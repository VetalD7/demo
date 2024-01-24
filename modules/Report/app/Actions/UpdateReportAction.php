<?php

namespace Modules\Report\Actions;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Illuminate\Log\Logger;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Modules\Report\Actions\DeliveryReports\DeliveryReportInterface;
use Modules\Report\Actions\Traits\ReportDeliverySetting;
use Modules\Report\Actions\Traits\ReportEmails;
use Modules\Report\Actions\Traits\ReportNotifiableUsers;
use Modules\Report\Exceptions\ReportNotCreatedException;
use Modules\Report\Models\Report;
use \Modules\Report\Actions\Traits\ReportStatus;
use Modules\Report\Repositories\Contracts\ReportRepository;

abstract class UpdateReportAction
{
    use ReportEmails,
        ReportDeliverySetting,
        ReportStatus,
        ReportNotifiableUsers;

    /**
     * @var DatabaseManager
     */
    protected DatabaseManager $databaseManager;

    /**
     * @var Logger
     */
    protected Logger $log;

    /**
     * @var ReportRepository
     */
    protected ReportRepository $repository;

    /**
     * @var DeliveryReportInterface
     */
    private DeliveryReportInterface $deliveryReport;

    /**
     * @param DatabaseManager         $databaseManager
     * @param Logger                  $log
     * @param ReportRepository        $repository
     * @param DeliveryReportInterface $deliveryReport
     */
    public function __construct(
        DatabaseManager $databaseManager,
        Logger $log,
        ReportRepository $repository,
        DeliveryReportInterface $deliveryReport
    ) {
        $this->databaseManager = $databaseManager;
        $this->log = $log;
        $this->repository = $repository;
        $this->deliveryReport = $deliveryReport;
    }

    /**
     * @param Report $report
     *
     * @return ShouldQueue|null
     */
    abstract protected function getProcessingJob(Report $report): ?ShouldQueue;

    /**
     * @param Report $report
     * @param array  $data
     *
     * @return Report
     * @throws \App\Exceptions\BaseException
     * @throws \Throwable
     */
    public function handle(Report $report, array $data = []): Report
    {
        $this->log->info('Updating report.', ['data' => $data]);

        $this->databaseManager->beginTransaction();

        try {
            $report = $this->updateReport($report, $data);
            $this->syncReportDeliverySetting($report, Arr::get($data, 'delivery'));

            $this->setNotifiableUsers($report, $data);

            $this->deliveryReport->handle($this->getProcessingJob($report), $report);
        } catch (\Throwable $throwable) {
            $this->databaseManager->rollBack();

            if ($throwable instanceof ValidationException) {
                throw $throwable;
            }

            throw ReportNotCreatedException::createFrom($throwable);
        }

        $this->databaseManager->commit();

        return $report;
    }

    /**
     * @param Report $report
     * @param array  $data
     *
     * @return Report
     */
    protected function updateReport(Report $report, array $data): Report
    {
        $data = $this->setReportStatus($data);

        $updateData = array_merge(Arr::get($data, 'report'), [
            'path' => null
        ]);

        return $this->repository->update($updateData, $report->getKey());
    }
}

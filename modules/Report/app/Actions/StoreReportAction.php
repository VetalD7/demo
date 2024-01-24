<?php

namespace Modules\Report\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Illuminate\Log\Logger;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Modules\Campaign\Repositories\Contracts\CampaignRepository;
use Modules\Report\Actions\DeliveryReports\DeliveryReportInterface;
use Modules\Report\Actions\Traits\ReportCampaigns;
use Modules\Report\Actions\Traits\ReportDeliverySetting;
use Modules\Report\Actions\Traits\ReportEmails;
use Modules\Report\Actions\Traits\ReportNotifiableUsers;
use Modules\Report\Exceptions\ReportNotCreatedException;
use Modules\Report\Models\Report;
use Modules\Report\Actions\Traits\ReportStatus;
use Modules\Report\Repositories\Contracts\ReportRepository;
use Modules\User\Models\User;

abstract class StoreReportAction
{
    use ReportEmails,
        ReportCampaigns,
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
     * @var CampaignRepository
     */
    protected CampaignRepository $campaignRepository;

    /**
     * @var DeliveryReportInterface
     */
    private DeliveryReportInterface $deliveryReport;

    /**
     * @var Authenticatable|User
     */
    private Authenticatable $user;

    /**
     * @param DatabaseManager         $databaseManager
     * @param Logger                  $log
     * @param ReportRepository        $repository
     * @param CampaignRepository      $campaignRepository
     * @param DeliveryReportInterface $deliveryReport
     * @param Authenticatable         $user
     */
    public function __construct(
        DatabaseManager         $databaseManager,
        Logger                  $log,
        ReportRepository        $repository,
        CampaignRepository      $campaignRepository,
        DeliveryReportInterface $deliveryReport,
        Authenticatable         $user
    ) {
        $this->databaseManager    = $databaseManager;
        $this->log                = $log;
        $this->repository         = $repository;
        $this->campaignRepository = $campaignRepository;
        $this->deliveryReport     = $deliveryReport;
        $this->user               = $user;
    }

    /**
     * @param Report $report
     *
     * @return ShouldQueue|null
     */
    abstract protected function getProcessingJob(Report $report): ?ShouldQueue;

    /**
     * @param array $data
     *
     * @return Report
     * @throws \App\Exceptions\BaseException
     * @throws \Throwable
     */
    public function handle(array $data = []): Report
    {
        $this->log->info('Storing new report.', ['data' => $data]);

        $this->databaseManager->beginTransaction();

        try {
            $report = $this->storeReport($data);

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
     * @param array $data
     *
     * @return Report
     */
    protected function storeReport(array $data): Report
    {
        $data = $this->setReportStatus($data);

        return $this->repository->create(Arr::get($data, 'report'));
    }
}

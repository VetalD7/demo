<?php

namespace Modules\Report\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Illuminate\Log\Logger;
use Illuminate\Support\Collection;
use Modules\Report\Actions\DestroyReportFileAction;
use Modules\Report\Models\Report;
use Modules\Report\Models\ReportType;
use Modules\Report\Repositories\ReportRepository;

class DeleteOldReportsCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'platform:report:delete_old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes report file from S3 bucket and removes report DB path for the "old" reports.';

    /**
     * @var Logger
     */
    protected Logger $log;

    /**
     * @var ReportRepository
     */
    protected ReportRepository $repository;

    /**
     * @var DatabaseManager
     */
    protected DatabaseManager $databaseManager;

    /**
     * @var DestroyReportFileAction
     */
    protected DestroyReportFileAction $destroyReportFileAction;

    /**
     * @param Logger                  $log
     * @param ReportRepository        $repository
     * @param DatabaseManager         $databaseManager
     * @param DestroyReportFileAction $destroyReportFileAction
     */
    public function __construct(
        Logger $log,
        ReportRepository $repository,
        DatabaseManager $databaseManager,
        DestroyReportFileAction $destroyReportFileAction
    ) {
        parent::__construct();

        $this->log = $log;
        $this->repository = $repository;
        $this->databaseManager = $databaseManager;
        $this->destroyReportFileAction = $destroyReportFileAction;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->log->info('Running delete advertiser reports files command.');
        $this->databaseManager->beginTransaction();

        try {
            $reports = $this->getReports();
            $this->deleteReportFiles($reports);
        } catch (\Throwable $exception) {
            $this->log->warning('Running delete advertiser reports files command failed.', [
                'reason' => $exception->getMessage(),
            ]);
            $this->databaseManager->rollBack();
            throw $exception;
        }

        $this->log->info('Running delete advertiser reports files command successfully finished.');
        $this->databaseManager->commit();
    }

    /**
     * Get all reports that files should be removed from storage.
     *
     * @return Collection
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    protected function getReports(): Collection
    {
        $expiration = config('report.file_expiration_hours');
        $date = Carbon::now()->subRealHours($expiration);

        /** @var Collection $reports */
        $reports = $this->repository
            ->byType(ReportType::ID_ADVERTISERS)
            ->olderThan($date)
            ->hasFile()
            ->all();

        $this->log->info('Amount of reports files to be deleted.', ['amount' => $reports->count()]);

        return $reports;
    }

    /**
     * @param Collection $reports
     */
    protected function deleteReportFiles(Collection $reports): void
    {
        if ($reports->count() === 0) {
            $this->log->info('Nothing to delete.');
            return;
        }

        $reports->each(function (Report $report): void {
            $this->destroyReportFileAction->handle($report);

            $report->path = null;
            $report->update_required = true;
            $report->save();

            $this->log->info('Report file deleted.', [
                'report' => $report->id,
            ]);
        });
    }
}

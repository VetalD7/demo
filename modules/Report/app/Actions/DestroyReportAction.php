<?php

namespace Modules\Report\Actions;

use Illuminate\Log\Logger;
use Modules\Report\Exceptions\ReportNotDeletedException;
use Modules\Report\Models\Report;

class DestroyReportAction
{
    /**
     * @var DestroyReportFileAction
     */
    protected DestroyReportFileAction $destroyReportFileAction;

    /**
     * @var Logger
     */
    protected Logger $log;

    /**
     * @param DestroyReportFileAction $destroyReportFileAction
     * @param Logger                  $log
     */
    public function __construct(DestroyReportFileAction $destroyReportFileAction, Logger $log)
    {
        $this->destroyReportFileAction = $destroyReportFileAction;
        $this->log = $log;
    }

    /**
     * Delete the report, relations and resources.
     *
     * @param Report $report
     * @throws ReportNotDeletedException
     */
    public function handle(Report $report): void
    {
        $this->log->info('Deleting report.', ['report_id' => $report->id]);

        if (!$report->delete()) {
            $this->log->warning('Report was not deleted.', ['report_id' => $report->id]);
            throw new ReportNotDeletedException(__('report::messages.report_was_not_deleted'));
        }

        $this->log->info('Report was successfully deleted.', ['report_id' => $report->id]);

        $this->destroyReportFileAction->handle($report);
    }
}

<?php

namespace Modules\Report\Actions\MetricsReport;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Report\Actions\MetricsReport\Base\UpdateMetricReportAction;
use Modules\Report\Jobs\ProcessSummaryReport;
use Modules\Report\Models\Report;

class UpdateSummaryReportAction extends UpdateMetricReportAction
{
    /**
     * @param Report $report
     *
     * @return ShouldQueue|null
     */
    public function getProcessingJob(Report $report): ?ShouldQueue
    {
        return new ProcessSummaryReport($report);
    }
}

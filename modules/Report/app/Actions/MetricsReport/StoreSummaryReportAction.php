<?php

namespace Modules\Report\Actions\MetricsReport;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Report\Actions\MetricsReport\Base\StoreMetricReportAction;
use Modules\Report\Jobs\ProcessSummaryReport;
use Modules\Report\Models\Report;

class StoreSummaryReportAction extends StoreMetricReportAction
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

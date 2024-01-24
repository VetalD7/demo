<?php

namespace Modules\Report\Actions\MetricsReport;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Campaign\Exceptions\CampaignNotFoundException;
use Modules\Report\Actions\MetricsReport\Base\UpdateMetricReportAction;
use Modules\Report\Http\Requests\MetricsReport\Traits\CheckCampaigns;
use Modules\Report\Jobs\ProcessDetailedReport;
use Modules\Report\Models\Report;

class UpdateDetailedReportAction extends UpdateMetricReportAction
{
    use CheckCampaigns;

    /**
     * @param Report $report
     * @param array  $data
     *
     * @return Report
     * @throws CampaignNotFoundException
     */
    protected function updateReport(Report $report, array $data): Report
    {
        return parent::updateReport($report, $this->checkCampaigns($data));
    }

    /**
     * @param Report $report
     *
     * @return ShouldQueue|null
     */
    public function getProcessingJob(Report $report): ?ShouldQueue
    {
        return new ProcessDetailedReport($report);
    }
}

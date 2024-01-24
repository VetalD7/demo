<?php

namespace Modules\Report\Actions\MetricsReport;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Campaign\Exceptions\CampaignNotFoundException;
use Modules\Report\Actions\MetricsReport\Base\StoreMetricReportAction;
use Modules\Report\Http\Requests\MetricsReport\Traits\CheckCampaigns;
use Modules\Report\Jobs\ProcessDetailedReport;
use Modules\Report\Models\Report;

class StoreDetailedReportAction extends StoreMetricReportAction
{
    use CheckCampaigns;

    /**
     * @param array $data
     *
     * @return Report
     * @throws CampaignNotFoundException
     */
    protected function storeReport(array $data): Report
    {
        return parent::storeReport($this->checkCampaigns($data));
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

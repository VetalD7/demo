<?php

namespace Modules\Report\Http\Controllers\Api\MetricsReport;

use App\Http\Controllers\Controller;
use Modules\Report\Http\Resources\MetricsReport\CampaignDetailedReportResource;
use Modules\Report\Models\Report;

class ShowCampaignDetailedReportController extends Controller
{
    /**
     * @param Report $report
     *
     * @return CampaignDetailedReportResource
     */
    public function __invoke(Report $report): CampaignDetailedReportResource
    {
        return new CampaignDetailedReportResource($report);
    }
}

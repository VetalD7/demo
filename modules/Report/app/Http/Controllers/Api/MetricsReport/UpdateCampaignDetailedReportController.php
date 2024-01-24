<?php

namespace Modules\Report\Http\Controllers\Api\MetricsReport;

use App\Exceptions\BaseException;
use App\Http\Controllers\Controller;
use Modules\Report\Actions\MetricsReport\UpdateDetailedReportAction;
use Modules\Report\Http\Requests\MetricsReport\StoreCampaignDetailedReportRequest;
use Modules\Report\Http\Resources\UpdateReportResource;
use Modules\Report\Models\Report;

class UpdateCampaignDetailedReportController extends Controller
{
    /**
     * @param StoreCampaignDetailedReportRequest $request
     * @param UpdateDetailedReportAction         $updateMetricReportAction
     * @param Report                             $report
     *
     * @return UpdateReportResource
     * @throws BaseException
     * @throws \Throwable
     */
    public function __invoke(
        StoreCampaignDetailedReportRequest $request,
        UpdateDetailedReportAction         $updateMetricReportAction,
        Report                             $report
    ): UpdateReportResource {
        return new UpdateReportResource($updateMetricReportAction->handle($report, $request->toArray()));
    }
}

<?php

namespace Modules\Report\Http\Controllers\Api\MetricsReport;

use App\Http\Controllers\Controller;
use Modules\Report\Actions\MetricsReport\StoreDetailedReportAction;
use Modules\Report\Http\Requests\MetricsReport\StoreCampaignDetailedReportRequest;
use Modules\Report\Http\Resources\StoreReportResource;

class StoreCampaignDetailedReportController extends Controller
{
    /**
     * @param StoreCampaignDetailedReportRequest $request
     * @param StoreDetailedReportAction          $storeMetricReportAction
     *
     * @return StoreReportResource
     * @throws \App\Exceptions\BaseException
     * @throws \Throwable
     */
    public function __invoke(
        StoreCampaignDetailedReportRequest $request,
        StoreDetailedReportAction          $storeMetricReportAction
    ): StoreReportResource {
        return new StoreReportResource($storeMetricReportAction->handle($request->toArray()));
    }
}

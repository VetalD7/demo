<?php

namespace Modules\Report\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Report\Actions\DestroyReportAction;
use Modules\Report\Models\Report;

class DestroyReportController extends Controller
{
    /**
     * Delete the specified resource.
     *
     * @param DestroyReportAction $action
     * @param Report $report
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Modules\Report\Exceptions\ReportNotDeletedException
     */
    public function __invoke(Report $report, DestroyReportAction $action)
    {
        $action->handle($report);

        return response([
            'message' => __('report::messages.you_have_removed_the_report'),
        ]);
    }
}

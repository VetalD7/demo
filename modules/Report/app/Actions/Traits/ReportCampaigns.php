<?php

namespace Modules\Report\Actions\Traits;

use Illuminate\Support\Arr;
use Modules\Report\Models\Report;

trait ReportCampaigns
{
    /**
     * @param Report $report
     * @param array  $targetings
     *
     * @return void
     */
    protected function saveReportTargetings(Report $report, array $targetings): void
    {
        $targetingsModelArray = array_map(function ($target) {
            return ['targeting_type_id' => $target];
        }, $targetings);

        $report->targetings()->detach();
        $report->targetings()->attach($targetingsModelArray);
    }

    /**
     * @param Report $report
     * @param array  $campaigns
     *
     * @return void
     */
    protected function saveReportCampaigns(Report $report, array $campaigns): void
    {
        $campaignsModelArray = array_map(function ($campaign) {
            return ['campaign_id' => $campaign];
        }, $campaigns);

        $report->campaigns()->detach();
        $report->campaigns()->attach($campaignsModelArray);
    }

    /**
     * @param Report $report
     * @param array  $data
     *
     * @return Report
     */
    protected function saveReportData(Report $report, array $data): Report
    {
        if (Arr::has($data, 'targetings')) {
            $this->saveReportTargetings($report, Arr::get($data, 'targetings'));
        }

        if (Arr::has($data, 'campaigns')) {
            $this->saveReportCampaigns($report, Arr::get($data, 'campaigns'));
        }

        return $report;
    }
}

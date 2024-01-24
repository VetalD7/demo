<?php

namespace Modules\Report\Actions\MetricsReport\Base;

use Modules\Report\Actions\Traits\ReportCampaigns;
use Modules\Report\Actions\UpdateReportAction;
use Modules\Report\Models\Report;

abstract class UpdateMetricReportAction extends UpdateReportAction
{
    use ReportCampaigns;

    /**
     * @param Report $report
     * @param array  $data
     *
     * @return Report
     */
    protected function updateReport(Report $report, array $data): Report
    {
        $report->emails()->delete();

        return $this->saveReportData(parent::updateReport($report, $data), $data);
    }
}

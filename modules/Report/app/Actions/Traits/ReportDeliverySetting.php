<?php

namespace Modules\Report\Actions\Traits;

use Modules\Report\Models\Report;

trait ReportDeliverySetting
{
    /**
     * @param Report $report
     * @param array  $data
     *
     * @return Report
     */
    public function syncReportDeliverySetting(Report $report, array $data): Report
    {
        $report->deliverySetting()->exists()
            ? $report->deliverySetting()->update($data)
            : $this->saveDeliverySetting($report, $data);

        return $report;
    }

    /**
     * @param Report $report
     * @param array  $data
     *
     * @return void
     */
    public function saveDeliverySetting(Report $report, array $data): void
    {
        $settings = $report->deliverySetting()->create($data);

        $report->deliverySetting()->save($settings);
    }
}

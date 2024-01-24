<?php

namespace Modules\Report\Actions\DeliveryReports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Report\Models\Report;

class ScheduleDelivery implements DeliveryReportInterface
{
    /**
     * @param ShouldQueue|null $action
     * @param Report           $report
     *
     * @return mixed|void
     */
    public function handle(?ShouldQueue $action, Report $report): void
    {
    }
}

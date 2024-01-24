<?php

namespace Modules\Report\Actions\DeliveryReports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Report\Models\Report;

interface DeliveryReportInterface
{
    /**
     * @param ShouldQueue|null $action
     * @param Report           $report
     *
     * @return void
     */
    public function handle(?ShouldQueue $action, Report $report): void;
}

<?php

namespace Modules\Report\Actions\DeliveryReports;

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Report\Models\Report;

class AsyncDelivery implements DeliveryReportInterface
{
    /**
     * @var Dispatcher
     */
    private Dispatcher $dispatcher;

    public function __construct()
    {
        $this->dispatcher = app(Dispatcher::class);
    }

    /**
     * @param ShouldQueue|null $action
     * @param Report           $report
     *
     * @return void
     */
    public function handle(?ShouldQueue $action, Report $report): void
    {
        $this->dispatcher->dispatch($action);
    }
}

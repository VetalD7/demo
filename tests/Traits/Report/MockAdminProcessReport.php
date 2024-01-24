<?php

namespace Tests\Traits\Report;

use Mockery;
use Modules\Report\Jobs\Base\ProcessAdminReport;

trait MockAdminProcessReport
{
    /**
     * @return void
     */
    private function mockAdminProcessReport(): void
    {
        $mock = Mockery::mock('ProcessAdminReport')->shouldAllowMockingProtectedMethods();

        $mock->shouldReceive('isLastIteration')
            ->andReturn(true);

        $mock->shouldReceive('handleLastIteration')
            ->andReturn(true);

        $mock->shouldReceive('getCsvPath')
            ->andReturn('/');


        $this->app->instance(ProcessAdminReport::class, $mock);
    }
}

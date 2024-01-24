<?php

namespace Modules\Report\Models\Traits;

use Illuminate\Support\Arr;
use Modules\Report\Database\Factories\ReportFactory;
use Modules\Report\Models\Report;
use Modules\Report\Models\ReportType;

trait CreateReport
{
    /**
     * Create test report using factory.
     *
     * @param array    $attributes
     * @param int|null $count
     *
     * @return Report|Report[]|\Illuminate\Database\Eloquent\Collection
     */
    private function createTestReport(array $attributes = [], int $count = null): Report
    {
        $map = [
            ReportType::ID_CAMPAIGN_SUMMARY  => function (ReportFactory $factory, array $attributes): ReportFactory {
                return $factory->campaignSummary($attributes);
            },
            ReportType::ID_CAMPAIGN_DETAILED => function (ReportFactory $factory, array $attributes): ReportFactory {
                return $factory->campaignDetailed($attributes);
            },
            'default'                        => function (ReportFactory $factory, array $attributes): ReportFactory {
                return $factory->override($attributes);
            },
        ];

        $callable = Arr::get($map, Arr::get($attributes, 'type_id', 'default')) ?? Arr::get($map, 'default');

        /** @var ReportFactory $factory */
        $factory = $callable(Report::factory(), $attributes);

        return $factory->count($count)->create();
    }
}

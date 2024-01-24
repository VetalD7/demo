<?php

namespace Tests\Feature\Report\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Auth\Guards\CognitoGuard;
use Modules\Report\Models\ReportDeliveryType;
use Modules\Report\Models\ReportType;
use Modules\Report\Models\Traits\CreateDeliverySetting;
use Modules\Report\Models\Traits\CreateReport;
use Tests\Feature\Organization\BaseOrganizationControllerTest;

class ShowReportControllerTest extends BaseOrganizationControllerTest
{
    use CreateReport,
        CreateDeliverySetting;

    /**
     * @test
     *
     * @param array $data
     *
     * @dataProvider requestDataProvider
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function showReportTest(array $data): void
    {
        $user = $this->{$data['user']};
        $report = $this->createTestReport([
            'user_id'    => $user->id,
            'account_id' => $user->activeAccount?->id,
            'type_id'    => Arr::get($data, 'report_type')
        ]);

        $this->createTestSettingsReport([
            'report_id' => $report->id,
            'type_id'   => Arr::get($data, 'delivery_type')
        ]);

        $this->actingAs($user, Arr::get($data, 'guard'));

        $response = $this->json(Request::METHOD_GET,
            route(Arr::get($data, 'request.route'), ['report' => $report->id]
            ));

        $response->assertOk();
    }

    public function requestDataProvider(): \Generator
    {
        yield [[
            'user'          => 'admin',
            'guard'          => CognitoGuard::ADMIN_GUARD,
            'report_type'   => ReportType::ID_ADVERTISERS,
            'delivery_type' => ReportDeliveryType::ID_DOWNLOAD,
            'request'       => [
                'route' => 'api.reports.advertisers.show'
            ],
        ]];
        yield [[
            'user'          => 'admin',
            'guard'          => CognitoGuard::ADMIN_GUARD,
            'report_type'   => ReportType::ID_DOWNLOAD,
            'delivery_type' => ReportDeliveryType::ID_DOWNLOAD,
            'request'       => [
                'route' => 'api.reports.download.show'
            ],
        ]];
        yield [[
            'user'          => 'admin',
            'guard'          => CognitoGuard::ADMIN_GUARD,
            'report_type'   => ReportType::ID_MISSING_ADS,
            'delivery_type' => ReportDeliveryType::ID_DOWNLOAD,
            'request'       => [
                'route' => 'api.reports.missing-ads.show'
            ],
        ]];
        yield [[
            'user'          => 'admin',
            'guard'          => CognitoGuard::ADMIN_GUARD,
            'report_type'   => ReportType::ID_PENDING_CAMPAIGNS,
            'delivery_type' => ReportDeliveryType::ID_DOWNLOAD,
            'request'       => [
                'route' => 'api.reports.pending-campaigns.show'
            ],
        ]];
        yield [[
            'user'          => 'advertiserMajor',
            'guard'          => CognitoGuard::ADVERTISER_GUARD,
            'report_type'   => ReportType::ID_CAMPAIGN_DETAILED,
            'delivery_type' => ReportDeliveryType::ID_DOWNLOAD,
            'request'       => [
                'route' => 'api.reports.campaign-detailed.show'
            ],
        ]];
        yield [[
            'user'          => 'advertiserMajor',
            'guard'          => CognitoGuard::ADVERTISER_GUARD,
            'report_type'   => ReportType::ID_CAMPAIGN_SUMMARY,
            'delivery_type' => ReportDeliveryType::ID_DOWNLOAD,
            'request'       => [
                'route' => 'api.reports.campaign-summary.show'
            ],
        ]];
    }
}

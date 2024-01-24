<?php

namespace Tests\Feature\Report\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Modules\Campaign\Models\Campaign;
use Modules\Campaign\Models\Traits\CreateCampaign;
use Modules\Report\Models\Report;
use Modules\Report\Models\ReportDeliveryType;
use Modules\Report\Models\ReportEmail;
use Modules\Report\Models\ReportTargetingTypes;
use Modules\Report\Models\ReportType;
use Modules\Report\Models\Traits\CreateDeliverySetting;
use Modules\Report\Models\Traits\CreateReport;
use Tests\Feature\Organization\BaseOrganizationControllerTest;

class UpdateReportControllerTest extends BaseOrganizationControllerTest
{
    use CreateReport,
        CreateDeliverySetting,
        CreateCampaign;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->campaign = $this->createTestCampaign([
            'user_id'    => $this->advertiserMajor->id,
            'account_id' => $this->advertiserMajor->activeAccount->id
        ]);
        $this->campaign->user()->associate($this->advertiserMajor);
        $this->campaign->save();
    }

    /**
     * @test
     *
     * @param array $data
     *
     * @dataProvider requestDataProvider
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function updateReportTest(array $data): void
    {
        $user = $this->{Arr::get($data, 'user')};
        $report = $this->createTestReport([
            'user_id'    => $user->id,
            'account_id' => $user->activeAccount?->id,
            'type_id'    => Arr::get($data, 'report_type')
        ]);

        $request = array_merge(
            Arr::get($data, 'request.params'),
            Arr::get($data, 'request.callbackParams') ? Arr::get($data, 'request.callbackParams')($this->campaign) : [],
            ['id' => $report->id]
        );

        $this->actingAs($user, $this->getGuard($data));
        $response = $this->json(
            Request::METHOD_PATCH,
            route(Arr::get($data, 'request.route'), ['report' => $report->id]),
            $request
        );

        $response->assertStatus(Arr::get($data, 'response.code'));
    }

    public function requestDataProvider(): \Generator
    {
        yield [[
            'user'          => 'admin',
            'report_type'   => ReportType::ID_ADVERTISERS,
            'delivery_type' => ReportDeliveryType::ID_DOWNLOAD,
            'request'       => [
                'route'  => 'api.reports.advertisers.update',
                'params' => [
                    'dateRange'         => [],
                    'notifiableUserIds' => [],
                    'name'              => 'test',
                    'delivery'          => [
                        'type'           => 'downloadable',
                        'scheduleParams' => [
                            'emails'    => [
                                'testadver@mail.com'
                            ],
                            'day'       => 1,
                            'frequency' => 2,
                        ]
                    ]
                ]
            ],
            'response'      => [
                'code' => Response::HTTP_OK
            ],
        ]];
        yield [[
            'user'          => 'admin',
            'report_type'   => ReportType::ID_MISSING_ADS,
            'delivery_type' => ReportDeliveryType::ID_DOWNLOAD,
            'request'       => [
                'route'  => 'api.reports.missing-ads.update',
                'params' => [
                    'dateRange' => [
                        'end'   => '06/10/2020',
                        'start' => '06/08/2020',
                    ],
                    'name'      => 'test',
                    'delivery'  => [
                        'type'           => 'scheduled',
                        'scheduleParams' => [
                            'emails'    => [
                                'test-adver@mail.com'
                            ],
                            'day'       => 3,
                            'frequency' => 2,
                        ]
                    ]
                ]
            ],
            'response'      => [
                'code' => Response::HTTP_OK
            ],
        ]];
        yield [[
            'user'          => 'admin',
            'report_type'   => ReportType::ID_PENDING_CAMPAIGNS,
            'delivery_type' => ReportDeliveryType::ID_DOWNLOAD,
            'request'       => [
                'route'  => 'api.reports.pending-campaigns.update',
                'params' => [
                    'dateRange' => [
                        'end'   => '06/10/2020',
                        'start' => '06/08/2020',
                    ],
                    'name'      => 'test',
                    'delivery'  => [
                        'type'           => 'scheduled',
                        'scheduleParams' => [
                            'emails'    => [
                                'test-adver@mail.com'
                            ],
                            'day'       => 2,
                            'frequency' => 2,
                        ]
                    ]
                ]
            ],
            'response'      => [
                'code' => Response::HTTP_OK
            ],
        ]];
        yield [[
            'user'          => 'admin',
            'report_type'   => ReportType::ID_SCHEDULED,
            'delivery_type' => ReportDeliveryType::ID_DOWNLOAD,
            'request'       => [
                'route'  => 'api.reports.scheduled.update',
                'params' => [
                    'emails'   => ['test-adver@mail.com'],
                    'name'     => 'test',
                    'delivery' => [
                        'type'           => 'scheduled',
                        'scheduleParams' => [
                            'emails'    => [
                                'test-adver@mail.com'
                            ],
                            'day'       => 2,
                            'frequency' => 2,
                        ]
                    ]
                ]
            ],
            'response'      => [
                'code' => Response::HTTP_OK
            ],
        ]];
        yield [[
            'user'          => 'advertiserMajor',
            'report_type'   => ReportType::ID_CAMPAIGN_DETAILED,
            'delivery_type' => ReportDeliveryType::ID_DOWNLOAD,
            'request'       => [
                'route'          => 'api.reports.campaign-detailed.update',
                'params'         => [
                    'name'              => 'test',
                    'notifiableUserIds' => [],
                    'targetings'        => [ReportTargetingTypes::ID_AUDIENCE_TYPE],
                    'dateRange'         => [
                        'end'   => null,
                        'start' => null,
                    ],
                    'delivery'          => [
                        'type'           => 'scheduled',
                        'scheduleParams' => [
                            'emails'    => [
                                'test-adver@mail.com'
                            ],
                            'day'       => 3,
                            'frequency' => 2,
                        ]
                    ]
                ],
                'callbackParams' => function (Campaign $campaign) {
                    return [
                        'campaigns' => [
                            [
                                'value' => $campaign->id,
                                'label' => $campaign->name
                            ]
                        ]
                    ];
                }
            ],
            'response'      => [
                'code' => Response::HTTP_OK
            ],
        ]];
        yield [[
            'user'          => 'advertiserMajor',
            'report_type'   => ReportType::ID_CAMPAIGN_SUMMARY,
            'delivery_type' => ReportDeliveryType::ID_DOWNLOAD,
            'request'       => [
                'route'          => 'api.reports.campaign-summary.update',
                'params'         => [
                    'notifiableUserIds' => [],
                    'name'              => 'test',
                    'targetings'        => [ReportTargetingTypes::ID_AUDIENCE_TYPE],
                    'dateRange'         => [
                        'end'   => null,
                        'start' => null,
                    ],
                    'delivery'          => [
                        'type'           => 'scheduled',
                        'scheduleParams' => [
                            'emails'    => [
                                'test-adver@mail.com'
                            ],
                            'day'       => 3,
                            'frequency' => 2,
                        ]
                    ]
                ],
                'callbackParams' => function (Campaign $campaign) {
                    return [
                        'campaigns' => [
                            'value' => $campaign->id,
                            'label' => $campaign->name
                        ]
                    ];
                }
            ],
            'response'      => [
                'code' => Response::HTTP_OK
            ],
        ]];

    }
}

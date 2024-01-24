<?php

namespace Tests\Feature\Report\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Modules\Campaign\Models\Campaign;
use Modules\Campaign\Models\Traits\CreateCampaign;
use Modules\Report\Models\Report;
use Modules\Report\Models\ReportTargetingTypes;
use Modules\Report\Models\Traits\CreateDeliverySetting;
use Modules\Report\Models\Traits\CreateReport;
use Tests\Feature\Organization\BaseOrganizationControllerTest;

class StoreReportControllerTest extends BaseOrganizationControllerTest
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
    public function storeReportTest(array $data): void
    {
        $responseCode = Arr::get($data, 'response.code');
        $user = $this->{$data['user']};
        $this->actingAs($user, $this->getGuard($data));

        $request = array_merge(
            Arr::get($data, 'request.params'),
            Arr::get($data, 'request.callbackParams') ? Arr::get($data, 'request.callbackParams')($this->campaign) : [],
        );

        $response = $this->json(Request::METHOD_POST, route(Arr::get($data, 'request.route')), $request);

        $response->assertStatus($responseCode);

        if ($responseCode === Response::HTTP_CREATED) {
            $report = Report::query()->where('user_id', $user->id)->latest('id')->first();
            $this->assertEquals(Arr::get($data, 'request.params.name'), $report->name);
        }
    }

    /**
     * @return \Generator
     */
    public function requestDataProvider(): \Generator
    {
        yield [[
            'user'     => 'admin',
            'request'  => [
                'route'  => 'api.reports.advertisers.store',
                'params' => [
                    'notifiableUserIds' => [],
                    'dateRange'         => [],
                    'name'              => 'test',
                    'delivery'          => [
                        'type' => 'downloadable',
                    ]
                ]
            ],
            'response' => [
                'code' => Response::HTTP_CREATED
            ],
        ]];
        yield [[
            'user'     => 'admin',
            'request'  => [
                'route'  => 'api.reports.missing-ads.store',
                'params' => [
                    'notifiableUserIds' => [],
                    'dateRange'         => [
                        'end'   => '06/10/2020',
                        'start' => '06/08/2020',
                    ],
                    'name'              => 'test',
                    'delivery'          => [
                        'type'           => 'downloadable',
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
            'response' => [
                'code' => Response::HTTP_CREATED
            ],
        ]];
        yield [[
            'user'     => 'admin',
            'request'  => [
                'route'  => 'api.reports.scheduled.store',
                'params' => [
                    'name'              => 'test',
                    'notifiableUserIds' => [],
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
                ]
            ],
            'response' => [
                'code' => Response::HTTP_CREATED
            ],
        ]];
        yield [[
            'user'     => 'admin',
            'request'  => [
                'route'  => 'api.reports.pending-campaigns.store',
                'params' => [
                    'notifiableUserIds' => [],
                    'dateRange'         => [
                        'end'   => '06/10/2020',
                        'start' => '06/08/2020',
                    ],
                    'name'              => 'test',
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
                ]
            ],
            'response' => [
                'code' => Response::HTTP_CREATED
            ],
        ]];
        yield [[
            'user'     => 'advertiserMajor',
            'request'  => [
                'route'          => 'api.reports.campaign-detailed.store',
                'params'         => [
                    'notifiableUserIds' => [],
                    'name'              => 'test',
                    'targetings'        => [ReportTargetingTypes::ID_LOCATION_TYPE],
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
                },
            ],
            'response' => [
                'code' => Response::HTTP_CREATED
            ],
        ]];
        yield [[
            'user'     => 'advertiserMajor',
            'request'  => [
                'route'          => 'api.reports.campaign-summary.store',
                'params'         => [
                    'name'              => 'test',
                    'notifiableUserIds' => [],
                    'dateRange'         => [
                        'end'   => '06/10/2020',
                        'start' => '06/08/2020',
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
                },
            ],
            'response' => [
                'code' => Response::HTTP_CREATED
            ],
        ]];
    }
}

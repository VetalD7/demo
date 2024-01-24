<?php

namespace Tests\Feature\Report\Controllers\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Report\Models\Traits\CreateReport;
use Modules\Report\Repositories\ReportRepository;
use Modules\User\Models\Traits\CreateUser;
use Modules\User\Models\User;
use Tests\TestCase;

class DestroyReportControllerTest extends TestCase
{
    use DatabaseTransactions,
        CreateUser,
        CreateReport;

    /**
     * @var ReportRepository
     */
    private ReportRepository $repository;

    /**
     * @var User
     */
    private User $advertiser;

    /**
     * Setup the test environment.
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->advertiser = $this->createTestAdvertiser();
        $this->actingAs($this->advertiser);

        $this->createTestReport([
            'user_id'    => $this->advertiser->id,
            'account_id' => $this->advertiser->activeAccount->id
        ]);

        $this->repository = app(ReportRepository::class);
    }

    /**
     * Test destroy report.
     */
    public function testDestroyReport(): void
    {
        $report = $this->repository->where(['user_id' => $this->advertiser->id])->first();

        $response = $this->delete(route('api.reports.destroy', ['report' => $report->id]));

        $response->assertOk();
    }
}

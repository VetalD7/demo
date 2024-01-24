<?php

namespace Tests\Feature\Report\Controllers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Auth\Guards\CognitoGuard;
use Modules\User\Models\Traits\CreateUser;
use Modules\User\Database\Seeders\RolesTableSeeder;
use Modules\User\Database\Seeders\UserStatusesTableSeeder;
use Tests\TestCase;
use Tests\Traits\Cognito\CognitoJwtValidationMock;
use Tests\Traits\HaapiMocks\AdminSignInMock;

class IndexControllerTest extends TestCase
{
    use DatabaseTransactions, CreateUser, CognitoJwtValidationMock, AdminSignInMock;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mockJwtValidation();
        $this->seed(UserStatusesTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
    }

    /**
     * Admin should be able to open listing of reports.
     */
    public function testAdminOpensThePage(): void
    {
        $admin = $this->createTestAdmin();
        $this->actingAs($admin, CognitoGuard::ADMIN_GUARD);

        $this->mockAdminSignInActionSuccess();

        $response = $this->get(route('reports.index'));

        $response->assertOk();
    }

    /**
     * Readonly admin should be able to open listing of reports.
     */
    public function testReadonlyAdminOpensThePage(): void
    {
        $readonly = $this->createTestReadOnlyAdmin();
        $this->actingAs($readonly, CognitoGuard::ADMIN_GUARD);

        $this->mockAdminSignInActionSuccess();

        $response = $this->get(route('reports.index'));

        $response->assertOk();
    }

    /**
     * Advertiser should be able to open listing of reports.
     */
    public function testAdvertiserOpensThePage(): void
    {
        $advertiser = $this->createTestAdvertiser();
        $this->actingAs($advertiser);

        $response = $this->get(route('reports.index'));

        $response->assertOk();
    }
}

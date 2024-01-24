<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Guards\CognitoGuard;
use Modules\Auth\Guards\OrganizationSession;
use Modules\Auth\Services\AuthService\Contracts\AuthService;
use Modules\Organization\Actions\Impersonality\LoginImpersonateAction;
use Modules\Organization\Actions\SellerMode\SellerModeLoginAction;
use Modules\Organization\Models\Organization;
use Modules\User\Models\Traits\CreateUser;
use Modules\User\Models\User;
use Tests\Traits\Auth\HandlesPermissions;
use Tests\Traits\Cognito\CognitoTokensHelper;

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions,
        CreatesApplication,
        CognitoTokensHelper,
        HandlesPermissions,
        CreateUser;

    /**
     *  Save last response
     *
     * @var Response|null A Response instance
     */
    static $lastResponse;

    /**
     *  Modify to save response
     *
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param array  $cookies
     * @param array  $files
     * @param array  $server
     * @param string $content
     *
     * @return \Illuminate\Http\Response
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $response = parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);

        static::$lastResponse = $response;

        return $response;
    }

    /**
     * If test failed show full response body.
     */
    protected function tearDown(): void
    {
        if ($this->hasFailed()) {
            try {
                echo sprintf(' Full response body: "%s ".', static::$lastResponse->getContent());
            } catch (\Throwable $exception) {
                echo sprintf(' Cannot log response body ');
            }
        }

        parent::tearDown();
    }

    /**
     * Set the currently logged in user for the application.
     *
     * @param Authenticatable $user
     * @param null            $driver
     * @param null            $token
     *
     * @return BaseTestCase
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
     * @throws \Exception
     */
    public function actingAs(Authenticatable $user, $driver = null, $token = null)
    {
        $result = parent::actingAs($user, $driver);
        /** @var AuthService $authService */
        $authService = $this->app->make(AuthService::class);

        $token = $token ?: $this->makeCognitoTokens();
        $authService->setTokens($user->getKey(), $token);

        $user->refresh();
        $this->handlePermissions($user);

        return $result;
    }

    /**
     * @param User $user
     *
     * @return void
     * @throws \Exception
     */
    public function actingAsByImpersonated(User $user): void
    {
        $impersonatedAction = app(LoginImpersonateAction::class);
        $data = [];
        $routeParams = [
            'name' => 'management.profile',
            'params' => []
        ];
        Arr::set($data, 'redirectLogoutUrl', $routeParams);
        $impersonatedAction->handle($user, $user->organizations->first(), $data);
    }

    /**
     * @param User         $user
     * @param Organization $organization
     *
     * @return void
     * @throws \App\Exceptions\BaseException
     * @throws \Modules\User\Exceptions\UserNotFoundException
     * @throws \Throwable
     */
    public function actingAsByOrganizationSeller(User $user, Organization $organization): void
    {
        /**
         * @var SellerModeLoginAction $sellerModeAction
         */
        $sellerModeAction = app(SellerModeLoginAction::class);
        $data = [];
        $routeParams = [
            'name' => 'management.profile',
            'params' => []
        ];
        Arr::set($data, 'redirectLogoutUrl', $routeParams);
        $sellerModeAction->handle($user, $organization, $data);
    }
}

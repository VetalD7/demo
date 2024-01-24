<?php

namespace Modules\Report;

use App\Providers\ModuleServiceProvider;
use Modules\Dhaapi\Actions\Reporter\Report\Contracts\CampaignsCsvContract;
use Modules\Dhaapi\Actions\Reporter\Report\CampaignsCsv;
use Modules\Report\Actions\DeliveryReports\DeliveryReportInterface;
use Modules\Report\Actions\DeliveryReports\AsyncDelivery;
use Modules\Report\Actions\DeliveryReports\SyncDelivery;
use Modules\Report\Actions\DeliveryReports\ScheduleDelivery;
use Modules\Report\Actions\MetricsReport\GenerateDetailedReportAction;
use Modules\Report\Actions\MetricsReport\GenerateSummaryReportAction;
use Modules\Report\Actions\MetricsReport\GetDaapiData\Contracts\CampaignsCsvParamsBuilderContract;
use Modules\Report\Actions\MetricsReport\GetDaapiData\DetailedCampaignsCsvParamsBuilder;
use Modules\Report\Actions\MetricsReport\GetDaapiData\SummaryCampaignsCsvParamsBuilder;
use Modules\Report\Actions\MetricsReport\GetDownloadSummaryReportAction;
use Modules\Report\Commands\DeleteOldReportsCommand;
use Modules\Report\DataTable\Repositories\ReportsDataTableRepository;
use Modules\Report\DataTable\Repositories\Contracts\ReportsDataTableRepository as ReportsDataTableRepositoryContract;
use Modules\Report\Models\ReportDeliveryType;
use Modules\Report\Repositories\Contracts\ReportRepository as ReportRepositoryContract;
use Modules\Report\Commands\ReportMakeCommand;
use Modules\Report\Commands\SendScheduledReportCommand;
use Modules\Report\Policies\ReportPolicy;
use Modules\Report\Repositories\ReportRepository;
use Modules\Report\States\CompletedState;
use Modules\Report\States\SubmittedState;

class ReportServiceProvider extends ModuleServiceProvider
{
    /**
     * List of all available policies.
     *
     * @var array
     */
    protected $policies = [
        'report' => ReportPolicy::class,
    ];

    /**
     * List of module console commands
     *
     * @var array
     */
    protected $commands = [
        DeleteOldReportsCommand::class,
    ];

    /**
     * @var array
     */
    public $bindings = [
        'state-machine.report.states.completed'   => CompletedState::class,
        'state-machine.report.states.submitted'   => SubmittedState::class,
        ReportRepositoryContract::class           => ReportRepository::class,
        ReportsDataTableRepositoryContract::class => ReportsDataTableRepository::class,
    ];

    /**
     * Register any user services.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function boot(): void
    {
        parent::boot();
        $this->loadRoutes();
        $this->loadConfigs(['report', 'state-machine']);
        $this->commands($this->commands);
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();
        $this->setUpReportDeliveryBinding();
        $this->bindClasses();
    }


    /**
     * Get module prefix
     * @return string
     */
    protected function getPrefix(): string
    {
        return 'report';
    }

    /**
     * @return void
     */
    public function setUpReportDeliveryBinding(): void
    {
        $this->app->bind(DeliveryReportInterface::class, function () {
            $deliveryType = request()->get('delivery')['type'];

            if ($deliveryType == ReportDeliveryType::DOWNLOAD
                || $deliveryType == ReportDeliveryType::EMAIL_NOW
            ) {
                return new AsyncDelivery();
            }

            return new ScheduleDelivery();
        });
    }

    /**
     * @return void
     */
    private function bindClasses(): void
    {
        $this->app->bind(CampaignsCsvContract::class, function (): CampaignsCsvContract {
            return $this->app->make(CampaignsCsv::class);
        });

        $this->app->when([GenerateSummaryReportAction::class, GetDownloadSummaryReportAction::class])
            ->needs(CampaignsCsvParamsBuilderContract::class)
            ->give(function () {
                return new SummaryCampaignsCsvParamsBuilder();
            });

        $this->app->when([GenerateDetailedReportAction::class])
            ->needs(CampaignsCsvParamsBuilderContract::class)
            ->give(function () {
                return new DetailedCampaignsCsvParamsBuilder();
            });
    }
}

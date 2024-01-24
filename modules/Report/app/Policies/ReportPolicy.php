<?php

namespace Modules\Report\Policies;

use App\Policies\Policy;
use Modules\Report\Models\Report;
use Modules\Report\Models\ReportStatus;
use Modules\Report\Policies\Traits\AdvertisersReportPolicy;
use Modules\Report\Policies\Traits\DetailedCampaignReportPolicy;
use Modules\Report\Policies\Traits\DownloadReportPolicy;
use Modules\Report\Policies\Traits\MissingAdsReportPolicy;
use Modules\Report\Policies\Traits\PendingCampaignsReportPolicy;
use Modules\Report\Policies\Traits\ScheduleReportPolicy;
use Modules\Report\Policies\Traits\SummaryCampaignReportPolicy;
use Modules\User\Models\User;

class ReportPolicy extends Policy
{
    /**
     * Model class.
     *
     * @var string
     */
    protected string $model = Report::class;

    /**
     * Permissions.
     */
    public const PERMISSION_LIST_REPORT                     = 'list_report';
    public const PERMISSION_CREATE_CAMPAIGN_DETAILED_REPORT = 'create_campaign_detailed_report';
    public const PERMISSION_SHOW_CAMPAIGN_DETAILED_REPORT = 'show_campaign_detailed_report';
    public const PERMISSION_UPDATE_CAMPAIGN_DETAILED_REPORT = 'update_campaign_detailed_report';
    public const PERMISSION_DELETE_REPORT = 'delete_report';

    /**
     * Returns true if user can create report.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $this->createCampaignDetailed($user) || $this->createCampaignSummary($user);
    }

    /**
     * Returns true if user can view list of all reports.
     *
     * @param User $user
     *
     * @return bool
     */
    public function list(User $user): bool
    {
        return $this->checkPermission($user, self::PERMISSION_LIST_REPORT) && !$user->isImpersonated();
    }

    /**
     * Returns true if user can delete the report.
     *
     * @param User   $user
     * @param Report $report
     *
     * @return bool
     */
    public function delete(User $user, Report $report): bool
    {
        return $this->checkPermission($user, self::PERMISSION_DELETE_REPORT, $report);
    }

    /**
     * Returns true if user can create Campaign detailed reports.
     *
     * @param User $user
     *
     * @return bool
     */
    public function createCampaignDetailed(User $user): bool
    {
        return $this->checkPermission($user, self::PERMISSION_CREATE_CAMPAIGN_DETAILED_REPORT);
    }

    /**
     * Returns true if user can update Campaign detailed reports.
     *
     * @param User   $user
     * @param Report $report
     *
     * @return bool
     */
    public function updateCampaignDetailed(User $user, Report $report): bool
    {
        return $this->checkPermission($user, self::PERMISSION_UPDATE_CAMPAIGN_DETAILED_REPORT, $report);
    }

    /**
     * Returns true if user can show Campaign detailed reports.
     *
     * @param User   $user
     * @param Report $report
     *
     * @return bool
     * @throws \Modules\Campaign\Exceptions\NotActiveAdAccountException
     */
    public function showCampaignDetailed(User $user, Report $report): bool
    {
        return $this->checkPermission($user, self::PERMISSION_SHOW_CAMPAIGN_DETAILED_REPORT, $report);
    }
}

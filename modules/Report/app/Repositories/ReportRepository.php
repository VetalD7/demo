<?php

namespace Modules\Report\Repositories;

use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Modules\Report\Exceptions\ReportNotGeneratedException;
use Modules\Report\Models\Report;
use Modules\Report\Models\ReportDeliveryFrequency;
use Modules\Report\Repositories\Contracts\ReportRepository as ReportRepositoryInterface;
use Modules\Report\Repositories\Criteria\JoinUsersToReportCriteria;
use Modules\Report\Repositories\Criteria\DeliveryFrequencyIdsCriteria;
use Modules\Report\Repositories\Criteria\ExecutionDayReportCriteria;
use Modules\Report\Repositories\Criteria\HasFileCriteria;
use Modules\Report\Repositories\Criteria\OlderThanCriteria;
use Modules\Report\Repositories\Criteria\ReportStatusIdsCriteria;
use Modules\Report\Repositories\Criteria\TypeIdCriteria;
use Modules\Targeting\Repositories\Criteria\DistinctCriteria;
use Modules\User\Repositories\Criteria\JoinCampaignsToReportCriteria;
use Modules\User\Repositories\Criteria\AddRolesCriteria;
use Modules\User\Repositories\Criteria\UserIdsCriteria;

class ReportRepository extends Repository implements ReportRepositoryInterface
{
    /**
     * @var bool
     */
    protected $skipPresenter = true;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Report::class;
    }

    /**
     * Filter reports by status (few may be passed).
     *
     * @param array $ids
     *
     * @return ReportRepository
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function byStatus(array $ids): ReportRepositoryInterface
    {
        return $this->pushCriteria(new ReportStatusIdsCriteria($ids));
    }

    /**
     * Filter reports by type id.
     *
     * @param int $typeId
     *
     * @return ReportRepositoryInterface
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function byType(int $typeId): ReportRepositoryInterface
    {
        return $this->pushCriteria(new TypeIdCriteria($typeId));
    }
}

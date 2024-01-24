<?php

namespace Modules\Report\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Modules\Report\Models\ReportDeliveryType;
use Modules\Report\Models\ReportStatus;
use Modules\Report\Models\ReportType;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

class ReplacedNameReportStatus implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param \Illuminate\Database\Eloquent\Model|Builder $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository): Builder
    {
        return $model->selectRaw(
            'IF (
                    report_statuses.id = ? AND report_delivery_settings.type_id != ?, ?, report_statuses.name
            ) AS report_status',
            [
                ReportStatus::ID_DRAFT,
                ReportDeliveryType::ID_SCHEDULED,
                ReportStatus::SUBMITTED
            ]
        )->leftJoin(
            'report_statuses',
            'reports.status_id',
            '=',
            'report_statuses.id'
        );
    }
}

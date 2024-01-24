<?php

namespace Modules\Report\Actions\Traits;

use Illuminate\Support\Arr;
use Modules\Report\Models\ReportDeliveryType;
use \Modules\Report\Models\ReportStatus as ModelReportStatus;

trait ReportStatus
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function setReportStatus(array $data): array
    {
        if (Arr::get($data, 'delivery.type_id') === ReportDeliveryType::ID_EMAIL_NOW) {
            Arr::set($data, 'report.status_id', ModelReportStatus::ID_SUBMITTED);
        } else {
            Arr::set($data, 'report.status_id', ModelReportStatus::ID_DRAFT);
        }

        return $data;
    }
}

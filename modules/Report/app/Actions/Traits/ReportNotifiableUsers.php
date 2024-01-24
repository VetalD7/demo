<?php

namespace Modules\Report\Actions\Traits;

use Illuminate\Support\Arr;
use Modules\Report\Models\Report;
use Modules\Report\Models\ReportNotifiableUser;

trait ReportNotifiableUsers
{
    /**
     * @param array  $data
     * @param Report $report
     *
     * @return void
     */
    public function setNotifiableUsers(Report $report, array $data = []): void
    {
        $notifiableUserIdsArray = array_map(function ($userId) {
            return new ReportNotifiableUser(['user_id' => $userId]);
        }, Arr::get($data, 'notifiableUserIds', []));

        $report->notifiableUsers()->delete();
        $report->notifiableUsers()->saveMany($notifiableUserIdsArray);
    }
}

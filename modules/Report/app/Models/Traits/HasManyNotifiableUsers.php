<?php

namespace Modules\Report\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Report\Models\ReportNotifiableUser;

/**
 * @property ReportNotifiableUser[]|\Illuminate\Database\Eloquent\Collection $notifiableUsers
 */
trait HasManyNotifiableUsers
{
    /**
     * @return HasMany
     */
    public function notifiableUsers(): HasMany
    {
        return $this->hasMany(ReportNotifiableUser::class, 'report_id', 'id');
    }
}

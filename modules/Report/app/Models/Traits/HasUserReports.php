<?php

namespace Modules\Report\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Report\Models\Report;

/**
 * Trait HasUserReports
 * @package Modules\Report\Models\Traits
 * @property Report[] $reports
 */
trait HasUserReports
{
    /**
     * @return HasMany
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'user_id');
    }
}

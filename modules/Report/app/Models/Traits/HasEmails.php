<?php

namespace Modules\Report\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Report\Models\ReportEmail;

/**
 * @property ReportEmail[]|\Illuminate\Database\Eloquent\Collection $emails
 */
trait HasEmails
{
    /**
     * @return HasMany
     */
    public function emails(): HasMany
    {
        return $this->hasMany(ReportEmail::class);
    }
}

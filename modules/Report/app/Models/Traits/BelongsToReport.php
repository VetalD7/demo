<?php

namespace Modules\Report\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Report\Models\Report;

/**
 * Trait BelongsToReport
 * @package Modules\Report\Models\Traits
 * @property Report $report
 */
trait BelongsToReport
{
    /**
     * @return BelongsTo
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}

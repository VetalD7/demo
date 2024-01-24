<?php

namespace Modules\Report\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Report\Models\ReportTargetingTypes;

/**
 * @property \Illuminate\Database\Eloquent\Collection $targets
 */
trait BelongsToManyTargetingTypes
{
    /**
     * @return BelongsToMany
     */
    public function targetings(): BelongsToMany
    {
        return $this->belongsToMany(
            ReportTargetingTypes::class,
            'report_targetings',
            'report_id',
            'targeting_type_id'
        );
    }
}

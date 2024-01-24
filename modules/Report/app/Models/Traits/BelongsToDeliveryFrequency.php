<?php

namespace Modules\Report\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Report\Models\ReportDeliveryFrequency;

/**
 * Trait BelongsToDeliveryFrequency
 * @package Modules\Report\Models\Traits
 * @property ReportDeliveryFrequency $deliveryFrequency
 */
trait BelongsToDeliveryFrequency
{
    /**
     * @return BelongsTo
     */
    public function deliveryFrequency(): BelongsTo
    {
        return $this->belongsTo(ReportDeliveryFrequency::class, 'frequency_id');
    }
}

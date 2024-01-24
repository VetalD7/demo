<?php

namespace Modules\Report\Models;

use App\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Report\Models\Traits\BelongsToDeliveryFrequency;

/**
 * @property int     $id
 * @property int     $report_id
 * @property int     $type_id
 * @property boolean $queued
 * @property int     $frequency_id
 * @property int     $execution_day
 */
class ReportDeliverySetting extends Model
{
    use BelongsToDeliveryFrequency,
        HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'report_id',
        'type_id',
        'queued',
        'frequency_id',
        'execution_day'
    ];
}

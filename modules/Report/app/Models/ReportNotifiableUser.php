<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int     $id
 * @property integer $report_id
 * @property integer $user_id
 */
class ReportNotifiableUser extends Model
{
    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'report_id',
        'user_id'
    ];
}

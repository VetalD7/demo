<?php

namespace Modules\Report\Models;

use App\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Report\Models\Traits\BelongsToReport;

/**
 * Class ReportEmail
 * @package Modules\Report\Models
 * @property string $email
 */
class ReportEmail extends Model
{
    use BelongsToReport,
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
        'email',
        'report_id',
    ];
}

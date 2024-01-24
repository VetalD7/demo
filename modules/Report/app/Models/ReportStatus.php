<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportStatus
 * @package Modules\Report\Models
 * @property string $name
 */
class ReportStatus extends Model
{
    /**
     * Available statuses.
     */
    public const ID_DRAFT     = 1;
    public const ID_SUBMITTED = 2;
    public const ID_FAILED    = 3;
    public const ID_COMPLETED = 4;
    public const ID_PAUSED    = 5;

    /**
     * Ids of statuses that indicate that report is pending to be processed.
     */
    public const PENDING_STATUS = [
        self::SUBMITTED,
    ];

    /**
     * User creates a report.
     */
    public const DRAFT = 'draft';

    /**
     * Queue or cron starts to handle the report generation.
     */
    public const SUBMITTED = 'submitted';

    /**
     * An error occurs while generating report.
     */
    public const FAILED = 'failed';

    /**
     * Report has been successfully generated.
     */
    public const COMPLETED = 'completed';

    /**
     * Report has been paused (scheduled report will skip its generation).
     */
    public const PAUSED = 'paused';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}

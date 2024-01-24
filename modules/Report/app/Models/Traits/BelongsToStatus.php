<?php

namespace Modules\Report\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Report\Models\ReportStatus;

/**
 * @property ReportStatus $status
 * @property string       $display_status
 * @mixin \Modules\Report\Models\Report
 */
trait BelongsToStatus
{
    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ReportStatus::class, 'status_id');
    }

    /**
     * Returns true if the report is paused.
     *
     * @return bool
     */
    public function isPaused(): bool
    {
        return $this->status_id === ReportStatus::ID_PAUSED;
    }

    /**
     * Get human readable status.
     *
     * @return string
     */
    public function getDisplayStatusAttribute(): string
    {
        if ($this->status_id === ReportStatus::ID_DRAFT && $this->isDownloadable()) {
            return ReportStatus::SUBMITTED;
        }

        return $this->status->name;
    }
}

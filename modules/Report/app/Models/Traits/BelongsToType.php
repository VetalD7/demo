<?php

namespace Modules\Report\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Report\Models\ReportType;

/**
 * Trait BelongsToType
 * @package Modules\Report\Models\Traits
 * @property ReportType $type
 * @property int $type_id
 */
trait BelongsToType
{
    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ReportType::class, 'type_id');
    }

    /**
     * Returns true if the report is scheduled type.
     *
     * @return bool
     */
    public function isScheduled(): bool
    {
        return $this->type_id == ReportType::ID_SCHEDULED;
    }

    /**
     * Returns true if the report is download type.
     * @return bool
     */
    public function isDownload(): bool
    {
        return $this->type_id === ReportType::ID_DOWNLOAD;
    }

    /**
     * Returns true if the report is advertisers type.
     * @return bool
     */
    public function isAdvertisers(): bool
    {
        return $this->type_id == ReportType::ID_ADVERTISERS;
    }

    /**
     * Returns true if the report is campaigns type.
     * @return bool
     */
    public function isPendingCampaigns(): bool
    {
        return $this->type_id == ReportType::ID_PENDING_CAMPAIGNS;
    }

    /**
     * Returns true if the report is summary type.
     * @return bool
     */
    public function isSummary(): bool
    {
        return $this->type_id == ReportType::ID_CAMPAIGN_SUMMARY;
    }

    /**
     * Returns true if the report is detailed type.
     * @return bool
     */
    public function isDetailed(): bool
    {
        return $this->type_id == ReportType::ID_CAMPAIGN_DETAILED;
    }

    /**
     * Returns true if the report is  missing-ads type.
     * @return bool
     */
    public function isMissingAds(): bool
    {
        return $this->type_id == ReportType::ID_MISSING_ADS;
    }

    /**
     * Returns true if notification about generated report could be shown in on-site notifications.
     * @return bool
     */
    public function isNotifiable(): bool
    {
        return in_array($this->type_id, ReportType::NOTIFIABLE_TYPE_IDS);
    }

    /**
     * Returns true if notification about generated report could be sent via email.
     * @return bool
     */
    public function isEmailable(): bool
    {
        return in_array($this->type_id, ReportType::EMAILABLE_TYPE_IDS);
    }

    /**
     * Returns true if the report is belongs to advertiser user.
     * @return bool
     */
    public function isBelongToAdvertiser(): bool
    {
        return in_array($this->type_id, ReportType::ADVERTISERS_TYPE_IDS);
    }
}

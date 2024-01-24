<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $name
 */
class ReportType extends Model
{
    /**
     * Default types.
     */
    public const SCHEDULED = 'scheduled';
    public const DOWNLOAD = 'download';
    public const ADVERTISERS = 'advertisers';
    public const PENDING_CAMPAIGNS = 'pending_campaigns';
    public const MISSING_ADS = 'missing_ads';
    public const CAMPAIGN_SUMMARY = 'campaign_summary';
    public const CAMPAIGN_DETAILED = 'campaign_detailed';

    public const ID_SCHEDULED = 1;
    public const ID_DOWNLOAD = 2;
    public const ID_ADVERTISERS = 3;
    public const ID_PENDING_CAMPAIGNS = 4;
    public const ID_MISSING_ADS = 5;
    public const ID_CAMPAIGN_SUMMARY = 7;
    public const ID_CAMPAIGN_DETAILED = 8;

    /**
     * Mapped types
     */
    public const TYPES = [
        self::ID_SCHEDULED         => self::SCHEDULED,
        self::ID_DOWNLOAD          => self::DOWNLOAD,
        self::ID_ADVERTISERS       => self::ADVERTISERS,
        self::ID_PENDING_CAMPAIGNS => self::PENDING_CAMPAIGNS,
        self::ID_MISSING_ADS       => self::MISSING_ADS,
        self::ID_CAMPAIGN_DETAILED => self::CAMPAIGN_DETAILED,
        self::ID_CAMPAIGN_SUMMARY  => self::CAMPAIGN_SUMMARY,
    ];

    /**
     * Downloadable type ids.
     */
    public const DOWNLOADABLE_TYPE_IDS = [
        self::ID_DOWNLOAD,
        self::ID_ADVERTISERS,
        self::ID_PENDING_CAMPAIGNS,
        self::ID_MISSING_ADS
    ];

    /**
     * Emailable type ids.
     */
    public const EMAILABLE_TYPE_IDS = [
        self::ID_SCHEDULED,
        self::ID_MISSING_ADS,
        self::ID_CAMPAIGN_DETAILED,
        self::ID_CAMPAIGN_SUMMARY
    ];

    /**
     * Notifiable type ids.
     */
    public const NOTIFIABLE_TYPE_IDS = [
        self::ID_SCHEDULED,
        self::ID_CAMPAIGN_DETAILED,
        self::ID_CAMPAIGN_SUMMARY,
    ];

    /**
     * Advertsers type ids.
     */
    public const ADVERTISERS_TYPE_IDS = [
        self::ID_CAMPAIGN_DETAILED,
        self::ID_CAMPAIGN_SUMMARY,
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param string $type
     *
     * @return int
     */
    public static function getTypeId(string $type): int
    {
        $typeId = array_search($type, self::TYPES);
        if ($typeId === false) {
            throw new InvalidArgumentException(sprintf('Unsupported report type ("%s") was given.', $type));
        }

        return $typeId;
    }
}

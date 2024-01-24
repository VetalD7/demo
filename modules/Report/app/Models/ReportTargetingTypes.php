<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Report\Models\Traits\BelongsToReport;
use Modules\Targeting\Models\AgeGroup;
use Modules\Targeting\Models\Audience;
use Modules\Targeting\Models\DeviceGroup;
use Modules\Targeting\Models\Genre;
use Modules\Targeting\Models\Location;

/**
 * @property string $name
 * @property string $description
 */
class ReportTargetingTypes extends Model
{
    /**
     * Targetings type ids.
     */
    public const ID_AGE_TYPE      = 1;
    public const ID_LOCATION_TYPE = 2;
    public const ID_AUDIENCE_TYPE = 3;
    public const ID_PLATFORM_TYPE = 4;
    public const ID_GENRE_TYPE    = 5;

    public const LIST_TARGETING_TYPES_IDS = [
        self::ID_LOCATION_TYPE,
        self::ID_AUDIENCE_TYPE,
        self::ID_GENRE_TYPE,
        self::ID_AGE_TYPE,
        self::ID_PLATFORM_TYPE,
    ];

    public const TARGETING_TYPES = [
        self::ID_AGE_TYPE      => AgeGroup::TYPE_NAME,
        self::ID_LOCATION_TYPE => Location::TYPE_NAME,
        self::ID_AUDIENCE_TYPE => Audience::TYPE_NAME,
        self::ID_PLATFORM_TYPE => DeviceGroup::TYPE_NAME,
        self::ID_GENRE_TYPE    => Genre::TYPE_NAME
    ];

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
        'name',
        'description'
    ];
}

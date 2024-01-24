<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $name
 */
class ReportDeliveryType extends Model
{
    /**
     * Delivery types id
     */
    public const ID_SCHEDULED = 1;
    public const ID_DOWNLOAD = 2;
    public const ID_EMAIL_NOW = 3;
    public const ID_DOWNLOAD_NOW = 4;

    /**
     * Delivery types
     */
    public const SCHEDULED = 'scheduled';
    public const DOWNLOAD = 'downloadable';
    public const EMAIL_NOW = 'email_now';
    public const DOWNLOAD_NOW = 'downloadable_now';

    /**
     * Delivery types list for cast
     */
    public const TYPES_LIST = [
        self::ID_SCHEDULED    => self::SCHEDULED,
        self::ID_DOWNLOAD     => self::DOWNLOAD,
        self::ID_EMAIL_NOW    => self::EMAIL_NOW,
        self::ID_DOWNLOAD_NOW => self::DOWNLOAD_NOW,
    ];

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;
}

<?php

namespace Modules\Report\Models;

use App\Exceptions\BaseException;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $name
 * @property string $translated_name
 */
class ReportDeliveryFrequency extends Model
{
    /**
     * Default frequency types.
     */
    public const DAILY = 'daily';
    public const WEEKLY = 'weekly';
    public const MONTHLY = 'monthly';

    public const ID_DAILY = 1;
    public const ID_WEEKLY = 2;
    public const ID_MONTHLY = 3;

    /**
     * frequency types id array
     */
    public const FREQUENCY_TYPES_ID = [
        self::ID_DAILY,
        self::ID_WEEKLY,
        self::ID_MONTHLY,
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return string
     */
    public function getTranslatedNameAttribute(): string
    {
        return __("report::labels.frequency.{$this->name}");
    }

    /**
     * @return null|string
     */
    public function getDescriptionAttribute(): ?string
    {
        if ($this->id === self::ID_MONTHLY) {
            return __("report::labels.reportModal.frequency_type_description.{$this->name}");
        }

        return null;
    }

    /**
     * Get start date for this type of report.
     *
     * @return CarbonInterface
     * @throws BaseException
     */
    public function getDateFrom(): CarbonInterface
    {
        switch ($this->id) {
            case self::ID_DAILY:
                return Carbon::yesterday()->startOfDay();
            case self::ID_WEEKLY:
                return Carbon::yesterday()->subWeek()->startOfDay();
            case self::ID_MONTHLY:
                return Carbon::now()->subMonth()->startOfMonth()->startOfDay();
            default:
                throw new BaseException("Undefined delivery frequency {$this->id}.");
        }
    }

    /**
     * Get end date for this type of report.
     *
     * @return CarbonInterface
     * @throws BaseException
     */
    public function getDateTo(): CarbonInterface
    {
        $start = $this->getDateFrom();

        switch ($this->id) {
            case self::ID_DAILY:
                return $start->endOfDay();
            case self::ID_WEEKLY:
                return Carbon::yesterday()->endOfDay();
            case self::ID_MONTHLY:
                return $start->endOfMonth()->endOfDay();
            default:
                throw new BaseException("Undefined delivery frequency {$this->id}.");
        }
    }
}

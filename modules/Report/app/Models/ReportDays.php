<?php

namespace Modules\Report\Models;

use Illuminate\Support\Arr;

class ReportDays
{
    /**
     * Sunday id
     */
    public const SUNDAY_ID = 1;

    /**
     * Monday id
     */
    public const MONDAY_ID = 2;

    /**
     * Tuesday id
     */
    public const TUESDAY_ID = 3;

    /**
     * Wednesday id
     */
    public const WEDNESDAY_ID = 4;

    /**
     * Thursday id
     */
    public const THURSDAY_ID = 5;

    /**
     * Friday id
     */
    public const FRIDAY_ID = 6;

    /**
     * Saturday id
     */
    public const SATURDAY_ID = 7;

    /**
     * Sunday name
     */
    public const SUNDAY = 'sunday';

    /**
     * Monday name
     */
    public const MONDAY = 'monday';

    /**
     * Tuesday name
     */
    public const TUESDAY = 'tuesday';

    /**
     * Wednesday name
     */
    public const WEDNESDAY = 'wednesday';

    /**
     * Thursday name
     */
    public const THURSDAY = 'thursday';

    /**
     * Friday name
     */
    public const FRIDAY = 'friday';

    /**
     * Saturday name
     */
    public const SATURDAY = 'saturday';

    /**
     * Days id array
     */
    public const REPORT_DAYS_ID = [
        self::SUNDAY_ID,
        self::MONDAY_ID,
        self::TUESDAY_ID,
        self::WEDNESDAY_ID,
        self::THURSDAY_ID,
        self::FRIDAY_ID,
        self::SATURDAY_ID,
    ];

    /**
     * Map days with id
     */
    public const REPORT_DAYS_MAP = [
        self::SUNDAY_ID    => self::SUNDAY,
        self::MONDAY_ID    => self::MONDAY,
        self::TUESDAY_ID   => self::TUESDAY,
        self::WEDNESDAY_ID => self::WEDNESDAY,
        self::THURSDAY_ID  => self::THURSDAY,
        self::FRIDAY_ID    => self::FRIDAY,
        self::SATURDAY_ID  => self::SATURDAY,
    ];

    /**
     * @param string $day
     *
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public static function translateDay(string $day): string
    {
        return trans("days.{$day}");
    }

    /**
     * @param int|null $id
     *
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public static function translateDayById(?int $id = null): string
    {
        $dayName = Arr::get(self::REPORT_DAYS_MAP, $id);
        return trans("days.{$dayName}");
    }

    /**
     * @return array
     */
    public static function get(): array
    {
        return [
            [
                'id'   => self::SUNDAY_ID,
                'name' => self::translateDay(self::SUNDAY),
            ],
            [
                'id'   => self::MONDAY_ID,
                'name' => self::translateDay(self::MONDAY),
            ],
            [
                'id'   => self::TUESDAY_ID,
                'name' => self::translateDay(self::TUESDAY),
            ],
            [
                'id'   => self::WEDNESDAY_ID,
                'name' => self::translateDay(self::WEDNESDAY),
            ],
            [
                'id'   => self::THURSDAY_ID,
                'name' => self::translateDay(self::THURSDAY),
            ],
            [
                'id'   => self::FRIDAY_ID,
                'name' => self::translateDay(self::FRIDAY),
            ],
            [
                'id'   => self::SATURDAY_ID,
                'name' => self::translateDay(self::SATURDAY),
            ],
        ];
    }
}

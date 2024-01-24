<?php

namespace Modules\Report\Models\Traits;

use App\Helpers\DateFormatHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Report\Models\ReportDeliverySetting;
use Modules\Report\Models\ReportDeliveryType;

/**
* @property ReportDeliverySetting $deliverySetting
*/
trait HasDeliverySetting
{
    /**
     * @return HasOne
     */
    public function deliverySetting(): HasOne
    {
        return $this->hasOne(ReportDeliverySetting::class);
    }

    /**
     * @return bool
     */
    public function isProcessingJobDelivery(): bool
    {
        return in_array(
            $this->deliverySetting->type_id,
            [
                ReportDeliveryType::ID_DOWNLOAD,
                ReportDeliveryType::ID_EMAIL_NOW
            ]
        );
    }

    /**
     * Get start date for the report.
     *
     * @return Carbon
     * @throws \App\Exceptions\BaseException
     */
    public function getDateFrom(): Carbon
    {
        if ($this->deliverySetting->deliveryFrequency) {
            $date = $this->deliverySetting->deliveryFrequency->getDateFrom();
        } else {
            $date = $this->date_start->startOfDay();
        }

        return DateFormatHelper::convertToTimezone($date);
    }

    /**
     * Get end date for the report.
     *
     * @return Carbon
     * @throws \App\Exceptions\BaseException
     */
    public function getDateTo(): Carbon
    {
        if ($this->deliverySetting->deliveryFrequency) {
            $date = $this->deliverySetting->deliveryFrequency->getDateTo();
        } else {
            $date = $this->date_end->endOfDay();
        }

        return DateFormatHelper::convertToTimezone($date);
    }

    /**
     * Returns true if the report is of schedulable types.
     * @return bool
     */
    public function isSchedulable(): bool
    {
        return $this->deliverySetting->type_id === ReportDeliveryType::ID_SCHEDULED;
    }

    /**
     * Returns true if the report is of downloadable types.
     * @return bool
     */
    public function isDownloadable(): bool
    {
        return in_array($this->deliverySetting->type_id, [
            ReportDeliveryType::ID_DOWNLOAD,
            ReportDeliveryType::ID_DOWNLOAD_NOW,
        ]);
    }

    /**
     * Returns true if the report is of downloadable now types.
     * @return bool
     */
    public function isDownloadableNow(): bool
    {
        return $this->deliverySetting->type_id === ReportDeliveryType::ID_DOWNLOAD_NOW;
    }

    /**
     * Returns true if the report is of email now type.
     * @return bool
     */
    public function isEmailNow(): bool
    {
        return $this->deliverySetting->type_id === ReportDeliveryType::ID_EMAIL_NOW;
    }
}

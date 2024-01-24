<?php

namespace Modules\Report\Http\Requests\MetricsReport;

use App\Http\Requests\Request;
use App\Services\ValidationRulesService\Contracts\ValidationRules;
use Illuminate\Support\Arr;
use Modules\Report\Http\Requests\MetricsReport\Traits\CheckCampaign;
use Modules\Report\Http\Requests\Traits\ReportDeliveryData;
use Modules\Report\Http\Requests\Traits\ReportDeliveryRules;
use Modules\Report\Http\Requests\Traits\ReportRules;
use Modules\Report\Models\ReportDeliveryType;
use Modules\Report\Models\ReportType;

/**
 **
 * @property string $name
 * @property array  $dateRange
 * @property array  $delivery
 * @property array  $campaigns
 */
class StoreCampaignSummaryReportRequest extends Request
{
    use CheckCampaign;

    /**
     * @param ValidationRules $validationRules
     *
     * @return array
     */
    public function rules(ValidationRules $validationRules): array
    {
        return [
            'campaigns.value'                   => $validationRules->only(
                'report.campaigns_value',
                ['required', 'max', 'exists']
            ),
            'notifiableUserIds'                => $validationRules->only(
                'report.notifiableUserIds',
                ['nullable', 'array']
            ),
            'delivery.scheduleParams.frequency' => $validationRules->only(
                'report.delivery_frequency',
                ['integer', 'exists', 'max', 'nullable', 'required_if']
            ),
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'report'            => [
                'name'       => $this->name,
                'user_id'    => $this->user()->getKey(),
                'date_start' => null,
                'date_end'   => null,
                'type_id'    => ReportType::ID_CAMPAIGN_SUMMARY,
            ],
            'campaigns'         => [Arr::get($this->campaigns, 'value')],
            'notifiableUserIds' => $this->notifiableUserIds,
            'delivery'          => Arr::get($this->delivery, 'type') == ReportDeliveryType::DOWNLOAD_NOW
                ?  $this->getDownloadNowData()
                :  $this->getScheduleData(),
        ];
    }
}

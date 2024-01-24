<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Modules\Organization\Http\Resources\User\ReportUserInfoResource;
use Modules\Organization\Models\Account;
use Modules\Report\Models\ReportDeliveryFrequency;
use Modules\Report\Models\ReportTargetingTypes;

/**
 * @mixin ReportDeliveryFrequency
 */
class ReportSettingsResource extends JsonResource
{
    /**
     * @var Account|null
     */
    protected ?Account $account;

    /**
     * @param mixed        $resource
     * @param Account|null $account
     */
    public function __construct($resource, ?Account $account)
    {
        parent::__construct($resource);
        $this->account = $account;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'notifiable_users' => ReportUserInfoResource::collection(Arr::get($this->resource, 'users', [])),
            'frequency'        => [
                'delivery_frequency' => DeliveryFrequencyResource::collection(
                    Arr::get($this->resource, 'deliveryFrequency')
                ),
                'report_days'        => Arr::get($this->resource, 'reportDays'),
            ],
            $this->mergeWhen((bool)$this->account, function () {
                return [
                    'states' => [
                        'targetings' => new ReportTargetingStatesResource(
                            ReportTargetingTypes::TARGETING_TYPES,
                            $this->account
                        )
                    ],
                ];
            })
        ];
    }
}

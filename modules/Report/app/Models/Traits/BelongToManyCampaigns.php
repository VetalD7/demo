<?php

namespace Modules\Report\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Campaign\Models\Campaign;

/**
 * @property \Illuminate\Database\Eloquent\Collection $campaigns
 */
trait BelongToManyCampaigns
{
    /**
     * @return BelongsToMany
     */
    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(
            Campaign::class,
            'report_campaigns',
            'report_id',
            'campaign_id'
        );
    }
}

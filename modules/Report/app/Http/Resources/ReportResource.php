<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Report\Models\Report;

/**
 * @mixin Report
 * @property Report $resource
 */
class ReportResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'              => $this->resource->id,
            'display_status'  => $this->resource->display_status,
            'status_id'       => $this->resource->status_id,
            'states'          => new ReportStatesResource($this->resource),
            'url'             => $this->resource->url,
            'generated_at'    => $this->resource->generated_at,
            'update_required' => $this->resource->update_required,
        ];
    }
}

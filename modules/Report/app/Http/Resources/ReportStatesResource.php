<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Report\Models\Report;
use Modules\Report\Models\ReportStatus;

/**
 * @mixin Report
 */
class ReportStatesResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        /** @var \Modules\User\Models\User $user */
        $user = app(Guard::class)->user();

        return [
            'can_download' => $user->can('report.download', $this->resource) &&
                $this->resource->display_status !== ReportStatus::FAILED,
            'can_pause'    => $user->can('report.pause', $this->resource),
            'can_resume'   => $user->can('report.resume', $this->resource),
            'can_delete'   => $user->can('report.delete', $this->resource),
        ];
    }
}

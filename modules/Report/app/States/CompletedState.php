<?php

namespace Modules\Report\States;

use App\Models\QueuePriority;
use Modules\Report\Jobs\SendDetailedReportNotifications;
use Modules\Report\Models\Report;
use Modules\Report\Models\ReportStatus;

class CompletedState
{
    /**
     * @param Report $report
     *
     * @throws \Modules\Daapi\Exceptions\CanNotApplyStatusException
     * @throws \SM\SMException
     */
    public function after(Report $report): void
    {
        if ($report->isSchedulable()) {
            $report->applyInternalStatus(ReportStatus::DRAFT);
        }

        if ($report->isEmailNow()) {
            SendDetailedReportNotifications::dispatch($report, QueuePriority::default());
        }
    }
}

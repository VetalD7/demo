<?php

namespace Modules\Report\Actions\Traits;

use Modules\Report\Models\Report;
use Modules\Report\Models\ReportEmail;

trait ReportEmails
{
    /**
     * @param Report $report
     * @param array  $emails
     */
    protected function saveReportEmails(Report $report, array $emails): void
    {
        $emailsModelArray = array_map(function ($email) {
            return new ReportEmail(['email' => $email]);
        }, $emails);

        $report->emails()->delete();
        $report->emails()->saveMany($emailsModelArray);
    }
}

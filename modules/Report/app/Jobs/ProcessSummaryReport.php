<?php

namespace Modules\Report\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Daapi\Exceptions\CanNotApplyStatusException;
use Modules\Report\Actions\MetricsReport\GenerateSummaryReportAction;
use Modules\Report\Exceptions\ReportNotGeneratedException;
use Modules\Report\Models\Report;
use Modules\Report\Models\ReportStatus;
use SM\SMException;
use Throwable;

class ProcessSummaryReport extends Job implements ShouldQueue
{
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public int $timeout = 600;

    /**
     * @var Report
     */
    protected Report $report;

    /**
     * Create a new job instance.
     *
     * @param Report $report
     * @return void
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Execute the job.
     *
     * @return Report
     * @throws CanNotApplyStatusException
     * @throws SMException
     * @throws \App\Exceptions\BaseException
     */
    public function handle(): Report
    {
        try {
            $this->report->applyInternalStatus(ReportStatus::SUBMITTED);

            app(GenerateSummaryReportAction::class)->handle($this->report);

            return $this->report;
        } catch (Throwable $exception) {
            $this->report->applyInternalStatus(ReportStatus::FAILED);

            throw ReportNotGeneratedException::createFrom($exception);
        }
    }
}

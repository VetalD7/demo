<?php

namespace Modules\Report\Jobs;

use App\Jobs\Job;
use App\Models\QueuePriority;
use App\Notifications\Dispatchers\Dispatcher;
use Modules\Report\Models\Report;
use Modules\Report\Notifications\CampaignDetailedCompleted;
use Psr\Log\LoggerInterface;

class SendDetailedReportNotifications extends Job
{
    /**
     * @var Report
     */
    protected Report $report;

    /**
     * Create a new job instance.
     *
     * @param Report      $report
     * @param null|string $priority
     *
     * @return void
     */
    public function __construct(Report $report, ?string $priority = null)
    {
        $this->report = $report;
        $this->onQueue($priority ?? QueuePriority::low());
    }

    /**
     * Execute the job.
     *
     * @param LoggerInterface $log
     * @param Dispatcher      $dispatcher
     *
     * @return void
     * @throws \App\Exceptions\BaseException
     */
    public function handle(LoggerInterface $log, Dispatcher $dispatcher): void
    {
        $dispatcher->send($this->report, new CampaignDetailedCompleted($this->report));

        $log->info('Scheduled report email has been sent.', [
            'report_id'   => $this->report->id,
            'report_type' => $this->report->type->name,
        ]);

        DeleteReport::dispatch($this->report, QueuePriority::default());
    }
}

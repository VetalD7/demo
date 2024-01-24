<?php

namespace Modules\Report\Notifications;

use App\Helpers\DateFormatHelper;
use App\Models\QueuePriority;
use App\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailable;
use Modules\Notification\Models\EmailIcon;
use Modules\Notification\Models\NotificationCategory;
use Modules\Notification\Notifications\Contracts\UserLevelContract;
use Modules\Report\Helpers\GenerateReportFilename;
use Modules\Report\Models\Report;
use Modules\User\Models\User;

abstract class MetricsCompletedAbstract extends Notification implements UserLevelContract
{
    use GenerateReportFilename,
        Queueable;

    /**
     * @var Report
     */
    protected Report $report;

    /**
     * @param Report $report
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
        $this->onQueue(QueuePriority::low());
    }

    /**
     * @return string
     */
    abstract protected function getTitle(): string;

    /**
     * Build the mail representation of the notification.
     *
     * @param User $notifiable
     *
     * @return Mailable
     * @throws \App\Exceptions\BaseException
     */
    public function toMail(User $notifiable): Mailable
    {
        $mail = parent::toMail($notifiable);
        $mail->attachFromStorage($this->report->path);

        return $mail;
    }

    /**
     * Get notification payload.
     *
     * @param User $notifiable
     *
     * @return array
     */
    protected function getPayload(User $notifiable): array
    {
        return [
            'firstName'       => $notifiable->first_name,
            'reportName'      => $this->report->name,
            'generationDate'  => DateFormatHelper::formatted(Carbon::now()),
            'campaignListing' => route('campaigns.index'),
            'timezone'        => config('report.timezone'),
            'titleIcon'       => EmailIcon::TYPE_SCHEDULE,
            'isAdmin'         => $this->report->user->hasAdminRole(),
            'title'           => $this->getTitle(),
        ];
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return NotificationCategory::ID_REPORT;
    }
}

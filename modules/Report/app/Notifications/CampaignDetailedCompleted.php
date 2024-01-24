<?php

namespace Modules\Report\Notifications;

use App\Helpers\HtmlHelper;
use App\Notifications\Notification;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Arr;
use Modules\Notification\Enums\NotificationEnum;
use Modules\Report\Mail\DetailedCampaignScheduled;
use Modules\User\Models\User;

class CampaignDetailedCompleted extends MetricsCompletedAbstract
{
    /**
     * Class unique identifier
     */
    protected const CLASS_IDENTIFIER = NotificationEnum::reportCampaignDetailedCompleted;

    /**
     * @var string
     */
    protected $mailClass = DetailedCampaignScheduled::class;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return __('report::emails.detailed_scheduled.title');
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public static function getContent(array $data): string
    {
        /** @var HtmlHelper $html */
        $html = app(HtmlHelper::class);

        return __('report::notifications.advertiser.scheduled', [
            'report_name' => $html->createAnchorElement(
                Arr::get($data, 'reportListing', route('reports.index')),
                ['title' => Arr::get($data, 'reportName'),]
            ),
        ]);
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param User $notifiable
     *
     * @return Mailable
     */
    public function toMail(User $notifiable): Mailable
    {
        $mail = Notification::toMail($notifiable);
        $mail->attachFromStorage($this->report->path);

        return $mail;
    }

    /**
     * @return int|null
     */
    public function getOrganizationId(): ?int
    {
        return $this->report->user->organizations?->value('id');
    }
}

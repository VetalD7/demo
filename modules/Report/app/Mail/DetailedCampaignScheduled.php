<?php

namespace Modules\Report\Mail;

use App\Mail\Mail;

class DetailedCampaignScheduled extends Mail
{
    /**
     * Build the message.
     * @return $this
     */
    public function build(): self
    {
        return $this
            ->subject(__('report::emails.detailed_scheduled.subject', [
                'name' => $this->getPayloadValue('reportName'),
            ]))
            ->view('report::emails.detailed-scheduled')
            ->with($this->payload);
    }
}

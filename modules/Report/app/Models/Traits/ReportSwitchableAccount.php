<?php

namespace Modules\Report\Models\Traits;

use Modules\Organization\Models\Account;

/**
 * @property Account $account
 */
trait ReportSwitchableAccount
{
    /**
     * @return array
     */
    public function getOrganizationIds(): array
    {
        return $this->account ? [$this->account->organization_id] : [];
    }

    /**
     * @return array
     */
    public function getAccountIds(): array
    {
        return $this->account_id ? [$this->account_id] : [];
    }

    /**
     * @return string
     */
    public function getRouteAfterSwitchAccount(): string
    {
        if ($this->isSummary()) {
            return route('reports.campaign-summary.show', ['report' => $this->id]);
        }

        if ($this->isDetailed()) {
            return route('reports.campaign-detailed.show', ['report' => $this->id]);
        }

        return route('reports.index');
    }

    /**
     * @return string
     */
    public function getEntityTypeLabel(): string
    {
        return class_basename($this::class);
    }
}

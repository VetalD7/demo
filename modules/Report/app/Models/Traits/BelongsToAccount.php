<?php

namespace Modules\Report\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Organization\Models\Account;

/**
 * @property Account $account
 * @property Account $selectedAccount
 */
trait BelongsToAccount
{
    /**
     * @return BelongsTo
     */
    public function selectedAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'selected_account_id');
    }

    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}

<?php

namespace Modules\Report\Models;

use App\Traits\State;
use Carbon\Carbon;
use App\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Organization\Models\Contracts\SwitchableAccount;
use Modules\Report\Helpers\AWSReport;
use Modules\Report\Models\Traits\BelongsToStatus;
use Modules\Report\Models\Traits\BelongsToType;
use Modules\Report\Models\Traits\BelongToManyCampaigns;
use Modules\Report\Models\Traits\HasDeliverySetting;
use Modules\Report\Models\Traits\HasEmails;
use Modules\Report\Models\Traits\BelongsToManyTargetingTypes;
use Modules\Report\Models\Traits\HasManyNotifiableUsers;
use Modules\Report\Models\Traits\ReportSwitchableAccount;
use Modules\User\Models\Traits\BelongsToUser;
use Modules\Report\Models\Traits\BelongsToAccount;

/**
 * @property int         $id
 * @property string      $name
 * @property string      $path
 * @property Carbon      $date_start
 * @property Carbon      $date_end
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property int         $type_id
 * @property int         $user_id
 * @property Carbon      $generated_at
 * @property string|null $url
 * @property int         $status_id
 * @property boolean     $update_required
 * @property int         $selected_account_id
 * @property int         $account_id
 * @see Report::getUrlAttribute()
 * @method static \Modules\Report\Database\Factories\ReportFactory factory()
 */
class Report extends Model implements SwitchableAccount
{
    use BelongsToStatus,
        BelongsToType,
        BelongsToUser,
        HasEmails,
        State,
        AWSReport,
        HasFactory,
        BelongsToManyTargetingTypes,
        BelongToManyCampaigns,
        HasDeliverySetting,
        BelongsToAccount,
        ReportSwitchableAccount,
        HasManyNotifiableUsers;

    const CAMPAIGNS_LIST_ALL = 'all';
    const CAMPAIGNS_LIST_CUSTOM = 'custom';

    /**
     * @var array
     */
    protected $fillable = [
        'date_end',
        'date_start',
        'generated_at',
        'name',
        'path',
        'status_id',
        'type_id',
        'user_id',
        'created_by',
        'account_id',
        'update_required',
        'selected_account_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date_start',
        'date_end',
        'generated_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'update_required' => 'boolean',
    ];

    /**
     * Campaign state's flow.
     *
     * @var string
     */
    protected $flow = 'report';

    /**
     * Get url for the report file.
     *
     * @return null|string
     */
    public function getUrlAttribute(): ?string
    {
        return $this->path ? $this->generateDownloadUrl($this) : null;
    }
}

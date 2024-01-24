<?php

use Modules\Campaign\Models\CampaignStatus;
use Modules\Report\Models\ReportDeliveryFrequency;
use Modules\Report\Models\ReportType;
use Modules\Report\Models\ReportStatus;
use Modules\Report\Repositories\Criteria\CampaignsForDailyReportCriteria;
use Modules\Report\Repositories\Criteria\CampaignsForMonthlyReportCriteria;
use Modules\Report\Repositories\Criteria\CampaignsForWeeklyReportCriteria;

return [

    /*
    |--------------------------------------------------------------------------
    | Root directory for report files
    |--------------------------------------------------------------------------
    |
    | This option defines name of root directory where report files should be
    | stored on disk. This allows us to find all the reports in one place.
    |
    */

    'directory'                             => 'reports',

    /*
    |--------------------------------------------------------------------------
    | Timezone
    |--------------------------------------------------------------------------
    |
    | This option defines timezone the report is generated in.
    |
    */
    'timezone'                              => 'Eastern Time (ET)',

    /*
    |--------------------------------------------------------------------------
    | Delivery frequency id
    |--------------------------------------------------------------------------
    |
    | This option defines if execution day option is valid.
    |
    */
    'show_days_frequency_id'                => ReportDeliveryFrequency::ID_WEEKLY,

    /*
    |--------------------------------------------------------------------------
    | List of downloadable type ids
    |--------------------------------------------------------------------------
    |
    */
    'downloadable_type_ids' => ReportType::DOWNLOADABLE_TYPE_IDS,

    /*
    |--------------------------------------------------------------------------
    | List of pending status ids
    |--------------------------------------------------------------------------
    |
    | This option defines report statuses indicating that report is being processed
    |
    */
    'pending_status_ids' => ReportStatus::PENDING_STATUS,

    /*
    |--------------------------------------------------------------------------
    | Date Format
    |--------------------------------------------------------------------------
    |
    | These configs include format for dates.
    |
    */
    'format'                                => [
        /*
        |--------------------------------------------------------------------------
        | Datetime format
        |--------------------------------------------------------------------------
        |
        | Used to generate reports.
        | e.g. Aug 14 2019
        |
        */
        'date'         => 'M j Y',
        /*
        |--------------------------------------------------------------------------
        | Datetime format
        |--------------------------------------------------------------------------
        |
        | Used to generate reports.
        | e.g. 2019.Aug.14
        |
        */
        'date_metrics' => 'Y.M.j'
    ],

    /*
    |--------------------------------------------------------------------------
    | AWS S3 link expiration time in minutes.
    |--------------------------------------------------------------------------
    |
    */
    'url_expiration'                        => 60,

    /*
    |--------------------------------------------------------------------------
    | AWS S3 report file expiration time in hours before delete.
    |--------------------------------------------------------------------------
    |
    */
    'file_expiration_hours'                 => env('REPORT_FILE_EXPIRATION_HOURS', 24),

    /*
    |--------------------------------------------------------------------------
    | Should application delete previous scheduled report from storage before
    |  sending new scheduled report.
    |
    | For example we have daily reports:
    | - report was sent yesterday
    | - if this option is enabled application would delete previous report file
    | - if this option is disabled application generate new report file and
    |  old one would just stays in storage not linked with user in DB.
    |--------------------------------------------------------------------------
    |
    */
    'delete_previous_scheduled_report_file' => env('DELETE_PREVIOUS_SCHEDULED_REPORT_FILE', true),

    /*
    |--------------------------------------------------------------------------
    | Shift from midnight (in hours) to send scheduled reports.
    |--------------------------------------------------------------------------
    |
    */
    'midnight_shift_hours'                  => env('REPORTS_MIDNIGHT_SHIFT_HOURS', 3),

    /*
    |--------------------------------------------------------------------------
    | Stop deliver scheduled reports
    |--------------------------------------------------------------------------
    | Would stop:
    | * if no campaigns are active
    | * for daily - 2 days after completion, 1 time afterwards => 2 times total
    | * for weekly - 1 time after completion (1 week is default date and 1 week extra)
    | * for monthly - 1 time after completion (1 month that starts 2 months ago)
    | for example if today is any day of October we should take from 1 of August to 31 of August
    |
    | Would resume:
    | * once there will be live campaigns at the account
    |
    */
    'stop'                                     => [
        'retry' => [
            'times'     => [
                ReportDeliveryFrequency::ID_DAILY   => env('REPORTS_STOP_RETRY_TIMES_DAILY_IN_DAYS', 2),
                ReportDeliveryFrequency::ID_WEEKLY  => env('REPORTS_STOP_RETRY_TIMES_WEEKLY_IN_WEEKS', 2),
                ReportDeliveryFrequency::ID_MONTHLY => env('REPORTS_STOP_RETRY_TIMES_MONTHLY_IN_MONTH', 2),
            ],
            'criterion' => [
                ReportDeliveryFrequency::ID_DAILY   => CampaignsForDailyReportCriteria::class,
                ReportDeliveryFrequency::ID_WEEKLY  => CampaignsForWeeklyReportCriteria::class,
                ReportDeliveryFrequency::ID_MONTHLY => CampaignsForMonthlyReportCriteria::class,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Count days add to today for missing ads report.
    |--------------------------------------------------------------------------
    */
    'count_days_fom_missing_ads_report'        => 7,

    /*
    |--------------------------------------------------------------------------
    | Supported statuses of search campaigns in the report
    |--------------------------------------------------------------------------
    */
    'search_campaigns_statuses_allowed_report' => [
        CampaignStatus::ID_LIVE,
        CampaignStatus::ID_PAUSED,
        CampaignStatus::ID_COMPLETED,
        CampaignStatus::ID_CANCELED,
        CampaignStatus::ID_PROCESSING,
        CampaignStatus::ID_SUSPENDED
    ],
    /*
    |--------------------------------------------------------------------------
    | Targeting rule for generate reports from DHAAPI
    |
    | Users attempting to download reports for Gender & Age Group for campaigns
    | starting before the above, date see an error or do not receive a report
    |--------------------------------------------------------------------------
    */
    'targeting_rules' => [
        'age_group' => [
            /*
            |--------------------------------------------------------------------------
            | Disable targeting type for report.
            |--------------------------------------------------------------------------
            */
            'disabled'           => env('REPORT_TARGETING_RULES_AGE_DEMO_DISABLED', true),
            'political_disabled' => env('REPORT_TARGETING_RULES_AGE_DEMO_POLITICAL_DISABLED', true),
            'date_limitation'    => [
                /*
                |--------------------------------------------------------------------------
                | Enable start date restriction.
                |--------------------------------------------------------------------------
                */
                'enabled' => env('REPORT_TARGETING_RULES_AGE_DEMO_DATE_LIMITATION_ENABLED', true),

                /*
                |--------------------------------------------------------------------------
                | The date from which the rules are ignored. Default March 1, 2021
                |--------------------------------------------------------------------------
                */
                'date'    => env('REPORT_TARGETING_RULES_GUARANTEED_AGE_DEMO_DATE', null)
            ],
        ],
        'audience' => [
            'political_disabled' => env('REPORT_TARGETING_RULES_AUDIENCE_POLITICAL_DISABLED', true),
        ],
    ],
    //delay sending for schedule report
    'cron' => [
        'delay'  => env('REPORT_DELAY_SENDING', 30), // 30 sec
        'delay_size' => env('REPORT_SIZE_SENDING', 5), // run delay after 5 request
    ],
    //limit data for admin reports
    'job' => [
        'records_limit' => env('REPORT_JOB_RECORDS_LIMIT', 1000),
    ],
];

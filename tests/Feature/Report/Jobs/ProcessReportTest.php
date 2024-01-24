<?php

namespace Tests\Feature\Report\Jobs;

use Modules\Report\Jobs\ProcessMissingAdsReport;
use Modules\Report\Jobs\ProcessPendingCampaignsReport;
use Modules\Report\Jobs\ProcessReport;
use Modules\Report\Models\Report;
use Modules\Report\Models\ReportDeliveryType;
use Modules\Report\Models\ReportStatus;
use Modules\Report\Models\ReportType;
use Modules\Report\Models\Traits\CreateDeliverySetting;
use Tests\TestCase;
use Tests\Traits\Report\MockAdminProcessReport;

class ProcessReportTest extends TestCase
{
    use CreateDeliverySetting,
        MockAdminProcessReport;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockAdminProcessReport();
    }

    /**
     * @dataProvider noLiveCampaignsDataProvider
     *
     * @param array $reportData
     *
     * @return void
     * @throws \App\Exceptions\BaseException
     * @throws \Modules\Daapi\Exceptions\CanNotApplyStatusException
     * @throws \SM\SMException
     */
    public function testNoLiveCampaignsReport(array $reportData): void
    {
        /** @var Report $report */
        $report = Report::factory()
            ->override([
                'status_id' => ReportStatus::ID_DRAFT,
                'type_id'   => $reportData['reportType'],

            ])
            ->createQuietly();

        $this->createTestSettingsReport([
            'report_id' => $report->id,
            'type_id'   => ReportDeliveryType::ID_DOWNLOAD
        ]);

        $process = app($reportData['reportAction'], [
                'report' => $report,
                'offset' => 0,
                'path'   => '/']);

        $process->handle();
    }

    /**
     * @return \Generator
     */
    public function noLiveCampaignsDataProvider(): \Generator
    {
        yield [
            [
                'reportType'   => ReportType::ID_PENDING_CAMPAIGNS,
                'reportAction' => ProcessPendingCampaignsReport::class
            ],
            [
                'reportType'   => ReportType::ID_DOWNLOAD,
                'reportAction' => ProcessReport::class
            ],
            [
                'reportType'   => ReportType::ID_MISSING_ADS,
                'reportAction' => ProcessMissingAdsReport::class
            ],
        ];
    }
}

<?php

/**
 * Statistic routes
 */
Route::group([
    'middleware' => 'auth:advertiser,admin,ghost',
], function () {
    Route::group(['prefix' => '/{report}'], function () {
        // Destroy
        Route::delete('/', 'DestroyReportController')
            ->name('destroy')
            ->middleware('can:report.delete,report');
    });

    Route::group(['prefix' => 'campaign-detailed', 'as' => 'campaign-detailed.'], function () {
        Route::post('/', 'MetricsReport\StoreCampaignDetailedReportController')
            ->name('store')
            ->middleware('can:report.createCampaignDetailed');

        Route::get('/{report}', 'MetricsReport\ShowCampaignDetailedReportController')
            ->name('show')
            ->middleware('can:report.showCampaignDetailed,report');

        Route::patch('/{report}', 'MetricsReport\UpdateCampaignDetailedReportController')
            ->name('update')
            ->middleware('can:report.updateCampaignDetailed,report');
    });

    Route::group(['prefix' => 'campaign-summary', 'as' => 'campaign-summary.'], function () {
        Route::post('/', 'MetricsReport\StoreCampaignSummaryReportController')
            ->name('store')
            ->middleware('can:report.createCampaignSummary');

        Route::get('/{report}', 'MetricsReport\ShowCampaignSummaryReportController')
            ->name('show')
            ->middleware('can:report.showCampaignSummary,report');

        Route::patch('/{report}', 'MetricsReport\UpdateCampaignSummaryReportController')
            ->name('update')
            ->middleware('can:report.updateCampaignSummary,report');
    });
});

<?php

Route::middleware(['auth:advertiser,admin,ghost'])->group(function () {
    Route::get('/', 'IndexController')->name('index')->middleware('can:report.list');

    Route::get('/create', 'IndexController')
        ->name('create')
        ->middleware('can:report.create');

    Route::group(['prefix' => 'campaign-detailed', 'as' => 'campaign-detailed.'], function () {
        Route::get('/{report}', 'IndexController')
            ->name('show')
            ->middleware('can:report.list');
    });

    Route::group(['prefix' => 'campaign-summary', 'as' => 'campaign-summary.'], function () {
        Route::get('/{report}', 'IndexController')
            ->name('show')
            ->middleware('can:report.list');
    });
});

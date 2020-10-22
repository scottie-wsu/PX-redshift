<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => [
        config('backpack.base.web_middleware', 'web'),
        config('backpack.base.middleware_key', 'admin'),
    ],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    Route::crud('calculations', 'CalculationsCrudController');
    Route::crud('methods', 'MethodsCrudController');
	Route::get('methods/{id}/softDelete', 'MethodsCrudController@softDelete');
	Route::crud('users', 'UsersCrudController');
    Route::get('users/{id}/reset', 'UsersCrudController@reset');

    Route::get('plotting', 'PlotController@Index')->name('plots');
    Route::post('plotting', 'PlotController@FetchPlot')->name('fetchPlot');

	Route::get('analytics', 'AnalyticsController@custom')->name('analytics');
	Route::get('analytics1', 'AnalyticsController@ajaxCounts1')->name('ajaxcounts1');
    Route::get('analytics2', 'AnalyticsController@ajaxCounts2')->name('ajaxcounts2');
    Route::get('analytics3', 'AnalyticsController@ajaxCounts3')->name('ajaxcounts3');
    Route::get('analytics4', 'AnalyticsController@ajaxCounts4')->name('ajaxcounts4');
    Route::get('analytics5', 'AnalyticsController@ajaxCounts5')->name('ajaxcounts5');
    Route::get('analytics6', 'AnalyticsController@ajaxCounts6')->name('ajaxcounts6');
	Route::get('analytics7', 'AnalyticsController@ajaxCounts7')->name('ajaxcounts7');
	Route::get('analytics8', 'AnalyticsController@ajaxCounts8')->name('ajaxcounts8');

	Route::get('charts/institutions', 'Charts\InstitutionsChartController@response')->name('charts.institutions.index');
    Route::get('charts/jobs', 'Charts\JobsChartController@response')->name('charts.jobs.index');
    Route::get('charts/method-use', 'Charts\MethodUseChartController@response')->name('charts.method-use.index');
    Route::get('charts/weekly-method-use-chart', 'Charts\WeeklyMethodUseChartChartController@response')->name('charts.weekly-method-use-chart.index');
    Route::get('charts/redshifts', 'Charts\RedshiftsChartController@response')->name('charts.redshifts.index');
}); // this should be the absolute last line of this file

<?php


Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::get('/', function () {
    return "hi in one page";
});

Route::get('/dashboard', function () {
    return "Not allow";
})-> name ('not.allow');

Route::get('/redirect/{service}', 'SocialController@redirect ');

Route::get('/callback/{service}', 'SocialController@callback');

Route::get('fillable', 'CrudController@getOffers');

Route::group(['prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {
    Route::group(['prefix' => 'offers'], function () {
        Route::get('create', 'CrudController@create');
        Route::post('store', 'CrudController@store')->name('offers.store');

        Route::get('edit/{offer_id}', 'CrudController@editOffer');
        Route::post('update/{offer_id}', 'CrudController@updateOffer')->name('offers.update');
        Route::get('delete/{offer_id}', 'CrudController@delete')->name('offers.delete');
        Route::get('all', 'CrudController@getAllOffers')->name('offers.all');
    });

    Route::get('youtube', 'CrudController@getVideo')-> middleware ('auth');
});

###################### Begin Ajax routes ###################
Route::group(['prefix' => 'ajax-offers'], function () {
    Route::get('create', 'OfferController@create');
    Route::post('store', 'OfferController@store')->name('ajax.offers.store');
    Route::get('all', 'OfferController@all')->name('ajax.offers.all');
    Route::post('delete', 'OfferController@delete')->name('ajax.offers.delete');
    Route::get('edit/{offer_id}', 'OfferController@edit')->name('ajax.offers.edit');
    Route::post('update', 'OfferController@Update')->name('ajax.offers.update');
});
###################### End Ajax routes #####################


##################### Begin Authentication && Guards ##############
Route::group(['middleware' => 'CheckAge', 'namespace' => 'Auth'], function () {
    Route::get('adults', 'CustomAuthController@adualt') -> name('adult');
});

Route::get('site', 'Auth\CustomAuthController@site')-> middleware ('auth:web') ->name('site');
Route::get('admin', 'Auth\CustomAuthController@admin') -> middleware ('auth:admin')-> name('admin');
Route::get('admin/login', 'Auth\CustomAuthController@adminlogin') -> name('admin.login');
Route::post('admin/login', 'Auth\CustomAuthController@checkAdminlogin') -> name('save.admin.login');
##################### End Authentication && Guards ##############

######### Begin relations routes #########
Route::get('has-one','Relation\RelationsController@hasOneRelation');
Route::get('has-one-reserve','Relation\RelationsController@hasOneRelationReserve');
Route::get('get-user-has-phone','Relation\RelationsController@getUserHasPhone');
Route::get('get-user-has-phone-with-condition','Relation\RelationsController@getUserWhereHasPhoneWithCondition');
Route::get('get-user-not-has-phone','Relation\RelationsController@getUserNotHasPhone');
######### End relations routes #########

################## Begin one To many Relationship #####################
Route::get('hospital-has-many','Relation\RelationsController@getHospitalDoctors');
Route::get('hospitals','Relation\RelationsController@hospitals') -> name('hospital.all');
Route::get('doctors/{hospital_id}','Relation\RelationsController@doctors')-> name('hospital.doctors');
Route::get('hospitals/{hospital_id}','Relation\RelationsController@deleteHospital') -> name('hospital.delete');
Route::get('hospitals_has_doctors','Relation\RelationsController@hospitalsHasDoctor');
Route::get('hospitals_has_doctors_male','Relation\RelationsController@hospitalsHasOnlyMaleDoctors');
Route::get('hospitals_not_has_doctors','Relation\RelationsController@hospitals_not_has_doctors');
################## End one To many Relationship #####################


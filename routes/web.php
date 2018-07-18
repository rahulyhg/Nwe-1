<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Hash;

Route::group(['middleware' => ['web','cors'],'prefix'=>'api/v1'], function () {

    Route::get('/cities', 'Auth\AuthController@getCities');

    Route::get('/work_type', 'Auth\AuthController@workType');
    Route::get('/work_form', 'Auth\AuthController@workForm');

    // Route::post('validate-employer', 'Auth\AuthController@postValidateEmployer');
    // Route::get('validate-employer', function(){
    //     abort(404);
    // });

    Route::post('auth/register', 'Auth\AuthController@postRegister');
    Route::get('auth/register', function(){
        abort(404);
    });

    Route::post('auth/forgot-password', 'Auth\AuthController@postForgotPassword');
    Route::get('auth/forgot-password', function(){
        abort(404);
    });

    // Route::get('validate-key-forgot-password', 'Auth\AuthController@validateKeyForgotPasswword');
    // Route::post('validate-key-forgot-password', function(){
    //     abort(404);
    // });
    
    Route::post('auth/login', 'Auth\AuthController@postLogin');
    Route::get('auth/login', function(){
        abort(404);
    });

    Route::get('/auth/provider/callback', 'Auth\AuthController@postLoginProvider');
    // Route::post('login-provider', 'Auth\AuthController@postLoginProvider');
    // Route::get('login-provider', function(){
    //     abort(404);
    // });

    // Route::get('/', function(){
    //     return Hash::make('Pass4now2018!');
    // });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('protected', function(){});

        Route::post('update-profile', 'Auth\AuthController@updateProfile');
        Route::get('update-profile', 'Auth\AuthController@getProfile');

        Route::post('update-info', 'Auth\AuthController@updateInfo');

        // Route::post('user/cv-status', 'CvStatusController@postCvStatus');
        // Route::get('user/cv-status', 'CvStatusController@getCvStatus');
        //Route::get('user', 'UserController@getUser');

        Route::get('/tabs/{id?}', 'CvTabController@getCvTabs');

        Route::post('/jobs', 'ApiJobsController@getJobs');
        Route::get('/job/{id}', 'ApiJobsController@getJobById');
        Route::post('/job/update', 'ApiJobsController@postUpdateJob');


        Route::get('/cvs/{id?}', 'ApiCvsController@getCvs');
        Route::post('/cv/create', 'ApiCvsController@createCV');
        Route::post('/cv/update-status/{id}', 'ApiCvsController@updateStatus');
        Route::post('/cv/active/{id}', 'ApiCvsController@activeStatus');
        Route::post('/cv/delete/{id}', 'ApiCvsController@deleteCV');
        // Route::get('user/cv-rows', 'CvRowController@getCvRowsByCvTabId');
        Route::post('/notification/get', ['as' => 'api.notification.get', 'uses' => 'NotificationController@apiGet']);
        Route::post('/notification/read', ['as' => 'api.notification.read', 'uses' => 'NotificationController@apiRead']);
        //Route::post('/tab-row-update', 'CvRowController@postUpdateCvRow');
        //Route::post('user/cv-row-delete', 'CvRowController@postDeleteCvRow');
    });
});

Route::group(['middleware' => ['web']], function () {
    // Route::get('/', 'Auth\LoginAdminController@getLoginAdmin');
     Route::get('/login', 'Auth\LoginAdminController@getLoginAdmin');
     Route::post('/login', 'Auth\LoginAdminController@postLoginAdmin');

     Route::get('/forgot-password', 'Auth\LoginAdminController@getForgotPassword');
     Route::post('/forgot-password', 'Auth\LoginAdminController@postForgotPassword');

     Route::get('/user/login', 'Auth\LoginUserController@getLogin');
     Route::post('/user/login', 'Auth\LoginUserController@postLogin');
    Route::put('/user/login', 'Auth\LoginUserController@putLogin');
     Route::post('/otp-login', 'Auth\LoginUserController@otpLogin');

    Route::get('employer/register', ['as' => 'client.employer.register', 'uses' => 'Auth\RegisterController@getRegisterEmployer']);
    Route::post('employer/register', ['as' => 'client.employer.register', 'uses' => 'Auth\RegisterController@registerEmployer']);
    Route::get('user/register', ['as' => 'client.user.register', 'uses' => 'Auth\RegisterController@getRegisterUser']);
    Route::post('user/register', ['as' => 'client.user.register', 'uses' => 'Auth\RegisterController@registerUser']);

     
     Route::get('/', 'Client\HomeController@home');
     Route::get('/xung-quanh', 'Client\HomeController@search');
     Route::get('cong-viec/{slug}', 'Client\HomeController@getJobBySlug');
     Route::get('doanh-nghiep/{slug}', 'Client\HomeController@getEmployerBySlug');
     Route::get('ho-so/{slug}', 'Client\HomeController@getUserBySlug');
     Route::get('ajax/job/search', 'Client\HomeController@getJobsSearch');
     Route::get('ajax/job/filter', 'Client\HomeController@getJobsFilter');
     Route::get('ajax/job/{id}', 'Client\HomeController@getJobById');
     

     Route::get('ajax/jobs', 'Client\HomeController@getJobs');
     
     Route::put('ajax/reg-cv', 'Client\HomeController@regCV');

    Route::post('/notification/get', ['as' => 'notification.get', 'uses' => 'NotificationController@get']);
    Route::post('/notification/read', ['as' => 'notification.read', 'uses' => 'NotificationController@read']);

    Route::get('/auth/{provider}', 'Auth\LoginUserController@redirectToProvider');
    Route::get('/auth/{provider}/employer', 'Auth\LoginUserController@redirectToProviderEmployer');
    Route::get('/auth/{provider}/callback', 'Auth\LoginUserController@handleProviderCallback');

    Route::group(['middleware' => ['user']], function () {
        Route::get('user/logout', 'Auth\LoginUserController@getLogout');

        Route::get('user/profile', 'Auth\LoginUserController@getProfile');
        Route::post('user/profile', 'Client\HomeController@updateProfile');

        Route::get('user/info-cv', 'Client\HomeController@getInfoCv');
        Route::post('user/info-cv', 'Client\HomeController@updateCV');


        Route::get('user/change-password', 'Auth\LoginUserController@getChangePassword');
        Route::post('user/change-password', 'Auth\LoginUserController@postChangePassword');
        
        Route::put('user/ajax/update-cv', 'Client\HomeController@updateCV');
        Route::put('user/row/delete/{id}', 'Client\HomeController@deleteRow');

        Route::put('ajax/post-cv', 'Client\HomeController@putCV');

        Route::get('user/cvs', 'Client\CvController@getCvsByUser');
        Route::put('/cv/active-status/{id}', 'Client\CvController@activeStatus');
        Route::put('user/cv/delete/{id}', 'Client\CvController@deleteCV');

        Route::put('user/review-job', 'Client\JobController@putUserReview');
    });

     Route::group(['middleware' => ['employer']], function () {

        Route::get('employer/logout', 'Auth\LoginUserController@getLogout');
        Route::get('employer/profile', 'Auth\LoginUserController@getProfile');
        Route::post('employer/profile', ['as' => 'client.employer.profile', 'uses' => 'Client\EmployerController@postEdit']);

        Route::get('employer/info-company', ['as' => 'client.employer.info_company', 'uses' => 'Client\EmployerController@getInfoCompany']);
        Route::post('employer/info-company', ['as' => 'client.employer.info_company', 'uses' => 'Client\EmployerController@postInfoCompany']);

        Route::get('employer/change-password', 'Auth\LoginUserController@getChangePassword');
        Route::post('employer/change-password', 'Auth\LoginUserController@postChangePassword');
        // Route::put('employer/ajax/update-cv', 'Client\HomeController@updateCV');
        Route::put('employer/row/delete/{id}', 'Client\HomeController@deleteRow');

        Route::post('employer/gallery-update/{id}', 'Admin\EmployersController@updateGallery');
        Route::post('employer/gallery-delete/{id}', 'Admin\EmployersController@deleteGallery');

        Route::get('employer/jobs', 'Client\JobController@getJobs');
        Route::get('employer/job/create', 'Client\JobController@getCreate');
        Route::post('employer/job/create', 'Client\JobController@postCreate')->name('empcreate');
        Route::get('employer/job/edit/{id}', 'Client\JobController@getEdit')->name('empedit');
        Route::post('employer/job/edit/{id}', 'Client\JobController@postEdit');
        Route::get('employer/job/duplicate/{id}', 'Client\JobController@getDuplicate');
        Route::put('employer/job/delete/{id}', 'Client\JobController@deleteJob');

        Route::get('ajax/job-cvs/{id}', 'Client\CvController@getCvsByJob');
        Route::put('/cv/update-status/{id}', 'Client\CvController@updateStatus');
        Route::put('employer/cv/active-status/{id}', 'Client\CvController@activeStatus');
        Route::put('employer/cv/delete/{id}', 'Client\CvController@deleteCV');
        
        Route::put('employer/review-job', 'Client\JobController@putReview');

    });

     Route::group(['middleware' => ['admin','role']], function () {

        Route::get('/logout', 'Auth\LoginAdminController@getLogoutAdmin');

        Route::get('dashboard', 'Admin\DashboardController@dashboard');

        Route::group(['middleware' => ['role']], function () {
            Route::get('tabs', 'Admin\TabsController@tabs');
            Route::get('ajax/tabs', 'Admin\TabsController@ajaxTabs');
            Route::get('tab/create', 'Admin\TabsController@getCreate');
            Route::post('tab/create', 'Admin\TabsController@postCreate');
            Route::get('tab/edit/{id}', 'Admin\TabsController@getEdit');
            Route::post('tab/edit/{id}', 'Admin\TabsController@postEdit');
            Route::post('tab/validate/{param}/{id?}', 'Admin\TabsController@postValidate');

            Route::get('utilities', 'Admin\UtilitiesController@utilities');
            Route::get('ajax/utilities', 'Admin\UtilitiesController@ajaxUtilities');
            Route::get('utilities/create', 'Admin\UtilitiesController@getCreate');
            Route::post('utilities/create', 'Admin\UtilitiesController@postCreate');
            Route::get('utilities/edit/{id}', 'Admin\UtilitiesController@getEdit');
            Route::post('utilities/edit/{id}', 'Admin\UtilitiesController@postEdit');
            Route::post('utilities/delete/{id}', 'Admin\UtilitiesController@postDelete');

            Route::get('tab/{id}/options', 'Admin\TabOptionsController@options');
            Route::get('ajax/tab/{id}/options', 'Admin\TabOptionsController@ajaxOptions');
            Route::get('tab/{id}/option/create', 'Admin\TabOptionsController@getCreate');
            Route::post('tab/{id}/option/create', 'Admin\TabOptionsController@postCreate');
            Route::get('tab/{tabId}/option/edit/{id}', 'Admin\TabOptionsController@getEdit');
            Route::post('tab/{tabId}/option/edit/{id}', 'Admin\TabOptionsController@postEdit');
            Route::post('option/delete/{id}', 'Admin\TabOptionsController@postDelete');
            Route::post('option/validate/{param}/{id?}', 'Admin\TabOptionsController@postValidate');

            Route::get('employers', 'Admin\EmployersController@employers');
            Route::get('ajax/employers', 'Admin\EmployersController@ajaxEmployers');
             Route::get('employer/create', 'Admin\EmployersController@getCreate');
             Route::post('employer/create', 'Admin\EmployersController@postCreate');
             
             Route::post('employer/delete/{id}', 'Admin\EmployersController@postDelete');

             Route::get('users', 'Admin\UsersController@users');
            Route::get('ajax/users', 'Admin\UsersController@ajaxUsers');
        });
        

         Route::get('jobs', 'Admin\JobsController@jobs');
         Route::get('ajax/jobs', 'Admin\JobsController@ajaxJobs');
         Route::get('job/create', 'Admin\JobsController@getCreate');
         Route::post('job/create', 'Admin\JobsController@postCreate');
         Route::get('job/edit/{id}', 'Admin\JobsController@getEdit');
         Route::get('job/duplicate/{id}', 'Admin\JobsController@getDuplicate');
         Route::post('job/edit/{id}', 'Admin\JobsController@postEdit');
         Route::post('job/delete/{id}', 'Admin\JobsController@postDelete');
         Route::get('job/{id}/users', 'Admin\JobsController@getUsers');
    //     Route::get('galleries/{id}', 'UserController@getGalleries');

         Route::post('row/delete/{id}', 'Admin\RowsController@deleteRow');
         

         Route::get('employer/edit/{id}', 'Admin\EmployersController@getEdit');
         Route::post('employer/edit/{id}', 'Admin\EmployersController@postEdit');
         Route::post('employer/validate/{param}/{id?}', 'Admin\EmployersController@postValidate');
         Route::post('employer/gallery/update/{id}', 'Admin\EmployersController@updateGallery');
         Route::post('employer/gallery/delete/{id}', 'Admin\EmployersController@deleteGallery');

         

         

         

         
     });
});
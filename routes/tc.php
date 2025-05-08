<?php
use Illuminate\Support\Facades\Route;
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
/**
 * Model binding into route
 */
Route::model('users', 'App\User');
/**
 */
    /**
     * Auth
     */
    Route::get('/', array('as' => 'home', 'uses' => 'Setting\UserController@index'));
    Route::get('login', array('as' => 'login', 'uses' => 'Setting\UserController@index'));
    Route::get('login/microsoft', array('as' => 'login.microsoft', 'uses' => 'Setting\UserController@login_microsoft'));
    Route::get('login/microsoft/callback', array('as' => 'login.microsoft.post', 'uses' => 'Setting\UserController@login_callback'));
    Route::post('login', array('as' => 'login.post', 'uses' => 'Setting\UserController@loginAdmin'));
    Route::get('logout', array('as' => 'logout', 'uses' => 'Setting\UserController@getLogout'));
    Route::get('/geo/update', array('as' => 'update.geolocation', 'uses' => 'WsController@update_session'));

    Route::group(array('prefix' => 'dashboard', 'middleware' => 'App\Http\Middleware\SentinelGuest'), function () {
        # Error pages should be shown without requiring login
        Route::get('404', function () {
            return View('404');
        });
        Route::get('500', function () {
            return View::make('500');
        });

        Route::get('/', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));

        Route::get('/profile', array('as' => 'user.profile', 'uses' => 'Setting\UserController@profile'));
        Route::post('/update', array('as' => 'user.profile.update', 'uses' => 'Setting\UserController@profile_update'));
        Route::post('/plocation', array('as' => 'user.profile.plocation', 'uses' => 'Setting\UserController@set_plocation'));
        Route::get('/download', array('as' => 'file.download', 'uses' => 'WsController@get_download'));

        Route::group(array('middleware' => 'App\Http\Middleware\SentinelUser'), function () {
            /**
             * Daily Routes
             */
            Route::post('/images/upload', array('as' => 'images.upload', 'uses' => 'WsController@images_upload'));
            Route::post('/settings/files/upload', array('as' => 'settings.files.upload', 'uses' => 'WsController@files_upload'));
            Route::post('/settings/images/upload', array('as' => 'images.settings.upload', 'uses' => 'WsController@images_settings_upload'));

            /**
             * QCF Incident Report
             */
            Route::get('/incident/report', array('as' => 'incident.reporting', 'uses' => 'Main\QcfIncidentReportingController@incident_index'));
            Route::get('/incident/report/add/{id}', array('as' => 'incident.reporting.add', 'uses' => 'Main\QcfIncidentReportingController@incident_add'));
            Route::get('/incident/report/detail/{id}', array('as' => 'incident.reporting.detail', 'uses' => 'Main\QcfIncidentReportingController@incident_detail'));
            Route::get('/incident/report/print/{id}', array('as' => 'incident.reporting.print', 'uses' => 'Main\QcfIncidentReportingController@incident_print'));
            Route::get('/incident/report/add_forms', array('as' => 'incident.reporting.add_forms', 'uses' => 'Main\QcfIncidentReportingController@incident_add_forms'));
            Route::get('/incident/report/check/edit/{id}', array('as' => 'incident.reporting.check.edit', 'uses' => 'Main\QcfIncidentReportingController@incident_check_edit'));
            Route::get('/incident/report/comments/{id}', array('as' => 'incident.reporting.comments', 'uses' => 'Main\QcfIncidentReportingController@incident_comments'));
            Route::post('/incident/report/comments_save', array('as' => 'incident.reporting.comments.save', 'uses' => 'Main\QcfIncidentReportingController@incident_comments_save'));

            Route::post('/incident/report/save', array('as' => 'incident.reporting.save', 'uses' => 'Main\QcfIncidentReportingController@incident_save'));
            Route::post('/incident/report/delete', array('as' => 'incident.reporting.delete', 'uses' => 'Main\QcfIncidentReportingController@incident_delete'));
            Route::post('/incident/report/check', array('as' => 'incident.reporting.check', 'uses' => 'Main\QcfIncidentReportingController@incident_check'));
            Route::post('/incident/report/upload', array('as' => 'incident.reporting.upload', 'uses' => 'Main\QcfIncidentReportingController@incident_upload'));

            /**
             *  Courses
             */
            Route::get('/course', array('as' => 'course', 'uses' => 'Main\CourseController@index'));
            Route::get('/course/topic', array('as' => 'course.topic', 'uses' => 'Main\CourseController@topic'));
            Route::get('/course/topic/detail', array('as' => 'course.topic.detail', 'uses' => 'Main\CourseController@detail'));
            Route::get('/quiz', array('as' => 'course.quiz', 'uses' => 'Main\QuizController@index'));
            Route::get('/quiz/list', array('as' => 'course.quiz.list', 'uses' => 'Main\QuizController@quiz'));
            Route::post('/quiz/timing/save', array('as' => 'course.quiz.timing.save', 'uses' => 'Main\QuizController@timing'));
            Route::post('/quiz/submit', array('as' => 'course.quiz.submit', 'uses' => 'Main\QuizController@submit'));

            Route::get('/cert', array('as' => 'cert', 'uses' => 'Main\CourseController@index'));



            Route::group(array('middleware' => 'App\Http\Middleware\SentinelAdmin'), function () {

                /**
                 * settings route
                 */

                Route::get('/settings', array('as' => 'settings', 'uses' => 'Setting\SettingController@index'));
                Route::get('/settings/user', array('as' => 'settings.user', 'uses' => 'Setting\UserController@user_list'));
                Route::get('/settings/user/add', array('as' => 'settings.user.add', 'uses' => 'Setting\UserController@create'));
                Route::post('/settings/user/save', array('as' => 'settings.user.save', 'uses' => 'Setting\UserController@store'));
                Route::get('/settings/user/edit/{id}', array('as' => 'settings.user.edit', 'uses' => 'Setting\UserController@edit'));
                Route::post('/settings/user/update', array('as' => 'settings.user.update', 'uses' => 'Setting\UserController@update'));
                Route::post('/settings/user/reset', array('as' => 'settings.user.reset', 'uses' => 'Setting\UserController@format'));
                Route::post('/settings/user/delete', array('as' => 'settings.user.delete', 'uses' => 'Setting\UserController@delete'));


                Route::get('/settings/location', array('as' => 'settings.location', 'uses' => 'Setting\SettingController@location_index'));
                Route::get('/settings/location/add', array('as' => 'settings.location.add', 'uses' => 'Setting\SettingController@location_add'));
                Route::get('/settings/location/edit/{id}', array('as' => 'settings.location.edit', 'uses' => 'Setting\SettingController@location_edit'));
                Route::post('/settings/location/save', array('as' => 'settings.location.save', 'uses' => 'Setting\SettingController@location_save'));
                Route::post('/settings/location/update', array('as' => 'settings.location.update', 'uses' => 'Setting\SettingController@location_update'));
                Route::post('/settings/location/delete', array('as' => 'settings.location.delete', 'uses' => 'Setting\SettingController@location_delete'));


                /**
                 * Setting Course
                 */
                Route::get('/settings/course', array('as' => 'settings.course', 'uses' => 'Setting\SettingCourseController@index'));
                Route::get('/settings/course/{id}/edit', array('as' => 'settings.course.edit', 'uses' => 'Setting\SettingCourseController@edit'));
                Route::post('/settings/course/save', array('as' => 'settings.course.save', 'uses' => 'Setting\SettingCourseController@save'));
                Route::post('/settings/course/delete', array('as' => 'settings.course.delete', 'uses' => 'Setting\SettingCourseController@delete'));

                Route::get('/settings/course/detail', array('as' => 'settings.course.detail', 'uses' => 'Setting\SettingCourseController@course_detail'));
                Route::get('/settings/course/detail/topic/{id}/edit', array('as' => 'settings.course.topic.edit', 'uses' => 'Setting\SettingCourseController@topic_edit'));
                Route::post('/settings/course/detail/topic/save', array('as' => 'settings.course.topic.save', 'uses' => 'Setting\SettingCourseController@topic_save'));
                Route::post('/settings/course/detail/topic/delete', array('as' => 'settings.course.topic.delete', 'uses' => 'Setting\SettingCourseController@topic_delete'));

                Route::get('/settings/course/detail/quiz/{id}/edit', array('as' => 'settings.course.quiz.edit', 'uses' => 'Setting\SettingCourseController@quiz_edit'));
                Route::post('/settings/course/detail/quiz/save', array('as' => 'settings.course.quiz.save', 'uses' => 'Setting\SettingCourseController@quiz_save'));
                Route::post('/settings/course/detail/quiz/delete', array('as' => 'settings.course.quiz.delete', 'uses' => 'Setting\SettingCourseController@quiz_delete'));

            });
        });

    });



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

        Route::get('/', array('as' => 'dashboard', 'uses' => 'QcfDashboardController@index'));
        Route::get('/inspects', array('as' => 'dashboard.inspects', 'uses' => 'QcfDashboardController@inspects'));

        Route::get('/profile', array('as' => 'user.profile', 'uses' => 'Setting\UserController@profile'));
        Route::post('/update', array('as' => 'user.profile.update', 'uses' => 'Setting\UserController@profile_update'));
        Route::post('/plocation', array('as' => 'user.profile.plocation', 'uses' => 'Setting\UserController@set_plocation'));

        Route::group(array('middleware' => 'App\Http\Middleware\SentinelUser'), function () {
            /**
             * Daily Routes
             */

            Route::get('/daily/fuel', array('as' => 'daily.fuel', 'uses' => 'Main\DailyController@fuel_index'));
            Route::get('/daily/fuel/change', array('as' => 'daily.fuel.change', 'uses' => 'Main\DailyController@change_add'));
            Route::get('/daily/fuel/add', array('as' => 'daily.fuel.add', 'uses' => 'Main\DailyController@fuel_add'));
            Route::get('/daily/fuel/edit/{id}', array('as' => 'daily.fuel.edit', 'uses' => 'Main\DailyController@fuel_edit'));
            Route::post('/daily/fuel/save', array('as' => 'daily.fuel.save', 'uses' => 'Main\DailyController@fuel_save'));
            Route::post('/daily/fuel/update', array('as' => 'daily.fuel.update', 'uses' => 'Main\DailyController@fuel_update'));
            Route::post('/daily/fuel/delete', array('as' => 'daily.fuel.delete', 'uses' => 'Main\DailyController@fuel_delete'));


            Route::get('/tankfarm/daily/filter/detail/{id}', array('as' => 'tf1.daily.filter.detail', 'uses' => 'Main\Tf1DailyController@filter_detail'));

            Route::get('/tankfarm/filter/detail', array('as' => 'daily.filter.detail', 'uses' => 'Main\Tf1DailyController@filter_report_detail'));

            Route::get('/calibration/download', array('as' => 'calibration.download', 'uses' => 'Main\CalibrationController@download'));

            Route::get('/audit', array('as' => 'audit', 'uses' => 'Main\AuditController@audit_index'));
            Route::get('/audit/add', array('as' => 'audit.add', 'uses' => 'Main\AuditController@audit_add'));
            Route::get('/audit/change', array('as' => 'audit.change', 'uses' => 'Main\AuditController@audit_change'));
            Route::get('/audit/edit/{id}', array('as' => 'audit.edit', 'uses' => 'Main\AuditController@audit_edit'));

            Route::post('/audit/save', array('as' => 'audit.save', 'uses' => 'Main\AuditController@audit_save'));
            Route::post('/audit/update', array('as' => 'audit.update', 'uses' => 'Main\AuditController@audit_update'));
            Route::post('/audit/delete', array('as' => 'audit.delete', 'uses' => 'Main\AuditController@audit_delete'));
            Route::post('/audit/upload', array('as' => 'audit.upload', 'uses' => 'Main\AuditController@audit_upload'));
            Route::get('/audit/download', array('as' => 'audit.download', 'uses' => 'Main\AuditController@getDownload'));
            Route::get('/audit/detail/{id}', array('as' => 'audit.detail', 'uses' => 'Main\AuditController@audit_detail'));
            Route::get('/audit/print/{id}', array('as' => 'audit.print', 'uses' => 'Main\AuditController@audit_print'));


            Route::post('/images/upload', array('as' => 'images.upload', 'uses' => 'WsController@images_upload'));
            Route::post('/images/settings/upload', array('as' => 'images.settings.upload', 'uses' => 'WsController@images_settings_upload'));

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

            Route::group(array('middleware' => 'App\Http\Middleware\SentinelSupervisor'), function () {
                /**
                 * Checking routes
                 */

                Route::post('/daily/fuel/check', array('as' => 'daily.fuel.check', 'uses' => 'Main\DailyController@fuel_check'));

                Route::post('/audit/check', array('as' => 'audit.check', 'uses' => 'Main\AuditController@audit_check'));

                Route::group(array('middleware' => 'App\Http\Middleware\Insight'), function () {
                });

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

                    Route::get('/settings/fuel', array('as' => 'settings.fuel', 'uses' => 'Setting\SettingController@fuel_index'));
                    Route::get('/settings/fuel/add', array('as' => 'settings.fuel.add', 'uses' => 'Setting\SettingController@fuel_add'));
                    Route::get('/settings/fuel/edit/{id}', array('as' => 'settings.fuel.edit', 'uses' => 'Setting\SettingController@fuel_edit'));
                    Route::post('/settings/fuel/save', array('as' => 'settings.fuel.save', 'uses' => 'Setting\SettingController@fuel_save'));
                    Route::post('/settings/fuel/update', array('as' => 'settings.fuel.update', 'uses' => 'Setting\SettingController@fuel_update'));
                    Route::post('/settings/fuel/delete', array('as' => 'settings.fuel.delete', 'uses' => 'Setting\SettingController@fuel_delete'));


                    Route::get('/settings/vessel', array('as' => 'settings.vessel', 'uses' => 'Setting\SettingController@vessel_index'));
                    Route::get('/settings/vessel/add', array('as' => 'settings.vessel.add', 'uses' => 'Setting\SettingController@vessel_add'));
                    Route::get('/settings/vessel/edit/{id}', array('as' => 'settings.vessel.edit', 'uses' => 'Setting\SettingController@vessel_edit'));
                    Route::post('/settings/vessel/save', array('as' => 'settings.vessel.save', 'uses' => 'Setting\SettingController@vessel_save'));
                    Route::post('/settings/vessel/update', array('as' => 'settings.vessel.update', 'uses' => 'Setting\SettingController@vessel_update'));
                    Route::post('/settings/vessel/delete', array('as' => 'settings.vessel.delete', 'uses' => 'Setting\SettingController@vessel_delete'));


                    Route::get('/settings/location', array('as' => 'settings.location', 'uses' => 'Setting\SettingController@location_index'));
                    Route::get('/settings/location/add', array('as' => 'settings.location.add', 'uses' => 'Setting\SettingController@location_add'));
                    Route::get('/settings/location/edit/{id}', array('as' => 'settings.location.edit', 'uses' => 'Setting\SettingController@location_edit'));
                    Route::post('/settings/location/save', array('as' => 'settings.location.save', 'uses' => 'Setting\SettingController@location_save'));
                    Route::post('/settings/location/update', array('as' => 'settings.location.update', 'uses' => 'Setting\SettingController@location_update'));
                    Route::post('/settings/location/delete', array('as' => 'settings.location.delete', 'uses' => 'Setting\SettingController@location_delete'));

                    Route::get('/settings/grading', array('as' => 'settings.grading', 'uses' => 'Setting\SettingController@grading_index'));
                    Route::get('/settings/grading/add', array('as' => 'settings.grading.add', 'uses' => 'Setting\SettingController@grading_add'));
                    Route::get('/settings/grading/edit/{id}', array('as' => 'settings.grading.edit', 'uses' => 'Setting\SettingController@grading_edit'));
                    Route::post('/settings/grading/save', array('as' => 'settings.grading.save', 'uses' => 'Setting\SettingController@grading_save'));
                    Route::post('/settings/grading/update', array('as' => 'settings.grading.update', 'uses' => 'Setting\SettingController@grading_update'));
                    Route::post('/settings/grading/delete', array('as' => 'settings.grading.delete', 'uses' => 'Setting\SettingController@grading_delete'));

                    Route::get('/settings/airline', array('as' => 'settings.airline', 'uses' => 'Setting\DailySettingController@airline_index'));
                    Route::get('/settings/airline/add', array('as' => 'settings.airline.add', 'uses' => 'Setting\DailySettingController@airline_add'));
                    Route::get('/settings/airline/edit/{id}', array('as' => 'settings.airline.edit', 'uses' => 'Setting\DailySettingController@airline_edit'));
                    Route::post('/settings/airline/save', array('as' => 'settings.airline.save', 'uses' => 'Setting\DailySettingController@airline_save'));
                    Route::post('/settings/airline/update', array('as' => 'settings.airline.update', 'uses' => 'Setting\DailySettingController@airline_update'));
                    Route::post('/settings/airline/delete', array('as' => 'settings.airline.delete', 'uses' => 'Setting\DailySettingController@airline_delete'));

                    Route::get('/settings/fire', array('as' => 'settings.fire', 'uses' => 'Setting\MonthlySettingController@fire_index'));
                    Route::get('/settings/fire/add', array('as' => 'settings.fire.add', 'uses' => 'Setting\MonthlySettingController@fire_add'));
                    Route::get('/settings/fire/edit/{id}', array('as' => 'settings.fire.edit', 'uses' => 'Setting\MonthlySettingController@fire_edit'));
                    Route::post('/settings/fire/save', array('as' => 'settings.fire.save', 'uses' => 'Setting\MonthlySettingController@fire_save'));
                    Route::post('/settings/fire/update', array('as' => 'settings.fire.update', 'uses' => 'Setting\MonthlySettingController@fire_update'));
                    Route::post('/settings/fire/delete', array('as' => 'settings.fire.delete', 'uses' => 'Setting\MonthlySettingController@fire_delete'));

                    Route::get('/settings/firetype', array('as' => 'settings.firetype', 'uses' => 'Setting\MonthlySettingController@firetype_index'));
                    Route::get('/settings/firetype/add', array('as' => 'settings.firetype.add', 'uses' => 'Setting\MonthlySettingController@firetype_add'));
                    Route::get('/settings/firetype/edit/{id}', array('as' => 'settings.firetype.edit', 'uses' => 'Setting\MonthlySettingController@firetype_edit'));
                    Route::post('/settings/firetype/save', array('as' => 'settings.firetype.save', 'uses' => 'Setting\MonthlySettingController@firetype_save'));
                    Route::post('/settings/firetype/update', array('as' => 'settings.firetype.update', 'uses' => 'Setting\MonthlySettingController@firetype_update'));
                    Route::post('/settings/firetype/delete', array('as' => 'settings.firetype.delete', 'uses' => 'Setting\MonthlySettingController@firetype_delete'));

                    Route::get('/tankfarm/settings/tanksump', array('as' => 'tf1.settings.tanksump', 'uses' => 'Setting\Tf1DailySettingController@tanksump_index'));
                    Route::get('/tankfarm/settings/tanksump/add', array('as' => 'tf1.settings.tanksump.add', 'uses' => 'Setting\Tf1DailySettingController@tanksump_add'));
                    Route::get('/tankfarm/settings/tanksump/edit/{id}', array('as' => 'tf1.settings.tanksump.edit', 'uses' => 'Setting\Tf1DailySettingController@tanksump_edit'));
                    Route::post('/tankfarm/settings/tanksump/save', array('as' => 'tf1.settings.tanksump.save', 'uses' => 'Setting\Tf1DailySettingController@tanksump_save'));
                    Route::post('/tankfarm/settings/tanksump/update', array('as' => 'tf1.settings.tanksump.update', 'uses' => 'Setting\Tf1DailySettingController@tanksump_update'));
                    Route::post('/tankfarm/settings/tanksump/delete', array('as' => 'tf1.settings.tanksump.delete', 'uses' => 'Setting\Tf1DailySettingController@tanksump_delete'));

                    Route::get('/settings/operator', array('as' => 'settings.operator', 'uses' => 'Setting\SettingController@operator_index'));
                    Route::get('/settings/operator/add', array('as' => 'settings.operator.add', 'uses' => 'Setting\SettingController@operator_add'));
                    Route::get('/settings/operator/edit/{id}', array('as' => 'settings.operator.edit', 'uses' => 'Setting\SettingController@operator_edit'));
                    Route::post('/settings/operator/save', array('as' => 'settings.operator.save', 'uses' => 'Setting\SettingController@operator_save'));
                    Route::post('/settings/operator/update', array('as' => 'settings.operator.update', 'uses' => 'Setting\SettingController@operator_update'));
                    Route::post('/settings/operator/delete', array('as' => 'settings.operator.delete', 'uses' => 'Setting\SettingController@operator_delete'));

                    Route::get('/settings/audit', array('as' => 'settings.audit', 'uses' => 'Setting\AuditSettingController@audit_index'));
                    Route::get('/settings/audit/add', array('as' => 'settings.audit.add', 'uses' => 'Setting\AuditSettingController@audit_add'));
                    Route::get('/settings/audit/edit/{id}', array('as' => 'settings.audit.edit', 'uses' => 'Setting\AuditSettingController@audit_edit'));
                    Route::get('/settings/audit/manage/{id}', array('as' => 'settings.audit.manage', 'uses' => 'Setting\AuditSettingController@audit_manage'));
                    Route::post('/settings/audit/save', array('as' => 'settings.audit.save', 'uses' => 'Setting\AuditSettingController@audit_save'));
                    Route::post('/settings/audit/update', array('as' => 'settings.audit.update', 'uses' => 'Setting\AuditSettingController@audit_update'));
                    Route::post('/settings/audit/delete', array('as' => 'settings.audit.delete', 'uses' => 'Setting\AuditSettingController@audit_delete'));

                    Route::get('/settings/audit/topic/add', array('as' => 'settings.audit.topic.add', 'uses' => 'Setting\AuditSettingController@topic_add'));
                    Route::get('/settings/audit/topic/edit/{id}', array('as' => 'settings.audit.topic.edit', 'uses' => 'Setting\AuditSettingController@topic_edit'));
                    Route::post('/settings/audit/topic/save', array('as' => 'settings.audit.topic.save', 'uses' => 'Setting\AuditSettingController@topic_save'));
                    Route::post('/settings/audit/topic/update', array('as' => 'settings.audit.topic.update', 'uses' => 'Setting\AuditSettingController@topic_update'));
                    Route::post('/settings/audit/topic/delete', array('as' => 'settings.audit.topic.delete', 'uses' => 'Setting\AuditSettingController@topic_delete'));


                    Route::get('/settings/itask/{mode}', array('as' => 'settings.inspect_task', 'uses' => 'Setting\SettingController@inspect_task_index'));
                    Route::get('/settings/itasks/add', array('as' => 'settings.inspect_task.add', 'uses' => 'Setting\SettingController@inspect_task_add'));
                    Route::get('/settings/itask/edit/{id}', array('as' => 'settings.inspect_task.edit', 'uses' => 'Setting\SettingController@inspect_task_edit'));
                    Route::post('/settings/itask/save', array('as' => 'settings.inspect_task.save', 'uses' => 'Setting\SettingController@inspect_task_save'));
                    Route::post('/settings/itask/update', array('as' => 'settings.inspect_task.update', 'uses' => 'Setting\SettingController@inspect_task_update'));
                    Route::post('/settings/itask/delete', array('as' => 'settings.inspect_task.delete', 'uses' => 'Setting\SettingController@inspect_task_delete'));

                    Route::get('/settings/ipreset/edit/{id}', array('as' => 'settings.inspect_preset.edit', 'uses' => 'Setting\SettingController@inspect_preset_edit'));
                    Route::post('/settings/ipreset/update', array('as' => 'settings.inspect_preset.update', 'uses' => 'Setting\SettingController@inspect_preset_update'));

                    Route::get('/settings/refuelled/add', array('as' => 'settings.refuelled.add', 'uses' => 'Setting\SettingController@refuelled_add'));
                    Route::get('/settings/refuelled/edit/{id}', array('as' => 'settings.refuelled.edit', 'uses' => 'Setting\SettingController@refuelled_edit'));
                    Route::post('/settings/refuelled/save', array('as' => 'settings.refuelled.save', 'uses' => 'Setting\SettingController@refuelled_save'));
                    Route::post('/settings/refuelled/update', array('as' => 'settings.refuelled.update', 'uses' => 'Setting\SettingController@refuelled_update'));
                    Route::post('/settings/refuelled/delete', array('as' => 'settings.refuelled.delete', 'uses' => 'Setting\SettingController@refuelled_delete'));

                    Route::get('/settings/delays', array('as' => 'settings.delays', 'uses' => 'Setting\DelaysSettingController@delays_index'));
                    Route::get('/settings/delays/add', array('as' => 'settings.delays.add', 'uses' => 'Setting\DelaysSettingController@delays_add'));
                    Route::get('/settings/delays/edit/{id}', array('as' => 'settings.delays.edit', 'uses' => 'Setting\DelaysSettingController@delays_edit'));
                    Route::post('/settings/delays/save', array('as' => 'settings.delays.save', 'uses' => 'Setting\DelaysSettingController@delays_save'));
                    Route::post('/settings/delays/preset', array('as' => 'settings.delays.preset', 'uses' => 'Setting\DelaysSettingController@delays_preset'));
                    Route::post('/settings/delays/update', array('as' => 'settings.delays.update', 'uses' => 'Setting\DelaysSettingController@delays_update'));
                    Route::post('/settings/delays/delete', array('as' => 'settings.delays.delete', 'uses' => 'Setting\DelaysSettingController@delays_delete'));


                    Route::get('/settings/prevent/task', array('as' => 'settings.prevent.task', 'uses' => 'Setting\MaintenanceSettingController@prevent_index'));
                    Route::get('/settings/prevent/task/add', array('as' => 'settings.prevent.task.add', 'uses' => 'Setting\MaintenanceSettingController@prevent_add'));
                    Route::get('/settings/prevent/task/edit/{id}', array('as' => 'settings.prevent.task.edit', 'uses' => 'Setting\MaintenanceSettingController@prevent_edit'));
                    Route::post('/settings/prevent/task/save', array('as' => 'settings.prevent.task.save', 'uses' => 'Setting\MaintenanceSettingController@prevent_save'));
                    Route::post('/settings/prevent/task/update', array('as' => 'settings.prevent.task.update', 'uses' => 'Setting\MaintenanceSettingController@prevent_update'));
                    Route::post('/settings/prevent/task/delete', array('as' => 'settings.prevent.task.delete', 'uses' => 'Setting\MaintenanceSettingController@prevent_delete'));

                    Route::get('/settings/prevent/fleet', array('as' => 'settings.prevent.fleet', 'uses' => 'Setting\MaintenanceSettingController@fleet_index'));
                    Route::get('/settings/prevent/fleet/edit/{id}', array('as' => 'settings.prevent.fleet.edit', 'uses' => 'Setting\MaintenanceSettingController@fleet_edit'));
                    Route::post('/settings/prevent/fleet/update', array('as' => 'settings.prevent.fleet.update', 'uses' => 'Setting\MaintenanceSettingController@fleet_update'));

                    Route::get('/settings/prevent/category', array('as' => 'settings.prevent.category', 'uses' => 'Setting\MaintenanceSettingController@category_index'));
                    Route::get('/settings/prevent/category/edit/{id}', array('as' => 'settings.prevent.category.edit', 'uses' => 'Setting\MaintenanceSettingController@category_edit'));
                    Route::post('/settings/prevent/category/update', array('as' => 'settings.prevent.category.update', 'uses' => 'Setting\MaintenanceSettingController@category_save'));
                    Route::post('/settings/prevent/category/delete', array('as' => 'settings.prevent.category.delete', 'uses' => 'Setting\MaintenanceSettingController@category_delete'));

                    /**
                     * QCF Setting Incident reporting
                     */
                    Route::get('/settings/incident', array('as' => 'qcf.settings.incident', 'uses' => 'Setting\QcfIncidentSettingController@index'));
                    Route::get('/settings/incident/type/edit/{id}', array('as' => 'qcf.settings.incident.type.edit', 'uses' => 'Setting\QcfIncidentSettingController@type_edit'));
                    Route::post('/settings/incident/type/save', array('as' => 'qcf.settings.incident.type.save', 'uses' => 'Setting\QcfIncidentSettingController@type_save'));
                    Route::post('/settings/incident/type/delete', array('as' => 'qcf.settings.incident.type.delete', 'uses' => 'Setting\QcfIncidentSettingController@type_delete'));

                    Route::get('/settings/incident/notification/edit/{id}', array('as' => 'qcf.settings.incident.notification.edit', 'uses' => 'Setting\QcfIncidentSettingController@notification_edit'));
                    Route::post('/settings/incident/notification/save', array('as' => 'qcf.settings.incident.notification.save', 'uses' => 'Setting\QcfIncidentSettingController@notification_save'));
                    Route::post('/settings/incident/notification/delete', array('as' => 'qcf.settings.incident.notification.delete', 'uses' => 'Setting\QcfIncidentSettingController@notification_delete'));

                    Route::get('/settings/incident/location/edit/{id}', array('as' => 'qcf.settings.incident.location.edit', 'uses' => 'Setting\QcfIncidentSettingController@location_edit'));
                    Route::post('/settings/incident/location/save', array('as' => 'qcf.settings.incident.location.save', 'uses' => 'Setting\QcfIncidentSettingController@location_save'));
                    Route::post('/settings/incident/location/delete', array('as' => 'qcf.settings.incident.location.delete', 'uses' => 'Setting\QcfIncidentSettingController@location_delete'));

                    Route::get('/settings/incident/department/edit/{id}', array('as' => 'qcf.settings.incident.department.edit', 'uses' => 'Setting\QcfIncidentSettingController@department_edit'));
                    Route::post('/settings/incident/department/save', array('as' => 'qcf.settings.incident.department.save', 'uses' => 'Setting\QcfIncidentSettingController@department_save'));
                    Route::post('/settings/incident/department/delete', array('as' => 'qcf.settings.incident.department.delete', 'uses' => 'Setting\QcfIncidentSettingController@department_delete'));

                    Route::get('/settings/incident/forms/edit/{id}', array('as' => 'qcf.settings.incident.forms.edit', 'uses' => 'Setting\QcfIncidentSettingController@forms_edit'));
                    Route::get('/settings/incident/forms/details/{id}', array('as' => 'qcf.settings.incident.forms.details', 'uses' => 'Setting\QcfIncidentSettingController@forms_details'));
                    Route::post('/settings/incident/forms/save', array('as' => 'qcf.settings.incident.forms.save', 'uses' => 'Setting\QcfIncidentSettingController@forms_save'));
                    Route::post('/settings/incident/forms/delete', array('as' => 'qcf.settings.incident.forms.delete', 'uses' => 'Setting\QcfIncidentSettingController@forms_delete'));

                    Route::get('/settings/incident/forms/manage/edit/{id}', array('as' => 'qcf.settings.incident.forms.manage.edit', 'uses' => 'Setting\QcfIncidentSettingController@forms_manage_edit'));
                    Route::post('/settings/incident/forms/manage/save', array('as' => 'qcf.settings.incident.forms.manage.save', 'uses' => 'Setting\QcfIncidentSettingController@forms_manage_save'));
                    Route::post('/settings/incident/forms/manage/delete', array('as' => 'qcf.settings.incident.forms.manage.delete', 'uses' => 'Setting\QcfIncidentSettingController@forms_manage_delete'));

                    /**
                     * regulations
                     **/

                    Route::get('/settings/regulations/{type}', array('as' => 'settings.regulations', 'uses' => 'Setting\RegulationsController@add'));
                    Route::post('/settings/regulations/save', array('as' => 'settings.regulations.save', 'uses' => 'Setting\RegulationsController@save'));
                });
            });
        });

        /**
         * Reports route
         */
        Route::group(array('middleware' => 'App\Http\Middleware\Report'), function () {

        });
    });



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

Route::get('/', function () {

    // return view('welcome');
    return view('auth.login');
});
Route::get('/form', function () {
    // return view('welcome');
    dd(route('action.index'));
    return view('form');
});
Route::get('/ravrock/1991', function () {
    return $_ENV;
});

Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::get('/test', 'HomeController@updateArtifact');
    Route::get('/run', 'HomeController@runmigration');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/dump-excel', 'UploadController@rawDumpAudit')->name('dump-excel');
    Route::get('/cycle', 'DashboardController@bottomProductParameter')->name('getAuditCycle');
    Route::get('/get-branch/{state_id}', 'DashboardController@getBranch')->name('getBranch');
    Route::get('/get-state-data/{state_id}', 'DashboardController@getStateData')->name('getStateData');
    Route::get('/get-agencies/{id}', 'DashboardController@getagencyOfCollection')->name('getagencyOfCollection');
    Route::get('/get-agencies-parameter/{agency_id}', 'DashboardController@getAgencyParameter')->name('getAgencyParameter');
    Route::post('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::post('/all-porudct', 'DashboardController@allProduct')->name('allProduct');
    Route::post('/fetch-map', 'DashboardController@fetchMapData')->name('fetchMap');
    
    
    
    Route::get('/home', 'DashboardController@index')->name('home');
// Route::get('/home', 'HomeController@index')->name('home');

Route::get('profile', 'UserController@profile');
Route::patch('update_profile/{id}', 'UserController@updateProfile');

// Route::group(['middleware' => ['role:zonal']], function () {
Route::resource('user', 'UserController');
// });
Route::resource('roles', 'RoleController');
Route::resource('permissions', 'PermissionController');
Route::get('user/status/{user_id}/{status}', 'UserController@change_user_status');
Route::resource('yard', 'YardController');
Route::resource('agency', 'AgencyController');
Route::resource('branch', 'BranchController');

Route::get('get-agencies-upload/{branch}', 'UploadController@getAgencies')->name('get-upload-agency');
Route::get('get-branch-upload/{lob}', 'UploadController@getBranch')->name('get-upload-branch');
Route::get('upload/gap-view', 'UploadController@gapView');
Route::get('upload/gap-show', 'UploadController@gapView')->name('gapShow');
Route::post('upload/gap-show', 'UploadController@gapShow')->name('getGap');
Route::resource('upload', 'UploadController');
Route::get('user-upload', 'UploadController@userUpload')->name('userUpload');
Route::get('download-user', 'UploadController@downloadUser')->name('downloadUser');
Route::post('user-import', 'UploadController@userImport')->name('userImport');
Route::get('product/hierarchy/view', 'ProductController@hierarchyView')->name('HierarchyView');
Route::get('product/hierarchy', 'ProductController@hierarchy')->name('Hierarchy');
Route::post('product/do-hierarchy', 'ProductController@doHierarchy')->name('doHierarchy');
Route::resource('product', 'ProductController');
Route::resource('qm', 'QmSheetController');

Route::get('/get-regions', 'BranchController@getRegions');
Route::get('/getStates/{id}', 'BranchController@getStates');
Route::get('/getCities/{id}', 'BranchController@getCities');
Route::get('/getAgency/{id}', 'YardController@getAgency');
Route::get('/getAgencyManager/{id}', 'YardController@getAgencyManager');
Route::resource('audit_alert_box','AuditAlertBoxController');
Route::resource('beat_plan','BeatPlanController');
Route::resource('allocation','AllocationController');

Route::resource('qm_sheet', 'QmSheetController');
Route::get('qm_sheet/{sheet_id}/add_parameter','QmSheetController@add_parameter');
Route::get('qm_sheet/{sheet_id}/list_parameter','QmSheetController@list_parameter');
Route::get('qm_sheet/{sheet_id}/parameter','QmSheetController@list_parameter');
Route::post('qm_sheet/store_parameters','QmSheetController@store_parameters')->name('store_parameters');
Route::delete('delete_parameter/{id}','QmSheetController@delete_parameter')->name('delete_parameter');
Route::get('parameter/{id}/edit','QmSheetController@edit_parameter');
Route::post('update_parameter','QmSheetController@update_parameter')->name('update_parameter');
Route::get('delete_sub_parameter/{id}','QmSheetController@delete_sub_parameter');

// Route::group(['middleware' => ['role:collection']], function () {
    Route::get('audit_sheet/{qm_sheet_id}','AuditController@render_audit_sheet');
    Route::get('get_branch_detail/{id}/{type}/{product_id}','AuditController@renderBranch');
    Route::get('get_branch_detail_qc/{id}/{type}/{product_id}','AuditController@renderBranchQc');
    Route::get('get_product/{id}/{type}','AuditController@getProduct');
    Route::get('audit_sheet/{qm_sheet_id}/edit','AuditController@render_audit_sheet_edit');
    //QC
    Route::get('audit_detail/{qm_sheet_id}/edit','AuditController@detail_audit_sheet_edit');
    Route::get('audit_detail/{audit_id}/view','AuditController@render_audit_sheet_View')->name('view_submit_audited');
    Route::get('audit_detail_qc/{audit_id}/view','AuditController@render_audit_sheet_View_QC')->name('view_submit_audited_qc');
    Route::post('save_qc_status','AuditController@save_qc_status')->name('saveStatus');
// });
	Route::get('get_qm_sheet_details_for_audit/{qm_sheet_id}','AuditController@get_qm_sheet_details_for_audit');
	Route::get('get_raw_data_for_audit/{comm_instance_id}','AuditController@get_raw_data_for_audit');

    Route::get('audited_list','AuditController@audited_list')->name('audited_list');
    Route::get('done_audited_list','AuditController@done_audited_list')->name('done_audited_list');
	Route::post('audited_list','AuditController@audited_list_Post')->name('audited_list');
	Route::post('allocation/store_audit','AuditController@store_audit');
	Route::post('allocation/update_audit','AuditController@update_audit');
    Route::get('get_reasons_by_type/{type_id}','AuditController@get_reasons_by_type');
    Route::resource('red-alert','RedAlertController');
    Route::get('download-file/{id}','RedAlertController@downloadFile');
    Route::resource('artifact','ArtifactController');
    Route::get('download-file-artifact/{id}','ArtifactController@downloadFile');
    
    Route::get('action/{sheet_id}/alert','ActionController@create');
    Route::get('action/{id}/view','ActionController@view');
    Route::get('action/list','ActionController@list')->name('action-list');
    Route::resource('action','ActionController');
    Route::get('download-branch', 'BluckUploadController@downloadBranchNew')->name('downloadBranch');
    Route::resource('bulkUpload','BluckUploadController');
    Route::resource('branchrepo','BranchRepoController');
    Route::resource('agencyrepo','AgencyRepoController');
    Route::resource('yardrepo','YardRepoController');
    Route::get('auditor_list','AllocationController@getSheets')->name('auditor_list');
    Route::get('submit_audited_list','AllocationController@done_audited_list')->name('submit_audited_list');
    Route::get('save_audited_list','AllocationController@save_audited_list')->name('save_audited_list');
    Route::get('get_users/{value}/{type}','AuditController@getUsers')->name('getUsers');

    Route::get('reject-user/{email}/{auditId}/{type}','AuditController@rejectUsers')->name('getUsers');
    Route::get('save-user/{email}/{auditId}/{type}/{userid}','AuditController@saveUsers')->name('getUsers');
    Route::get('download-action-artifact/{id}','ActionController@downloadFile');
    Route::get('test-email','RedAlertController@test');
});
Route::get('send','ActionController@sendNextReport');
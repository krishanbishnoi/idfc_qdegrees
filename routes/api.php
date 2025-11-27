<?php



use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;



/*

|--------------------------------------------------------------------------

| API Routes

|--------------------------------------------------------------------------

|

| Here is where you can register API routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| is assigned the "api" middleware group. Enjoy building your API!

|

*/



Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();

});


 #get audit cycle

Route::namespace('Api')->group(function () {
	Route::post('/loginUser','AuthController@login');
	Route::post('/updatePassword','AuthController@updatePassword');
	
	
	// Route::post('auditList', 'AuditController@auditList');

	Route::post('dashboard','DashboardController@dashboard');


	//Audit Sheets
	Route::post('getSheets', 'AllocationController@getSheets'); //Sheet List
	Route::post('savedAuditList', 'AllocationController@savedAuditList');
	Route::post('submittedAuditList', 'AllocationController@submittedAuditList');



	/* For Audit Form fill */
	Route::post('audit_sheet', 'AuditController@render_audit_sheet');
	Route::post('audit_sheet_edit', 'AuditController@render_audit_sheet_edit');
	Route::post('getProduct', 'AuditController@getProduct'); 
	Route::post('renderBranch', 'AuditController@renderBranch');
	Route::post('storeAudit', 'AuditController@store_audit');
	// by sumeet
	Route::post('storeArtifact', 'ArtifactController@storeArtifact');
	// Route::post('transfer_artifact_from_temp_to_main', 'ArtifactController@transfer_artifact_from_temp_to_main');
	// by sumeet
	Route::post('storeRedAlert', 'RedAlertController@storeRedAlert');

	/* Beat Plan APis */
	Route::post('beatplanList', 'BeatPlanController@list');
	Route::post('beatPlanFormField', 'BeatPlanController@beatPlanFormField');
	Route::post('createBeatPlan', 'BeatPlanController@createBeatPlan');
	Route::post('editBeatPlan', 'BeatPlanController@editBeatPlan');
	Route::post('updateBeatPlan', 'BeatPlanController@updateBeatPlan');
	Route::post('deleteBeatPlan', 'BeatPlanController@deleteBeatPlan');

	/* Artifacts List */
	Route::post('artifactsList', 'ArtifactController@artifacts_list');
	Route::post('getdata','DummyController@getdata');
	Route::post('getdata/{id}','DummyController@getdata');
	Route::post('check/{id}','DummyController@checkuser');

	Route::post('artifact_audit_file_links','AuditController@artifact_audit_file_links');

	Route::get('get_audit_cycle', 'AuditController@get_audit_cycle');
	

});



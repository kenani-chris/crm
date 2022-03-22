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
Route::view('/','livewire.home')->name('login');

Route::get('/dashboard','DashboardController@index')->name('home');
Route::post('/dashboard/data','DashboardController@dashboard')->name('home.dashboard');

Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/user/profile','ProfileController@profile')->name('profile.show');
Route::get('/user/password/change','ProfileController@change_password')->name('password.change');
Route::post('user/password/change-password', 'ProfileController@newPassword')->name('new.password');


//Toyota Cases
Route::get('/customer-feedbacks','ToyotaCaseController@index')->name('toyota.case');
Route::post('/customer-feedbacks/reports','ToyotaCaseController@customerFeedback')->name('toyota.customer.feedback');
Route::put('customer-feedbacks/{id}/close','ToyotaCaseController@update')->name('feedback.update');
Route::get('/customer-feedbacks/{id}/customer','ToyotaCaseController@show')->name('toyota.customer.show');
/**Toyota Cases Export Route */
Route::post('/customer-feedbacks/export','ExportController@customerFeedbacksExport')->name('toyota.customer.feedback.export');


/**Export Route */
Route::post('/customer-feedbacks/export','ExportController@customerFeedbacksExport')->name('toyota.customer.feedback.export');



//Resolution Rates
Route::get('/resolution-rates','ResolutionsController@index')->name('resolutions');
Route::post('/resolution-rates','ResolutionsController@index')->name('resolutions.search');
Route::post('/resolution-rates/export','ResolutionsController@export')->name('resolutions.export');
// Route::post('/resolution-rates/reports','ResolutionsController@resolutions')->name('toyota.customer.resolutions');

//Reports

Route::get('reports/{id}/{name}','ReportController@index')->name('reports.index');
Route::post('reports/{id}/monthly','ReportController@monthlyReports')->name('reports.monthly');
Route::post('reports/{id}/voc','ReportController@vocReports')->name('reports.voc');
Route::post('reports/{id}/csi','ReportController@csiReports')->name('reports.csi');
Route::post('reports/{id}/nps','ReportController@npsReports')->name('reports.nps');
Route::post('reports/{id}/advisorcsi','ReportController@advisorcsiReports')->name('reports.advisor.csi');
Route::get('/reports/overallcsi','ReportController@overallCSI')->name('reports.overallcsi');
Route::post('/reports/overallcsi','ReportController@overallCSI')->name('reports.overallcsi.post');
Route::get('/reports/npscall','ReportController@npsCall')->name('reports.npscall');
Route::post('/reports/npscall','ReportController@npsCall')->name('reports.npscall.post');




/**Reports Export Route */
Route::post('/reports/{id}/sales/monthly/export','ExportController@salesMonthlyReport')->name('reports.sales.monthly.export');
Route::post('/reports/{id}/body/monthly/export','ExportController@bodyMonthlyReport')->name('reports.body.monthly.export');
Route::post('/reports/{id}/parts/monthly/export','ExportController@partsMonthlyReport')->name('reports.parts.monthly.export');
Route::post('/reports/{id}/service/monthly/export','ExportController@serviceMonthlyReport')->name('reports.service.monthly.export');
Route::post('/reports/{id}/voc/export','ExportController@VOCReport')->name('reports.voc.export');
Route::post('/reports/{id}/csi/export','ExportController@CSIReport')->name('reports.csi.export');
Route::post('/reports/{id}/nps/export','ExportController@NPSReport')->name('reports.nps.export');
Route::post('/reports/{id}/advisorcsi/export','ExportController@AdvisorCSIReport')->name('reports.advisorcsi.export');
Route::post('/reports/overallcsi/export','ExportController@overallCSIReport')->name('reports.overallcsi.export');
Route::post('/reports/npsscore/export','ExportController@npsScoreReport')->name('reports.npsscore.export');







//Users
Route::get('users','UserController@index')->name('list.users');
Route::get('users/new','UserController@new')->name('new.user');
Route::post('users/new/create','UserController@createUser')->name('new.user.create');


Route::get('users/getUsers','UserController@getUsers')->name('list.getUsers');

Route::get('users/show/{id}','UserController@show')->name('edit.user');
Route::put('users/update/{id}','UserController@update')->name('update.user');

Route::put('users/password/{id}/reset','UserController@updatePassword')->name('password.reset');


//Leads
Route::get('leads','LeadController@index')->name('leads.user');
Route::post('leads','LeadController@index')->name('leads.user.filter');
Route::get('leads/callback','LeadController@callback')->name('leads.user.callback');
Route::post('leads/callback','LeadController@callback')->name('leads.user.callback.filter');
Route::get('leads/unreachable','LeadController@unreachable')->name('leads.user.unreachable');
Route::post('leads/unreachable','LeadController@unreachable')->name('leads.user.unreachable.filter');
Route::get('leads/create','LeadController@create')->name('leads.create');
Route::post('leads/store','LeadController@store')->name('leads.store');
Route::get('leads/{contact}/call','LeadController@call')->name('leads.call');
Route::post('leads/{contact}/call','LeadController@call')->name('leads.call.post');
Route::post('leads/{contact}/call','LeadController@call')->name('leads.call.post');
Route::post('leads/comment-summary','LeadController@commentSummary')->name('leads.comment.summary');
Route::get('leads/download/template','LeadController@download')->name('leads.download.template');
Route::get('leads/leads/tracker','LeadController@leadsTracker')->name('leads.tracker');
Route::post('leads/leads/tracker','LeadController@leadsTracker')->name('leads.tracker.post');
Route::post('leads/leads/tracker/export','LeadController@leadsTrackerExport')->name('leads.tracker.export');






/*Route::get('unauthorized', function () {
    return view('welcome');
});

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');


//Auth::routes();
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');



// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/dashboard', 'HomeController@dashboard')->name('dashboard');

Route::get('/join/channel','SurveyController@channel')->name('join.channel');

Route::post('/somment/summary','SurveyController@getCommentSummary')->name('comment.summary');


Route::get('/leads/{id}/display/{name}','OOCController@displayLeads')->name('leads.display');

Route::get('/display/leads/{id}','OOCController@displayOOCLeads')->name('leads.show');

//DT DOBIE Survey Start
Route::any('/channel/{channel}/intro/','SurveyController@channelIntro')->name('channel.intro');

Route::any('/phone-no/{channel}/search/','SurveyController@predictiveDial')->name('phone.search');



//DT DOBIE

//END DT DOBIE














Route::any('/survey/greater/{id}/intro', 'OOCGreaterSurveyController@intro')->name('survey.intro');

Route::post('/ooc-greater-than-50/{question}/{id}/outbound/survey', 'OOCGreaterSurveyController@surveyQuestions')->name('question.oocgreater');


//Services Survey

Route::any('/survey/service/{id}/intro', 'ServiceController@intro')->name('survey.intro.service');

Route::post('/service/{question}/{id}/outbound/survey', 'ServiceController@surveyQuestions')->name('question.service');

//Terminate Survey
Route::post('/survey/{id}/terminate', 'ServiceController@terminateSurvey')->name('survey.terminate');
//End Service Survey

//Sales
Route::any('/survey/sales/{id}/intro', 'SalesController@intro')->name('survey.intro.sales');

Route::post('/sales/{question}/{id}/outbound/survey', 'SalesController@surveyQuestions')->name('question.sales');

//Body Shop
Route::any('/survey/bodyshop/{id}/intro', 'BodyShopController@intro')->name('survey.intro.bodyshop');
Route::post('/bodyshop/{question}/{id}/outbound/survey', 'BodyShopController@surveyQuestions')->name('question.bodyshop');

//Terminate Survey
//Route::post('/survey/{id}/terminate', 'SalesController@terminateSurvey')->name('survey.terminate');
//End Sales





//Follwups
Route::get('/join/followups','FollowupController@index')->name('join.followup');

Route::any('/channel/{channel}/followup/','FollowupController@followUpChannel')->name('channel.followup');

Route::any('/followups/payments/today','FollowupController@todayFollowups')->name('todayPayments.getlist');



    //payments
    Route::get('/payments/customers/{id}/{channel}/paid','ReportsController@paymentIndex')->name('payments.index');


  
    //Folluwps
    Route::get('/lead/{id}/show/{channel}','FollowupController@getLead')->name('followup.lead');
    Route::post('/followup/{channel}/save','FollowupController@confirmFollowup')->name('followup.store');



Route::group(['middleware' => ['admin']], function () {
    // Registration Routes...
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');

    //Users
    Route::get('users','UserController@index')->name('list.users');
    Route::get('users/getUsers','UserController@getUsers')->name('list.getUsers');
    Route::get('users/export', 'UserController@fileExport')->name('users-export');
    //Edit
    Route::get('users/show/{id}','UserController@show')->name('edit.user');
    Route::put('users/update/{id}','UserController@update')->name('update.user');
    //update

    //DT DOBIE
    Route::get('/leads/new', 'LeadsController@index')->name('new.leads');
    Route::post('/leads/upload','LeadsController@uploadLeads')->name('upload.leads');
    //END DT DOBIE
  

   

      //Reports
    Route::get('/reports/{id}/{channel}','ReportsController@index')->name('reports.index');  
    Route::post('/all/reports/raw-data','ReportsController@rawDataReport')->name('reports.rawdata');


});




//Profile
Route::get('/user/profile','ProfileController@profile')->name('profile.show');
Route::get('/user/password/change','ProfileController@change_password')->name('password.change');
Route::post('user/password/change-password', 'ProfileController@newPassword')->name('new.password');



//Workflow Coordinator
Route::group(['middleware' => ['can:isWFC']], function () {
  


});

//Team Lead Access
Route::group(['middleware' => ['can:isTeamLead']], function () {
    
});

//Analyst
Route::group(['middleware' => ['can:isAnalyst']], function () { 
   

});
//End Analyst

Route::group(['middleware' => ['can:isAdmin']], function () { 


});*/




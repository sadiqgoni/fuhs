<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Forms\EmployeeProfile;
use App\Livewire\Forms\SalaryUpdateCenter;
use App\Livewire\Forms\SalaryLedgerPosting;
use App\Livewire\Reports\PayrollReportCenter;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Livewire\Reports\EmployeeReportCenter;
use App\Livewire\Reports\ReportForIndividual;
use App\Livewire\Forms\AnnualSalaryIncrement;
use App\Livewire\Forms\GroupSalaryUpdate;
use App\Livewire\Forms\DeductionCountdown;
use App\Http\Controllers\Report\EmployeeReport;
use App\Livewire\Passkey\Generate;
use App\Livewire\Passkey\Authenticate;
use App\Livewire\Auth\ChangePassword;
use App\Livewire\Pages\AnnualIncrementHistory;
use App\Livewire\Forms\AvailableAllowances;
use App\Livewire\Forms\AvailableDeduction;
use App\Livewire\Forms\SalaryStructure;
use App\Livewire\Forms\Unit;
use App\Livewire\Forms\Department;
use App\Livewire\Forms\Bank;
use App\Livewire\Forms\Pfa;
use App\Livewire\Forms\StaffUnion;
use App\Livewire\Forms\Rank;
use App\Livewire\Forms\Tribe;
use App\Livewire\Forms\Relationship;
use App\Livewire\Forms\StaffCategory;
use App\Livewire\Forms\EmployeeType;
use App\Livewire\Forms\State;
use App\Livewire\Forms\LocalGovt;
use App\Livewire\Pages\LoanDeductionHistory;
use App\Livewire\Forms\AppSetting;
use App\Livewire\Forms\Restore;
use App\Livewire\Forms\Backup;
use App\Livewire\Forms\Help;
use App\Livewire\Pages\Log;
use App\Livewire\Pages\Analytics;
use App\Livewire\Reports\RetiredStaff;
use App\Livewire\Auth\UserAccount;
use App\Livewire\Forms\SalaryTemplate;
use App\Livewire\Forms\AllowanceTemplate;
use App\Livewire\Forms\DeductionTemplate;
use App\Livewire\Forms\AdminUser;
use App\Livewire\Pages\BackupHistory;
use App\Livewire\Pages\RestorHistory;
use App\Http\Controllers\TaxBracketController;
use App\Http\Controllers\Report\ChartController;
use App\Livewire\Pages\Chart as staff_chart;
use App\Http\Controllers\Auth\StaffAuth;
use App\Livewire\Forms\EmployeePromotion;
use App\Livewire\Forms\LoanDeductionUpload;
use App\Livewire\Pages\ReportRepo;
use App\Livewire\Staff\PayrollRequest;
use App\Http\Controllers\Report\StaffPayroll;
use App\Livewire\Forms\DeductionUnion;
use App\Livewire\Reports\ContractTermination;

use App\Livewire\Pages\RestorePoint;

use App\Http\Controllers\Auth\TwoFactorAuthController;

use App\Http\Controllers\Auth\TwoFASettingsController;

use App\Livewire\Pages\SalaryAnalysisChart;

use App\Livewire\Pages\HelpView;
use App\Livewire\Forms\Question;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/',[StaffAuth::class,'login'])->name('login')->middleware('guest');
Route::post('/',[StaffAuth::class,'postLogin']);

Route::middleware(['auth','is_staff','verified'])->group(function(){
    Route::get('change/staff/password',[DashboardController::class,'show_pass'])->name('staff.password');
    Route::post('change/staff/password',[DashboardController::class,'post_pass']);
    Route::get('payroll/request',PayrollRequest::class)->name('payroll.request')->middleware('has_changed_pass');
    Route::post('staff/payroll/request',[StaffPayroll::class,'generate'])->name('staff.payroll.request')->middleware('has_changed_pass');
    Route::get('profile/update/',\App\Livewire\Staff\Profile::class)->name('staff.profile');
    Route::get('staff/logout',[DashboardController::class,'staff_logout'])->name('staff.logout');
    Route::get('staff/dashboard',[DashboardController::class,'staff_dashboard'])->name('staff.dashboard');

});

Route::get('/admin',[AuthController::class,'login'])->name('admin.login')->middleware('guest');;
Route::post('/admin',[AuthController::class,'postLogin']);
Route::get('/logout',[DashboardController::class,'logout'])->name('logout');
Route::get('help center',HelpView::class)->name('help.view');


Route::middleware(['auth','is_admin','passkey','2fa'])->group(function (){
    Route::get('/2fa/verify', [TwoFactorAuthController::class, 'showVerifyForm'])
        ->name('2fa.verify');
    Route::post('/2fa/verify', [TwoFactorAuthController::class, 'verify'])
        ->name('2fa.verify.submit');
    Route::post('/2fa/resend', [TwoFactorAuthController::class, 'resend'])
        ->name('2fa.resend');
    Route::get('/profile/2fa', [TwoFASettingsController::class, 'showSettingsForm'])->name('profile.2fa');
    Route::post('/profile/2fa/enable', [TwoFASettingsController::class, 'enable'])->name('2fa.enable');
    Route::post('/profile/2fa/disable', [TwoFASettingsController::class, 'disable'])->name('2fa.disable');

    Route::middleware('can:employee_setting')->group(function(){
        Route::get('unit',Unit::class)->name('unit');
        Route::get('department',Department::class)->name('department');
        Route::get('staff/union',StaffUnion::class)->name('staff.union');
        Route::get('pfa',Pfa::class)->name('pfa');
        Route::get('bank',Bank::class)->name('bank');
        Route::get('rank',Rank::class)->name('rank');
        Route::get('tribe',Tribe::class)->name('tribe');
        Route::get('relationship',Relationship::class)->name('relationship');
        Route::get('employee/type',EmployeeType::class)->name('employee.type');
        Route::get('staff/category',StaffCategory::class)->name('staff.category');
        Route::get('state',State::class)->name('state');
        Route::get('local/govt',LocalGovt::class)->name('local.govt');
        Route::get('employee/profile',EmployeeProfile::class)->name('employee.profile');
    });
    Route::get('/dashboard',[DashboardController::class,'dashboard'])->name('dashboard');
    Route::get('salary/update/center',SalaryUpdateCenter::class)->name('salary.update.center')->middleware('can:monthly_update');
    Route::get('salary/ledger/posting',SalaryLedgerPosting::class)->name('salary.ledger.posting')->middleware('can:salary_posting');
    Route::get('payroll/report/center',PayrollReportCenter::class)->name('payroll.report.center')->middleware('can:group_report');
    Route::get('report/download/{file}',[ReportController::class,'download'])->name('report.download')->middleware('can:repository');
    Route::post('payroll/report',[ReportController::class,'report'])->name('payroll_report');
    Route::post('employee/report',[EmployeeReport::class,'report'])->name('employee.report');
    Route::post('individual/report',[EmployeeReport::class,'generate'])->name('individual.report');
    Route::post('loan/deduction/report',[ReportController::class,'loan_deduction_report'])->name('loan.deduction.report');
    Route::post('annual/increment/report',[ReportController::class,'annual_increment_report'])->name('annual.increment.report');
    Route::post('retired/staff/report',[ReportController::class,'retired_staff_report'])->name('retired.staff.report');
    Route::post('contract/termination/list',[ReportController::class,'termination_list'])->name('contract.termination.list');


    Route::get('group/salary/update',GroupSalaryUpdate::class)->name('group-salary-update')->middleware('can:group_update');
    Route::get('staff/annual/salary/increment',AnnualSalaryIncrement::class)->name('staff.annual.salary.increment')->middleware('can:annual_increment');;
    Route::get('staff/annual/salary/increment/history',AnnualIncrementHistory::class)->name('staff.annual.salary.increment.history')->middleware('can:annual_inc_history');;
    Route::get('employee/report/center',EmployeeReportCenter::class)->name('employee.report.center')->middleware('can:nominal_roll');;
    Route::get('report/for/individual',ReportForIndividual::class)->name('report.for.individual')->middleware('can:individual_report');;
    Route::get('deduction/countdown',DeductionCountdown::class)->name('deduction.countdown')->middleware('can:loan_deduction');
    Route::get('loan/deduction/history',LoanDeductionHistory::class)->name('loan.deduction.history')->middleware('can:loan_dedc_history');

    Route::get('generate/passkey',Generate::class)->name('generate.passkey');
    Route::get('authenticate/',Authenticate::class)->name('authenticate');
    Route::get('change/password',ChangePassword::class)->name('change.password');

    Route::get('available/allowance',AvailableAllowances::class)->name('available.allowance')->middleware('can:allowance');
    Route::get('available/deduction',AvailableDeduction::class)->name('available.deduction')->middleware('can:deduction');
    Route::get('salary/structure',SalaryStructure::class)->name('salary.structure')->middleware('can:salary_structure');
    Route::get('salary/template',SalaryTemplate::class)->name('salary.template')->middleware('can:salary_template');
    Route::get('allowance/template',AllowanceTemplate::class)->name('allowance.template')->middleware('can:allowance_template');
    Route::get('deduction/template',DeductionTemplate::class)->name('deduction.template')->middleware('can:deduction_template');



    Route::get('backup',Backup::class)->name('backup')->middleware('can:backup');
    Route::get('restore',Restore::class)->name('restore')->middleware('can:restore');
    Route::get('backup/history',BackupHistory::class)->name('backup.history')->middleware('can:backup_history');
    Route::get('restore/history',RestorHistory::class)->name('restore.history')->middleware('can:restore_history');
    Route::get('audit/log',Log::class)->name('audit.log')->middleware('can:audit_log');
    Route::get('help',Help::class)->name('help');
    Route::get('application/setting',AppSetting::class)->name('application.setting')->middleware('can:app_setting');
    Route::get('analytic/',Analytics::class)->name('analytic');
    Route::get('chart/',staff_chart::class)->name('charts');
    Route::get('retired/staff',RetiredStaff::class)->name('retired.staff')->middleware('can:retired_staff');
    Route::get('user/account',UserAccount::class)->name('user.account');
    Route::get('admin/user',AdminUser::class)->name('admin.user');

    Route::get('payroll/chart',[ChartController::class,'payroll_chart'])->name('chart');
    Route::get('download/backup/{data}',[EmployeeReport::class,'download'])->name('download');

    Route::get('report/repository',ReportRepo::class)->name('report.repository')->middleware('can:repository');
    Route::get('staff/promotion',EmployeePromotion::class)->name('employee.promotion')->middleware('can:can_promote');
    Route::get('loan/deduction/upload',LoanDeductionUpload::class)->name('loan.deduction.upload');
    Route::get('deduction/union/',DeductionUnion::class)->name('deduction.union');
    Route::get('contract/termination/',ContractTermination::class)->name('contract.termination')->middleware('can:terminated_list');

    Route::get('restore/point',RestorePoint::class)->name('restore.point')->middleware('can:restore_point');
    Route::get('salary/analysis',SalaryAnalysisChart::class)->name('salary.analysis');
    Route::get('faq',Question::class)->name('faq');

});

Route::get('verify/account',[AuthController::class,'show_two_factor'])->name('two-factor')->middleware(['auth','verified_passkey']);
Route::post('verify/account',[AuthController::class,'two_factor'])->middleware('auth');

Route::get('individual/payslip/mail/{staff_number}/{month_from}/{month_to}',[EmployeeReport::class,'payslipMail'])->name('payslip.mail');

Route::get('test/',\App\Livewire\Index::class);
Route::get('clear_cache', function () {

    \Illuminate\Support\Facades\Artisan::call('cache:clear');

    dd("Cache is cleared");

});
Route::get('storage', function () {

    \Illuminate\Support\Facades\Artisan::call('storage:link');

    dd("Cache is cleared");

});
Route::post('/save-dom-file', [ReportController::class, 'saveDomFile']);

// Tax Bracket Management Routes (Admin Only)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('tax-brackets', TaxBracketController::class)->names([
        'index' => 'tax-brackets.index',
        'create' => 'tax-brackets.create',
        'store' => 'tax-brackets.store',
        'show' => 'tax-brackets.show',
        'edit' => 'tax-brackets.edit',
        'update' => 'tax-brackets.update',
        'destroy' => 'tax-brackets.destroy',
    ]);
    Route::post('tax-brackets/{taxBracket}/activate', [TaxBracketController::class, 'activate'])->name('tax-brackets.activate');
    Route::get('tax-brackets/{taxBracket}/test', [TaxBracketController::class, 'test'])->name('tax-brackets.test');
});

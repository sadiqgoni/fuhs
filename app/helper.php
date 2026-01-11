<?php

function emp_status($id)
{
    $name='';
    if ($id == 1) {
        $name = "Active";
    }
    if ($id == 2) {
        $name = "Suspended";
    }
    if ($id == 3) {
        $name = "Dismissed";
    }
    if ($id == 4) {
        $name = "Transfered";
    }
    if ($id == 5) {
        $name = "Retired";
    }
    if ($id == 6) {
        $name = "Leave of Absence";
    }
    if ($id == 7) {
        $name = "Secondment";
    }
    if ($id == 8) {
        $name = "Visiting Lecturers";
    }
    if ($id == 9) {
        $name = "Part-timers";
    }
    return $name;
}
function emp_type($id)
{
    $name=\App\Models\EmploymentType::find($id);
    if ($name != null){
      if ($name->id == $id){
          return $name->name;
      }
    }else{
        return $name="NA";
    }
}
function ss($id)
{
    $name=\App\Models\SalaryStructure::find($id);
    try {
       if ($id==$name->id){
           return $name->name;
       }
    }catch (\Exception){
        return "NA";
    }
}
function staff_cat($id){
    $name=\App\Models\StaffCategory::find($id);
    try {
        if ($id==$name->id){
            return $name->name;
        }
    }catch (\Exception){
        return "NA";
    }
}
function dept($id)
{
    $name=\App\Models\Department::find($id);
    try {
        if (!is_null($name)){
            return strtolower($name->name);
        }
    }catch (\Exception){
        return "NA";
    }
}
function unit($id)
{
    $name=\App\Models\Unit::find($id);
    if(!is_null($name)) {

            return $name->name;

    }else{
        return "NA";
    }
}
function rank($id)
{
    $name=\App\Models\Rank::find($id);
   if (!is_null($name)){
       return $name->name;
   }else{
       return "Not Avail";
   }
}
function nationality($id)
{
   if ($id=1){
      return $name="Nigeria";
   }
}
function state($id)
{
    $name=\App\Models\State::find($id);
   if(!is_null($name)){
       return $name->name;
   }else{
        return "NA";
    }
}
function pfa($id)
{
    $name=\App\Models\PFA::find($id);

        if (!is_null($name)){
            return $name->name;

    }else{
        return "NA";
    }
}
function post_held($id)
{
    $name=\App\Models\PostHeld::find($id);

    if (!is_null($name)){
        return $name->name;

    }else{
        return "NA";
    }
}
function lga($id)
{
    $name=\App\Models\LocalGovt::find($id);

        if (!is_null($name)){
            return $name->name;

    }else{
        return "NA";
    }
}
function allowance_type($id)
{
    $name="";
        if (2 == $id){
             $name= "Fixed";
        }elseif (1==$id){
             $name= "POB";
        }
    return $name;
}
function ded_type($id)
{
    $name="";
    if (2 == $id){
        $name= "Fixed";
    }elseif (1==$id){
        $name= "POB";
    }
    return $name;
}
function allowance_name($id)
{
    $name=\App\Models\Allowance::find($id);
    if (!is_null($name)){
        return $name->allowance_name;
    }
}
function deduction_name($id)
{
    $name=\App\Models\Deduction::find($id);
    if (!is_null($name)){
        return $name->deduction_name;
    }
}

function unit_name($id){

   $name= \App\Models\Unit::find($id);
   if (!is_null($name)){
       return $name->name;
   }else{
       return "not avail";
   }
}
function gender($id)
{
    $name=\App\Models\Gender::find($id);
    if(!is_null($name)){
        return $name->name;
    }else{
        return "Not available";
    }
}
function relationships($id)
{
    $name=\App\Models\Relationship::find($id);
    if(!is_null($name)){
        return $name->name;
    }else{
        return "Not available";
    }
}
function religion($id)
{
    $name=\App\Models\Religion::find($id);
    if(!is_null($name)){
        return $name->name;
    }else{
        return "Not available";
    }
}
function marital_status($id)
{
    $name=\App\Models\MaritalStatus::find($id);
    if(!is_null($name)){
        return $name->name;
    }else{
        return "Not available";
    }
}
function tribe($id)
{
    $name=\App\Models\Tribe::find($id);
    if(!is_null($name)){
        return $name->name;
    }else{
        return "Not available";
    }
}


//
//require __DIR__.'/../bootstrap/autoload.php';
//$app = require_once __DIR__.'/../bootstrap/app.php';
//to:
//require __DIR__.'/bootstrap/autoload.php';
//$app = require_once __DIR__.'/bootstrap/app.php';
//$pdf = PDF::loadView('application.full_app_letter', [
//    'app_id'=>$app_id
//]);
//return $pdf->stream('full_application_'. Carbon::now()->format('y-m-d') .'pdf');



//error_reporting(E_ALL);
//ini_set('display_errors', 1);
function pfa_name($id){
    $name="";
    $name=\App\Models\PFA::find($id);
    if (!is_null($name)){
        return strtolower($name->name);
    }else{
        return "";
    }
}
function logo()
{
    return env('App_Logo');
}

function allowance_status($id){
    if($id==0){
        return "Discontinue";
    }else{
        return "Active";
    }
}
function deduction_status($id){
    if ($id==0){
        return "Discontinue";
    }else{
        return "Active";
    }
}
function g_s($id){
    if ($id==1){
        return "Active";
    }else{
        return "Discontinued";
    }
}
function deduction_type($id){
    if ($id==1){
        return "Define in Template";
    }else{
        return "Not define";

    }
}
function status($id)
{
    if ($id==0){
        return "Discontinued";
    }
    if ($id==1){
        return "Active";
    }
}
function user_status($id)
{
    if ($id==1){
        return "Active";
    }
    if ($id==0){
        return "Pending";
    }else{
        return "Pending";
    }
}
function success_status($id)
{
    if ($id==1){
        return "Successful";
    }
    if ($id==0){
        return "Failed";
    }
}
function visibility_status($id)
{
    if ($id==0){
        return "Hidden";
    }
    if ($id==1){
        return "Visible";
    }
}
function backup_type($id){
    if($id==1){
        return "Payroll Data";
    }
    if($id==2){
        return "Loan Deduction History Data";
    }
    if($id==3){
        return "Employee Profile Data";
    }
    if($id==4){
        return "Salary Update Data";
    }
    if($id==5){
        return "Salary Template Data";
    }
    if($id==6){
        return "Allowance  Template Data";
    }
    if($id==7){
        return "Deduction  Template Data";
    }
}
function loan_status($id)
{
    if($id=="0"){
        return "Active";
    }elseif($id==1){
        return "Suspended";
    }
}

if (! function_exists('organizations')) {
    function organizations()
    {
        return \App\Models\Department::orderBy('name')->get();
    }
}
function app_settings()
{
    try {
        return \App\Models\AppSetting::first();

    }catch (\Exception $e){
        return "Set up your application variables or contact your developers";
    }
}
function address()
{
    return \App\Models\AppSetting::first()->address;
}
function no_record()
{
    return "There is no staff that matches your selection criteria, please check and try again.";
}
function staff_union($id){
    $name=\App\Models\Union::find($id);
    if ($name != null){
        return $name->name;
    }else{
        return 0;
    }
}
function report_file_name()
{
    $stopWords = ['to', 'of', 'us', 'the', 'in', 'on', 'at', 'and', 'or', 'a', 'an', 'is', 'with', 'for'];

    $words = explode(' ', strtolower(app_settings()->name)); // break into words
    $filtered = array_filter($words, function ($word) use ($stopWords) {
        return !in_array($word, $stopWords);
    });

    $word= implode(' ', $filtered);
    $sentence = $word;
    $initials = collect(explode(' ', $sentence))->map(function ($word) {
        return strtoupper(substr($word, 0, 1));
    })->implode('');
    return $initials;
}

function active_dropdown($link)
{
    if ($link==1){
        request()->is("dashboard") ? "active" : " ";

    }
}

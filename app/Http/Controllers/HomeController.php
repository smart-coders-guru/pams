<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

use App\Apps;
use App\Report;
use App\Report_template;
use App\Email_account;
use App\Mailing_list;
use App\Programmed_email;
use Mail;

class HomeController extends Controller
{
   
    function index(Request $request){
       if($request->session()->has('apps')){
            $app = $request->session()->get('apps');
            foreach ($app as $app) {
                if($app->apps_login=='noApp'){
                    $apps = Apps::all();
                    $app = Apps::all();
                    return view('index', ['apps'=>$apps, 'app'=>$app]);
                }else{
                    $myApps = session('apps');
                    return view('pages.dashboard', ['myApps'=>$myApps]);
                }
            }
        }else{
            return view('pages.login');
        }
    }

    
    function getReportTemplate(Request $request){
        if($request->session()->has('apps')){
             $report_template = new Report_template();
             $report_template = Report_template::all(); 
             $app = Apps::all(); 
             $myApps = session('apps');
             return view('pages.report-template', ['report_template'=>$report_template, 'myApps'=>$myApps, 'app'=>$app]);
        }else{
            return view('pages.login');
        }
    }
    
    function saveReport(Request $request){
        $reports = new Report();
        $reports->report_template_id = 1;
        $reports->apps_id = 1;
        $reports->report_title = $request->input('report_title');
        $reports->report_content = $request->input('report_content');
        $reports->report_link = $request->input('report_link');
        $reports->report_desc = $request->input('report_desc');
        $reports->report_state = 0;
        $reports->save();
        $reports= Report_template::all(); 
        return view('pages.reports', ['reports'=>$reports]);
    }
    function getEmailAccounts(){
         $email_account = new Email_account();
         $email_account = Email_account::all();
         $app = Apps::all(); 
         $myApps = session('apps'); 
         return view('pages.email-account', ['email_account'=>$email_account, 'app'=>$app, 'myApps'=>$myApps]);
    }
    function saveEmailAccount(Request $request){
        $email_account = new Email_account();
        $email_account->apps_id = 1;
        $email_account->email_account_name = $request->input('email_account_name');
        $email_account->email_account_email = $request->input('email_account_email');
        $email_account->email_account_desc = $request->input('email_account_desc');
        $email_account->email_account_state = 0;
        $email_account->save();
        $email_account = Report_template::all(); 
        return view('pages.email-account', ['email_account'=>$email_account]);
    }
    function getMailingList(){
         $mailing_list = new Mailing_list();
         $mailing_list = Mailing_list::all(); 
         $app = Apps::all(); 
         $myApps = session('apps'); 
         return view('pages.mailing-list', ['mailing_list'=>$mailing_list, 'app'=>$app, 'myApps'=>$myApps]);
    }
    function saveMailingList(Request $request){
        $mailing_list = new Mailing_list();
        $mailing_list->apps_id = 1;
        $mailing_list->mailing_list_name = $request->input('email_account_name');
        $mailing_list->mailing_list_additional_email = $request->input('mailing_list_additional_email');
        $mailing_list->mailing_list_desc = $request->input('mailing_list_desc');
        $mailing_list->mailing_list_state = 0;
        $mailing_list->save();
        $mailing_list = Mailing_list::all();
        return view('pages.mailing-list', ['mailing_list'=>$mailing_list]);
    }
    function getProgrammedMailList(){
        $prog_email = new Programmed_email();
        $prog_email = Programmed_email::all(); 
        $app = Apps::all(); 
        $myApps = session('apps');
        return view('pages.programmed-email', ['prog_email'=>$prog_email, 'app'=>$app, 'myApps'=>$myApps]);
    }
    function saveProgrammedEmail(){
    /*    $prog_email = new Programmed_email();
        $prog_email->programmed_email*/
    }
    function getMailList(){
        $prog_email = new Programmed_email();
        $prog_email = Programmed_email::all(); 
        $app = Apps::all(); 
        $myApps = session('apps');
        return view('pages.email', ['prog_email'=>$prog_email, 'app'=>$app, 'myApps'=>$myApps]);
    }
    function connected(Request $request){
        $app = new Apps();
        $app = DB::table('apps')->where('apps_login', $request->apps_login)->where('apps_password', $request->apps_password)->get();
        if($app!=null){
            $request->session()->put('apps', $app);
            if($request->apps_login == 'noApp'){
                  $apps = Apps::all();
                  $app = Apps::all();
            return view('index', ['apps'=>$apps, 'app'=>$app]);
            }else{
                $myApps = session('apps');
                return view('pages.dashboard', ['myApps'=>$myApps, 'app'=>$app]);
            } 
        }
        return  view('pages.login');
    }
    function logout(Request $request){
        $request->session()->pull('apps');
        return view('pages.login');
    }
    function showDashboard(Request $request){
        if($request->session()->has('apps')){
            $app = Apps::all(); 
            $myApps = session('apps');
            return view('pages.dashboard', ['myApps'=>$myApps, 'app'=>$app]);
         }else{
            $myApps = session('apps');
            $app = session('apps');
            return view('pages.dashboard', ['myApps'=>$myApps, 'app'=>$app]);
        }
    }
    function selectAppByAdmin(Request $request, $apps_name){
        if($request->session()->has('apps')){
            $application=session('apps');
            foreach ($application as $app) {
               if($app->apps_login == 'noApp'){
                    $myApps = DB::table('apps')->where('apps_name', $request->apps_name)->get();
                    $app = Apps::all(); 
                    return view('pages.dashboard', ['myApps'=>$myApps, 'app'=>$app]);
               }else{
                return view('pages.login');
               }
            }
        }else{
            return view('pages.login');
        }
    }  
}
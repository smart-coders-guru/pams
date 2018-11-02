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
use App\Email_template;
use Mail;

class ReportingController extends Controller
{
    function saveReportTemplate(Request $request){
        $report_template = new Report_template();
        $report_template->report_template_name = $request->input('report_template_name');
        $report_template->report_template_content = $request->input('report_template_content');
        $report_template->report_template_desc = $request->input('report_template_desc');
        $report_template->report_template_state = 0;
        $report_template->save();
        $report_template = Report_template::all(); 
        $app = Apps::all();
        return view('pages.report-template', ['report_template'=>$report_template, 'app'=>$app]);
    }
    function getAllReportTemplates(Request $request){
    	if($request->session()->has('apps')){
            $report_template = Report_template::all(); 
            $app = Apps::all(); 
            return view('pages.report-template', ['report_template'=>$report_template, 'app'=>$app]);
        }else{
            return view('pages.login');
        }
    }
    function getAllReports(Request $request){
    	if($request->session()->has('apps')){
            $reports = Report::all(); 
            $app = Apps::all(); 
            return view('pages.reports', ['reports'=>$reports, 'app'=>$app]);
        }else{
            return view('pages.login');
        }
    }
     function deleteReport(Request $request){
	   if($request->session()->has('apps')){
            $application=session('apps');
        	foreach ($application as $app) {  
                DB::table('report')->where('report_id', $request->report_id)->update(['report_state' => 1]);
                $reports = Report::all(); 
            	$app = Apps::all(); 
            return view('pages.reports', ['reports'=>$reports, 'app'=>$app]);
            }
        }else{
            return view('pages.login');
        }
    }
    function saveEmailTemplate(Request $request){
    	$email_template = new Email_template();
    	$email_template->apps_id=1;
    	$email_template->email_template_name = $request->input('email_template_name');
    	$email_template->email_template_content=$request->input('email_template_content');
    	$email_template->email_template_state=0;
    	$email_template->save();
    	$apps = Apps::all();
        $app = Apps::all();
        return view('index', ['apps'=>$apps, 'app'=>$app]);
    }
}

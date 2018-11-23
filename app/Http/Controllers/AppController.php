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

class AppController extends Controller
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
    function generateKey(){
        $chaine='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $chaineA='';
        for ($i = 0; $i < 20; $i++){ $chaineA .= $chaine[rand(0, strlen($chaine) - 1)];}
        return substr(encrypt($chaineA.date('s').rand()), 0, 14); 
    }
    function saveApps(Request $request){
        if($request->session()->has('apps')){
            $apps = new Apps();
            $apps->apps_name = $request->input('apps_name');
            $apps->apps_key = $this->generateKey();
            $apps->apps_login = $request->input('apps_login');
            $apps->apps_password = $request->input('apps_password');
            $apps->apps_desc = $request->input('apps_desc');
            $apps->apps_state = 0;
            $apps->apps_creation_date = date('Y-m-d H:i:s');
            $apps->save();
            $apps = Apps::all();
            $app = Apps::all();
            return view('index', ['apps'=>$apps, 'app'=>$app]);
       }else{
            return view('pages.login');
        }
    }
   function updateCurrentApp(Request $request){
    	if($request->session()->has('apps')){
            $application=session('apps');
        	foreach ($application as $app) {
                DB::table('apps')->where('apps_id', $request->apps_id)->update(['apps_name' => $request->apps_name, 'apps_login'=>$request->apps_login, 'apps_desc'=>$request->apps_desc, 'apps_password'=>$request->apps_password]);
                $sessionApp=DB::table('apps')->where('apps_id', $request->apps_id);
                $request->session()->pull('apps');
                $request->session()->put('apps', $sessionApp);
                $apps = Apps::all();
                $app = Apps::all();
                return view('index', ['apps'=>$apps, 'app'=>$app]);
            }
        }else{
            return view('pages.login');
        }
    }
    function deleteApp(Request $request){
	   if($request->session()->has('apps')){
            $application=session('apps');
        	foreach ($application as $app) {
               if($app->apps_login == 'noApps'){
                    DB::table('apps')->where('apps_id', $request->apps_id)->update(['apps_state' => 1]);
                    $apps = Apps::all();
                    $app = Apps::all();
                    $status = true;
                    return view('index', ['apps'=>$apps, 'app'=>$app, "status"=>$status]);
               }else{
                return view('pages.login');
               }
            }
        }else{
            return view('pages.login');
        }
    }
}

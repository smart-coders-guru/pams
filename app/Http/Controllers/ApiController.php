<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

use App\Apps;
use App\Report;
use App\Report_template;
use App\Email_account;
use App\Mailing_list;
use App\Programmed_email;
use Mail;
use Excel;
use PDF;

class ApiController extends Controller
{
    
    function getAppByKey(Request $request, $key){
    	return DB::table('apps')->where('apps_key', $key)->get();
    }
    function haveAccessRight(Request $request, $key){
    	$app = $this->getAppByKey($request, $key); 
    	if($app!=null)
    		return true;
    	return false;
    }
    function string2KeyedArray($string, $delimiter = ',', $kv = '=>') {
	  if ($a = explode($delimiter, $string)) { 
	    foreach ($a as $s) { 
	      if ($s) {
	        if ($pos = strpos($s, $kv)) { 
	          $ka[trim(substr($s, 0, $pos))] = trim(substr($s, $pos + strlen($kv)));
	        } else { 
	          $ka[] = trim($s);
	        }
	      }
	    }
	    return $ka;
	  }
	}
    function convertStringToArray(Request $request){
    	return $this->string2KeyedArray($request->data);
    }
   
    function downloadPdfReport(Request $request, $key, $title){
    	if($this->haveAccessRight($request, $key)){
    		return response()->file(storage_path().'/'.$title);
    	}
    	return "vous n avez pas le droit";
    }
	
	function setParam($paramId, $replacement, $content){
		$pattern = "/#".$paramId."_[1-9]{1,4}#/";
		$content = preg_replace($pattern, $replacement, $content);
		$pattern = "/#[a-zA-Z0-9_]{1,}".$paramId."#/";
		$content = preg_replace($pattern, $replacement, $content);
		return $content;
	}
	
	function getContent($template, $dataArr){
		$copy = $template;
		foreach($dataArr as $key => $value){
			$copy = $this->setParam($key, $value, $copy);
		}
		return $copy;
	}
	
	function generateReport(Request $request){
		$key = $request->input('key');
		$data = $request->input('data');
		$type = $request->input('type');
    	if($this->haveAccessRight($request, $key)){
			$date = date('Y_m_d H_i_s');
    		$app = $this->getAppByKey($request, $key); 
    		$report = new Report();
			if($type=="pdf"){
		    	$pdf = \App::make('dompdf.wrapper');
				
				$dataArr = json_decode($data, true);
				$repTemplate = DB::table('report_template')->where('report_template_name', $dataArr['template'])->get()[0];
				$repContent = $this->getContent($repTemplate->report_template_content, $dataArr['data']);
		    	$pdf->loadHTML($repContent);
				
		    	$pdf->save(storage_path().'/PdfFile-'.$date.'.pdf');
		        $report->report_template_id = 1;
		        foreach ($app as $app) {
		        	$report->apps_id = $app->apps_id;
		        }
		        $report->report_title = "PdfFile-".$date.".pdf"; 
		        $report->report_content = json_encode($dataArr['data']);
		        $report->report_link = url("download/".$key."/PdfFile-".$date.".pdf");
		        $report->report_desc = "";
		        $report->report_state = 0;
		        $report->save();
		    	return response()->json($report, 200);
		    }
			if($type=="excel"){
		    	Excel::create($title, function($excel) {
		            $excel->setTitle("ici le titre");
		            $excel->setCreator('Me')->setCompany('Our Code World');
		            $excel->setDescription('A demonstration to change the file properties');
		            $data = [12,"Hey",123,4234,5632435,"Nope",345,345,345,345];
		            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
	                $sheet->setOrientation('landscape');
	                $sheet->fromArray($data, NULL, 'A3');
	               // $excel->save(storage_path().'/ExcelFile.pdf');
           	    	});
                })->download('xlsx');  
		    }
		    if($type=="word"){
	    		$phpWord = new \PhpOffice\PhpWord\PhpWord();
    	        $section = $phpWord->addSection();
    	        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
      	        $section->addText($this->word_content());
		        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		        try {
		            $objWriter->save(storage_path('report.docx'));
		        } catch (Exception $e) {

		        }
		        return response()->download(storage_path('report.docx'));
		    }
		}
	}

    function word_content(){
    	return "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    }
	
	
    function simpleEmailSend(Request $request){
	  $key = $request->input('key');
	  $data_ = $request->input('data');
	  if($this->haveAccessRight($request, $key)){
		$dataArr = json_decode($data_, true);
		$data = array('name'=>"Reporting/Mailing Service of webcolf");
		Mail::send([], $data, function($message) use ($dataArr){
			 $message->to($dataArr['to'], 'Reporting/Mailing Service of webcolf')->subject
				($dataArr['subject']);
				
			 $mailTemplate = DB::table('email_template')->where('email_template_name', $dataArr['template'])->get()[0];
			 $mailContent = $this->getContent($mailTemplate->email_template_content, $dataArr['data']);
			 $message->setBody($mailContent, 'text/html');
			 $message->attach(storage_path().'/PdfFile-2018_11_02 09_56_07.pdf');
			 $message->from('dev.miu.baos@gmail.com','Mailing of Webcolf');
		});
		return response()->json("The email was sent successfully", 200);
	  }
   }
}
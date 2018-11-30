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
    /*function string2KeyedArray($string, $delimiter = ',', $kv = '=>') {
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
    */
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
				//return $dataArr["template"];
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

	function testPdf(){
		$pdf = \App::make('dompdf.wrapper');
		$pdf->loadHTML($this->costi());
		return $pdf->stream();
	}
	function costi(){
		return "
		<style type=\"text/css\">
			.marginLeft{font-size:12px;}	
		</style>
		<div style=\" padding-left:5px; font-size:12px; padding-right:5px;text-align:justify;\">
		<div style=\"margin-top:0px; border-bottom:1px solid; padding-left:20px;padding-right:20px; padding-bottom:5px;\">
			<div style=\"width: 100%; font-weight: bold;font-size:18px;\"><span>www.WEBCOLF.com - Scheda retribuzione ed analisi costo collaboratore</span> <span style=\"float: right;\">Anno: 2018</span></div>
		</div>
		<div>
			<span>Datore di lavoro: CAVALLETTO EDOARDINO</span><br>
			<span>Residenza (VIA) : VIA SALGARI, 19</span> <span style=\"margin:auto 20px auto 20px;\">Residenza (VIA) : VIA SALGARI, 19</span> <span>Residenza (VIA) : VIA SALGARI, 19</span><br/>
			<span>Residenza (VIA) : VIA SALGARI, 19</span> <span style=\"margin:auto 20px auto 20px;\">Residenza (VIA) : VIA SALGARI, 19</span> <span>Residenza (VIA) : VIA SALGARI, 19</span><br/>
		</div>
		<div style=\"margin-top:10px; border-top:1px solid; border-bottom:1px solid; padding-bottom:5px;\">
			<div style=\"width: 100%; font-weight: bold;font-size:18px;\"><span>Collaboratore: NARUDIA IZEHIESE</span></div>
			<div style=\"width: 100%; font-weight: bold;font-size:18px;\"><span>Collaboratore: NARUDIA IZEHIESE</span></div>
			<div style=\"width: 100%; font-weight: bold;font-size:18px;\"><span>Collaboratore: NARUDIA IZEHIESE</span></div>
			<div style=\"width: 100%; font-weight: bold;font-size:18px;\"><span>Collaboratore: NARUDIA IZEHIESE</span></div>
		</div>
		<div style=\"margin-top:10px; border-top:none; border-bottom:1px solid; padding-bottom:5px;\">
			<table border=\"1\">
				<tr>
					<td style=\" \">Paga base oraria  :</td>
					<td style=\"padding-left:50px;\">4,57000</td>
					<td>Inquadramento  :</td>
					<td style=\"padding-left:50px;\">A</td>
				</tr>

			</table>
		</div>
		<div style=\"margin-top:10px; border-top:none; border-bottom:1px solid; padding-bottom:5px;\">
			<table border=\"1\">
				<tr>
					<td>Ore:</td>
					<td>01</td>
					<td class=\"marginLeft\">02</td>
					<td class=\"marginLeft\">03</td>
					<td class=\"marginLeft\">04</td>
					<td class=\"marginLeft\">05</td>
					<td class=\"marginLeft\">06</td>
					<td class=\"marginLeft\">07</td>
					<td class=\"marginLeft\">08</td>
					<td class=\"marginLeft\">09</td>
					<td class=\"marginLeft\">10</td>
					<td class=\"marginLeft\">11</td>
					<td class=\"marginLeft\">12</td>
					<td>13</td>
					<td>14</td>
					<td class=\"marginLeft\">15</td>
					<td class=\"marginLeft\">16</td>
					<td class=\"marginLeft\">17</td>
					<td class=\"marginLeft\">18</td>
					<td class=\"marginLeft\">19</td>
					<td class=\"marginLeft\">20</td>
					<td class=\"marginLeft\">21</td>
					<td class=\"marginLeft\">22</td>
					<td class=\"marginLeft\">23</td>
					<td class=\"marginLeft\">24</td>
					<td class=\"marginLeft\">25</td>
					<td class=\"marginLeft\">26</td>
					<td class=\"marginLeft\">27</td>
					<td class=\"marginLeft\">28</td>
					<td class=\"marginLeft\">29</td>
					<td class=\"marginLeft\">30</td>
					<td class=\"marginLeft\">31</td>
				</tr>
				<tr>
					<td >Genn.</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
				</tr>
				<tr>
					<td>Febb.</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
				</tr>
				<tr>
					<td><b>Marzo</b></td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
				</tr>
				<tr>
					<td>Apri.</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
				</tr>
				<tr>
					<td>Magg.</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
				</tr>
				<tr>
					<td>Giug.</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
				</tr>
				<tr>
					<td>Lugl.</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
				</tr>
				<tr>
					<td>Agos.</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
				</tr>
				<tr>
					<td>Sett.</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
				</tr>
				<tr>
					<td>Otto.</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
				</tr>
				<tr>
					<td>Nove.</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
				</tr>
				<tr>
					<td>Dice.</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
					<td>5</td>
				</tr>
			</table>
		</div>
		<div style=\"margin-top:10px; border-top:none; border-bottom:none; padding-bottom:5px;\">
			<table border=\"1\">
				<tr>
					<td style=\"font-family:Apple Chancery, cursive;\">Costi mensili:</td>
					<td style=\"padding-left:40px;\">Genn.</td>
					<td class=\"marginLeft\">Febb.</td>
					<td class=\"marginLeft\">Marzo</td>
					<td class=\"marginLeft\">Apri.</td>
					<td class=\"marginLeft\">Magg.</td>
					<td class=\"marginLeft\">Giug.</td>
					<td class=\"marginLeft\">Lugl.</td>
					<td class=\"marginLeft\">Agos.</td>
					<td class=\"marginLeft\">Sett.</td>
					<td class=\"marginLeft\">Otto.</td>
					<td class=\"marginLeft\">Nove.</td>
					<td class=\"marginLeft\">Dice.</td>
				</tr>
				<tr>
					<td >Retrib.lorda:</td>
					<td style=\"padding-left:40px;\">Genn.</td>
					<td>831,93</td>
					<td>703,00</td>
					<td>773,30</td>
					<td>726,48</td>
					<td>802,62</td>
					<td>767,47</td>
					<td>773,30</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Cont.Car.Dip:</td>
					<td style=\"padding-left:40px;\">Genn.</td>
					<td>Febb.</td>
					<td>Marzo</td>
					<td>Apri.</td>
					<td>Magg.</td>
					<td>Giug.</td>
					<td>Lugl.</td>
					<td>Agos.</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Netto :</b></td>
					<td style=\"padding-left:40px;\"><b>805,00 </b></td>
					<td><b>676,00</b></td>
					<td><b>740,00</b></td>
					<td><b>699,00</b></td>
					<td><b>776,00</b></td>
					<td><b>733,00</b></td>
					<td><b>747,00</b></td>
					<td><b>775,00</b></td>
					<td><b></b></td>
					<td><b></b></td>
					<td><b></b></td>
					<td><b></b></td>
				</tr>
				<tr>
					<td>Vitto/Allogg :</td>
					<td style=\"padding-left:40px;\">Genn.</td>
					<td>Febb.</td>
					<td>Marzo</td>
					<td>Apri.</td>
					<td>Magg.</td>
					<td>Giug.</td>
					<td>Lugl.</td>
					<td>Agos.</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Contributi :</td>
					<td style=\"padding-left:40px;\">Genn.</td>
					<td>Febb.</td>
					<td>Marzo</td>
					<td>Apri.</td>
					<td>Magg.</td>
					<td>Giug.</td>
					<td>Lugl.</td>
					<td>Agos.</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Ferie  :</td>
					<td style=\"padding-left:40px;\">Genn.</td>
					<td>Febb.</td>
					<td>Marzo</td>
					<td>Apri.</td>
					<td>Magg.</td>
					<td>Giug.</td>
					<td>Lugl.</td>
					<td>Agos.</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>13a mensil :</td>
					<td style=\"padding-left:40px;\">Genn.</td>
					<td>Febb.</td>
					<td>Marzo</td>
					<td>Apri.</td>
					<td>Magg.</td>
					<td>Giug.</td>
					<td>Lugl.</td>
					<td>Agos.</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>T. F. R.</td>
					<td style=\"padding-left:40px;\">60,36</td>
					<td>57,22</td>
					<td>62,95</td>
					<td>58,96</td>
					<td>65,39</td>
					<td>62,52</td>
					<td>63,22</td>
					<td>65,65</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Totale Costo:</td>
					<td style=\"padding-left:40px;\">1.097,22</td>
					<td>965,15</td>
					<td>1.060,69</td>
					<td>990,37</td>
					<td>1.072,94</td>
					<td>1.054,42</td>
					<td>1.041,45</td>
					<td>1.073,20</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</div>
	</div>
";
	}

	function cu_2018(){
		return "<div style=\"padding-left:30px; padding-bottom:30px;\">
		<div style=\"text-align: center;width: 100%;margin-top:30px;\">
			<u><span style=\"font-size: 25px; font-weight: bold;\">Dichiarazione sostitutiva Certificazione Unica</span></u><br/><span style=\"font-size: 18px;\">(Certificazione unica dei compensi, Ex CUD)</span>
		</div>
		<div  style=\"width: 100%; margin-top: 40px;\">
			<div style=\" float:left;width:15%;\">
				Il sottoscritto
			</div>
			<div  style=\"width: 80%; float:left; margin:10px auto 10px auto;padding-bottom:5px!important;border:none;border-bottom:2px black solid!important; font-weight: bold;\" >
				CAVALLETTO EDOARDINO 
			</div>
			<form>
				<label style=\"width:15%\"></label>
				x

				<label>nato/a a </label>
				<input type=\"text\" style=\"width: 30%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"BOLOGNA\"/>
				<label>provincia</label>
				<input type=\"text\" style=\"width: 25%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"BO\"/>
				<label>il</label>
				<input type=\"text\" name=\" style=\"width: 30%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"11/09/1953\"/><br>

				<label>residente in</label>
				<input type=\"text\"  style=\"width: 55%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"BOLOGNA\"/>
				<label>provincia </label>
				<input type=\"text\" style=\"width: 30%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"BO\"/> <br>


				<label>via </label>
				<input type=\"text\"  style=\"width: 60%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"CVLDDN53P11A944D\"/>
				<label>frazione</label>
				<input type=\"text\" name=\" style=\"width: 30%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"/> <br>

				<label>codice fiscale</label>
				<input type=\"text\" name=\" style=\"width: 90%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"CVLDDN53P11A944D\"/>
			</form>
		</div>
		<div>
			<div style=\"width:60%;  float: left;\">
				<h1 style=\"text-align:right;margin-right: 50px;\"><u>ATTESTA</u></h1>
				<span style=\">di avere corrisposto per prestazioni di lavoro domestico nell'anno</span>
			</div>
			<div style=\"width:35%; float: left;\">
				<div style=\"width:90px; background-color: rgba(50, 150, 30, 0.5); margin-top: 40px; border-radius:10px; padding-top: -20px;padding-bottom: -20px;padding-left: 100px; padding-right: 100px; border:2px solid; position:relative; float: right;\"><h1>2017</h1></div>
			</div>	
		</div>
		<div style=\"clear: both;\"></div>
		<div  style=\"text-align: center;width: 100%; margin-top: 40px;\">
			<form>
				<label>per il periodo dal</label>
				<input type=\"text\" name=\" style=\"width: 30%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"05/07/2017\"/>
				<label> al</label>
				<input type=\"text\" name=\" style=\"width: 25%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"31/12/2017\"/>
				<label>pari a numero giorni di detrazioni:</label>
				<input type=\"text\" name=\" style=\"width: 10%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"180\"/><br>


				<label>al / la lavoratore / trice</label>
				<input type=\"text\" name=\" style=\"width: 85%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"NARUDIA IZEHIESE\"/> <br>


				<label>codice fiscale</label>
				<input type=\"text\" name=\" style=\"width: 90%; margin:10px auto 10px auto;border:none;border-bottom:2px rgb(150, 150, 150) solid!important;\" value=\"codice fiscale\"/> <br>

			</form>
		</div>
		<div>
			<b>i seguenti compensi:</b>
			<form style=\"text-align: right;\">
				<div style=\"margin:auto auto 20px 20%;\">
					<label style=\"font-weight: bold;font-size: 25px;\">Retribuzione lorda (comprensiva di tredicesima):</label>
					<input type=\"text\" name=\" value=\"Euro:  4800,98\" style=\"border-radius: 8px; width:300px; height: 50px; border: 2px solid; font-weight: bold;font-size: 25px; \"/>
				</div>
				<div style=\"margin:auto auto 20px 20%;\">
					<label style=\"font-weight: bold;font-size: 25px;\">(*)Cassa:</label>
					<input type=\"text\" name=\" value=\"Euro: 6,37\" style=\"border-radius: 8px; width:300px; height: 50px; border:solid; font-weight: bold;font-size: 25px; \"/>
					<label style=\"font-weight: bold;font-size: 25px;\">Contributi:</label>
					<input type=\"text\" name=\" value=\"Euro:    162,95\" style=\"border-radius: 8px; width:300px; height: 50px; border:solid; font-weight: bold;font-size: 25px; \"/>
				</div>
				<div style=\"margin:auto auto 20px 20%;\">
					<label style=\"font-weight: bold;font-size: 25px;margin-right: 120px;\">Netto corrisposto (imponibile fiscale):</label>
					<input type=\"text\" name=\" value=\"Euro:  4638,03\" style=\"border-radius: 8px; background-color: rgba(50, 150, 30, 0.5); width:300px; height: 50px; border:solid; font-weight: bold;font-size: 25px; \"/>
				</div>
				<div style=\"margin:auto auto 20px 20%;\">
					<label style=\"font-weight: bold;font-size: 25px; margin-right: 120px;\">Netto corrisposto (imponibile fiscale): </label>
					<input type=\"text\" name=\" value=\"Euro:  0,00\" style=\"border-radius: 8px; width:300px; height: 50px; border:solid; font-weight: bold;font-size: 25px; \"/>
				</div>
				<div style=\"margin:auto auto 20px 20%;\">
					<label style=\"font-weight: bold;font-size: 25px;margin-right: 120px;\">Netto corrisposto (imponibile fiscale):</label>
					<input type=\"text\" name=\" value=\"Euro:  0,00\" style=\"border-radius: 8px; width:300px; height: 50px; border:solid; font-weight: bold;font-size: 25px; \"/>
				</div>
			</form>
			<span>(*) Cassa Colf ed Ebilcoba, non essendo esclusivamente casse sanitarie (coprono anche finalità di assistenza contrattuale, etc.) non contribuiscono alla diminuzione dell'imponibile fiscale.</span>

			<p>Il / La lavoratore/trice dovra'' procedere in termini autonomi agli adempimenti fiscali relativi alle competenze economiche certificate con la presente dichiarazione in quanto il sottoscritto, per la propria qualità di soggetto privato, datore di lavoro domestico, non è sostituto d''imposta.</p>
		</div>
		<div style=\"margin-bottom: 20px;\">
			<div style=\"width:250px;float: left;padding-bottom: 5px; border:none; border-bottom:2px solid!important;\">BOLOGNA</div> , 
				<div style=\"border:none; border-bottom:2px solid!important;margin-left: 50px;width:250px;float: left;padding-bottom: 5px;\">28/02/2018</div>
				<div style=\"border:none; border-bottom:2px solid!important;margin-left: 50px;width:250px;float: left;padding-bottom: 30px;\">Il datore di lavoro</div>
			</div> 
		</div>
		<div style=\"clear:both;\"></div>
		<hr style=\"border:2px solid black;margin: 40px auto 50px auto;\">
		<div>
			<div style=\"float:left;\"><span style=\"font-weight:bold;font-size: 17px;\"> Per ricevuta:</span> <span style=\"margin-left: 150px;\">data:</span></div> <div style=\"width:20%; border:none; float:left; height: 22px; border-bottom: 2px solid!important; margin-left: 15px;\"></div>
			<div style=\"clear:both;\"></div>
			<div style=\"float:left; margin: 20px auto auto 250px;\">firma:</div> <div style=\"width:20%; border:none; float:left; height: 40px!important;margin-left:15px; border-bottom: 2px solid!important;\"></div>
		</div>
	</div>";
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
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

	/*function testPdf(){
		$pdf = \App::make('dompdf.wrapper');
		$pdf->loadHTML($this->webcolf());
		$repTemplate = DB::table('report_template')->where('report_template_name', 'default')->get()[0];
		return $pdf->stream();
	}*/


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

	function oneri(){
		return "<div style=\"padding-left:35px; font-size:14px; padding-right:35px;text-align:justify;\">
		<div style=\"width: 40%; margin-top:50px;\">
			<span style=\"font-weight: bold;\">CAVALLETTO EDOARDINO</span><br>
			VIA SALGARI, 19<br>
			40127 BOLOGNA (BO)<br>
			<div style=\"margin-top: 50px;\"></div>
		</div>
		<div style=\"width: 30%;margin:50px 30% 0px 40%;\">
			<b>BOLOGNA, 6/11/12</b>
		</div>
		<div style=\"width: 100%; margin-top: 50px;font-style:justify;\">
			<span style=\" font-size: 18px;\">Oggetto: <b>DICHIARAZIONE ONERI DEDUCIBILI </b></span><br>
			<div style=\"height: 20px!important;\"></div>
			<span style=\">Facendo seguito agli accordi verbali intercorsi, si conferma la Sua assunzione ed in base al comma 3 dell'art. 9-bis L. 608/96 e al D.Lgs. 152/97, si specificano gli aspetti caratterizzanti il Suo rapporto di lavoro:</span>
		</div>
		
		<div style=\"margin-top: 18px;\">
			<p>Ai sensi dell'art. 10 del testo unico delle imposte dirette, si dichiara che i contributi deducibili dalla dichiarazione dei redditi dell'anno 2018, per contributi corrisposti nell'anno a collaboratori domestici, sono pari ad Euro 893,00</p>
			<p>In segno di accettazione delle condizioni sopra espresse, si chiede la restituzione di una copia della presente dopo averla sottoscritta.</p>
			<p>Altri 23,50 Euro sono stati versati per contributi di assistenza sanitaria Cassa Colf. Quest'ultimi non sono però deducibili e non sono stati ricompresi nella somma di cui sopra in quanto CassaColf non ha natura esclusivamente di cassa sanitaria ma ricopre anche scopi di assistenza contrattuale ed altre finalità bilaterali.</p>
			<p> Nell'importo sopra indicato sono esclusi i contributi a carico del collaboratore. In fede. </p>
		</div>
		<div style=\"margin-top: 40px;\">	
			<div style=\"width:150px;float: right;padding-bottom: 40px; border:none; border-bottom:1px solid!important;\">Il Collaboratore</div>
		</div>
		<div style=\"margin-top: 150px;\">	
			<div style=\"width:300px;text-align: left;padding-bottom: 40px; border:none; border-bottom:1px solid!important;\"></div>
			<h6>Dettaglio contabile deduz: Mese 	Importo </h6>
			<ul style=\"margin-left:-39px; list-style-type: none;\">
				<li>NARUDIA IZEHIESE	76,00 </li>
				<li>NARUDIA IZEHIESE	95,00  </li>
				<li>NARUDIA IZEHIESE	76,00 </li>
				<li>NARUDIA IZEHIESE	95,00  </li>
				<li>NARUDIA IZEHIESE	76,00 </li>
				<li>NARUDIA IZEHIESE	95,00  </li>
				<li>NARUDIA IZEHIESE	76,00 </li>
				<li>NARUDIA IZEHIESE	95,00 </li>
				<li>NARUDIA IZEHIESE	76,00 </li>
				<li>NARUDIA IZEHIESE	95,00  </li>
				<li>NARUDIA IZEHIESE	76,00 </li>
			</ul>
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
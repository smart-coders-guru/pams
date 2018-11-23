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
				return $dataArr['template'];
				//$repTemplate = DB::table('report_template')->where('report_template_name', $dataArr['template'])->get()[0];
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
		//$pdf = \App::make('dompdf.wrapper');
		//$pdf->loadHTML($this->webcolf());
		$repTemplate = DB::table('report_template')->where('report_template_name', 'default')->get()[0];
		return $repTemplate->report_template_name;//$pdf->stream();
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

	function webcolf(){
		return "<div style=\"padding-left:35px; font-size:14px; padding-right:35px;text-align:justify;\">
	<div style=\"width: 40%; margin-top:50px;\">
		<span style=\"font-weight: bold;\">CAVALLETTO EDOARDINO</span><br>
		VIA SALGARI, 19<br>
		40127 BOLOGNA (BO)<br>
		<div style=\"margin-top: 50px;\"></div>
		<br>
		<span style=\"\">BOLOGNA, 6 Luglio 2017</span><br>
	</div>
	<div style=\"width: 30%;margin:50px 30% 0px 40%;\">
		NARUDIA IZEHIESE<br>
		VIA DEI MILLE, 2<br>
		40121 BOLOGNA (BO)<br>
	</div>
	<div style=\"width: 100%; margin-top: 50px;font-style:justify;\">
		<span style=\" font-size: 18px;\">OGGETTO: <b>#object_1#</b></span><br>
		<div style=\"height: 20px!important;\"></div>
		<span style=\"\">Facendo seguito agli accordi verbali intercorsi, si conferma la Sua assunzione ed in base al comma 3 dell'art. 9-bis L. 608/96 e al D.Lgs. 152/97, si specificano gli aspetti caratterizzanti il Suo rapporto di lavoro:</span>
	</div>
	<div style=\"margin-top: 18px;\">
		<span style=\"font-weight: bold;\">INIZIO DEL RAPPORTO:</span><br>
		<span>L'inizio del rapporto viene stabilito per il giorno 05/07/2017. Oltre tale data, in caso di mancata presa di servizio, indipendente dal motivo, il presente contratto dovrà essere considerato decaduto.</span>
	</div>
	<div style=\"margin-top: 18px;\">
		<span style=\"font-weight: bold;\">LUOGO DI LAVORO:</span><br>
		<span>BOLOGNA (BO) in VIA SALGARI, 19. Potrebbe esserle richiesto di effettuare le sua prestazioni presso altre sedi, in caso di trasferimento anche temporaneo del Suo datore di lavoro, trasferimento che, salvo congruo preavviso, dichiara di accettare.</span>
	</div>
	<div style=\"margin-top: 18px;\">
		<span style=\"font-weight: bold;\">DURATA DEL RAPPORTO:</span><br>
		<span>A tempo indeterminato</span>
	</div>
	<div style=\"margin-top: 18px;\">
		<span style=\"font-weight: bold;\">ORARIO DI LAVORO:</span><br>
		<span>25 ore settimanali. Le prestazioni lavorative non si svolgono in regime di convivenza. L'impegno lavorativo avrà la seguente, sottoelencata, distribuzione giornaliera:</span>
		<ul>
			<li>Lunedi: 5 ore</li>
			<li>Lunedi: 5 ore</li>
			<li>Lunedi: 5 ore</li>
			<li>Giovedi: 5 ore</li>
			<li>Giovedi: 5 ore</li>
		</ul>
		<span>A norma del combinato disposto dall'art. 6 e 14 del CCNL applicato si precisa che la mezza giornata di riposo
aggiuntiva è stabilita di sabato.</span>
	</div>
	<div style=\"margin-top: 18px;\">
		<span style=\"font-weight: bold;\">DOMICILIO E RESIDENZA:</span><br>
		<span>Il domicilio coincide con la residenza ed è correttamente indicato nell'indirizzo di destinazione della presente.</span>
	</div>
	<div style=\"margin-top: 18px;text-align:justify;\">
		<span style=\"font-weight: bold;\">QUALIFICA E MANSIONI:</span><br>
		<span>La collaboratrice verrà inquadrato al livello A. I compiti assegnati saranno di addetto alle pulizie, lavanderia, aiuto di cucina, assitente ad animali domestici, addetto all'annaffiatura e pulizia di aree verdi. In riferimento al livello di inquadramento la collaboratrice dichiara di avere già svolto -1 mesi, compresi quelli presso precedenti datori di lavoro, mansioni di pulizia e altri compiti comuni inquadrati al livello A (prima del 01.03.2007 nella ex 3a categoria) del CCNL applicato.</span>
	</div>
	<div style=\"margin-top: 18px;\">
		<span style=\"font-weight: bold;\">OBBLIGO DI FEDELTA':</span><br>
		<span>La Sig.ra NARUDIA IZEHIESE si impegna ad osservare le direttive ricevute dal proprio datore di lavoro e le regole della casa ricevute all'inizio del rapporto di lavoro. La Legge prevede l'obbligo di non divulgare informazioni, affari, notizie riguardanti il datore di lavoro o altre notizie apprese nello svolgimento delle proprie mansioni, facendone un uso in modo da poter recare pregiudizio al datore di lavoro stesso e/o a terzi.</span>
	</div>
	<div style=\"margin-top: 18px;\">
		<span style=\"font-weight: bold;\">TENUTA DI LAVORO:</span><br>
		<span>Non è prevista una particolare tenuta di lavoro, salvo un vestire sobrio e dignitoso, adatto a svolgere le mansioni descritte.</span>
	</div>
	<div style=\"margin-top: 18px;\">
		<span style=\"font-weight: bold;\">SPAZIO DOVE RIPORRE I PROPRI OGGETTI PERSONALI:</span><br>
		<span>Al momento della presa di servizio verrà indicato uno spazio dove riporre propri oggetto personali.</span>
	</div>
	<div style=\"margin-top: 18px;\">
		<span style=\"font-weight: bold;\">PERIODO DI PROVA:</span><br>
		<span>Le parti decidono di non applicare il periodo di prova.</span>
	</div>
	<div style=\"margin-top: 18px;\">
		<span style=\"font-weight: bold;\">FERIE:</span><br>
		<span>26 giorni lavorativi. Il valore di ogni giornata andrà riproporzionato per l'orario di lavoro effettivamente svolto. Il periodo di godimento delle ferie annuali verrà concordato di anno in anno e fissato in accordo tra le parti con congruo preavviso.</span>
	</div>
	<div style=\"margin-top: 18px;\">
		<span style=\"font-weight: bold;\">PREAVVISO DI LICENZIAMENTO/DIMISSIONI:</span><br>
		<span>15 giorni di calendario per anzianità fino a 5 anni. 30 giorni di calendario per anzianità superiori. I suddetti termini sono ridotti del 50% nel caso di dimissioni da parte del lavoratore.</span>
	</div>
	<div style=\"margin-top: 18px;\">
		<span style=\"font-weight: bold;\">TRATTAMENTO ECONOMICO:</span><br>
		<span>Corrispondente alle disposizioni contrattuali per il livello assegnato come da importi sotto descritti:</span><br>
		Paga base: ............ 4,54<br>
		Paga base: ............ 4,54<br>
		<hr>
		Totale: ............... 7,00
	</div>
	<div style=\"margin-top: 18px;\">
		<p>Per quanto non specificato nella presente le parti dichiarano di voler fare riferimento alle norme previste dal contratto collettivo nazionale di lavoro per i prestatori di lavoro domestico stipulato il 21 maggio 2013 e a tutte le norme di Legge vigenti.</p>
		<p>In segno di accettazione delle condizioni sopra espresse, si chiede la restituzione di una copia della presente dopo averla sottoscritta.</p>
		<p>Certi di poter contare nella Sua migliore collaborazione, porgiamo distinti saluti.</p>
	</div>
	<div style=\"margin-top: 18px;\">
		<div style=\"\">
			<div style=\"width:250px;float: left;padding-bottom: 30px; border:none; border-bottom:2px solid!important;\">Il Collaboratore</div>
			<div style=\"border:none; border-bottom:2px solid!important;margin-left: 50px;width:250px;float: left;padding-bottom: 30px;\">Il Collaboratore</div>
		</div>
		<div style=\"clear: both; padding-top:30px;margin-bottom: 50px;\">
			<p>
				Inoltre La informiamo che, ai sensi dell'art. 10 del D.Lgs. 196 del 30 giugno 2003, i suoi dati personali saranno utilizzati per una corretta gestione del rapporto stesso, dunque per le comunicazioni obbligatorie agli enti di previdenza e assistenza, rapporti con l'amministrazione finanziaria, istituti di credito ed eventuali consulenti familiari. Il trattamento dei predetti dati avverrà mediante strumenti manuali, informatici e telematici, comunque idonei a garantire la sicurezza e la riservatezza dei dati stessi. In base all'art. 13 del D.Lgs. 196/2003 lei ha specifici diritti, in particolare può ottenere la conferma circa l'esistenza o meno di dati che la riguardano, conoscerne l'origine, la finalità, chiedere la cancellazione o la trasformazione o il blocco dei dati trattati in violazione di legge.
			</p>
			<p>
				Il sottoscritto, ai sensi della legge sopracitata, autorizza e acconsente espressamente che i dati raccolti e in particolare quelli considerati sensibili possano costituire oggetto di trattamento e comunicazione per le finalità della corretta gestione del rapporto di lavoro, degli obblighi di legge e contrattuali.
			</p>
		</div>
		<div style=\"width:250px;float: left;padding-bottom: 30px; border:none; border-bottom:2px solid!important;\">Il Collaboratore</div>
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
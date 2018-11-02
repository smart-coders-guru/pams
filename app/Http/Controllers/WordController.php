<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class WordController extends Controller
{
        public function generateDocx()

    {

	        $phpWord = new \PhpOffice\PhpWord\PhpWord();


	        $section = $phpWord->addSection();


	        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,

	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo

	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse

	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non

	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";


	      // $section->addImage("http://itsolutionstuff.com/frontTheme/images/logo.png");

	        $section->addText($description);


	        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

	        try {

	            $objWriter->save(storage_path('helloWorld.docx'));
 
	        } catch (Exception $e) {

	        }


	        return response()->download(storage_path('helloWorld.docx'));

    }

    public function create(){
    	
    	return view('createdocument');
    }

   public function store(Request $request)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $text = $section->addText($request->get('name'));
        $text = $section->addText($request->get('email'));
        $text = $section->addText($request->get('number'),array('name'=>'Arial','size' => 20,'bold' => true));
        //$section->addImage("./images/fabrice10.png");  
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
        $objWriter->save('Appdividen.docx');
        return response()->download(public_path('Appdividen.docx')); 
    }
}

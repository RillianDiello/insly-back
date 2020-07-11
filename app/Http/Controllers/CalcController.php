<?php

namespace App\Http\Controllers;

use App\Models\Calc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Response;
use DateTime;


class CalcController extends Controller
{

    public function makeCalc(Request $request){
        $params = $request->all();

        $validator = Validator::make(
            $params,
            [
                'valueOfCar' => 'required|numeric|between:100,100000',
                'taxPercent'     => 'required|numeric|between:0,100',
                'numInstalments'      => 'required|numeric|between:1,12',
            ]);
        $calculate['errors'] = [];
        if ($validator->fails())
        {           
            $calculate['errors'] = $validator->errors();
            $calculate['colunms'] = [];
            $calculate['totais'] = [];
            $calculate['instalments'] = [];
            return response()->json($calculate);
        }
        
        $valueOfCar = (float) $params['valueOfCar'];
        $taxPercent = (float) $params['taxPercent'];
        $numInstalments = (int) $params['numInstalments'];

      
        $calc = new Calc();

        $calc->setValueOfCar($valueOfCar);
        $calc->setTaxPercent($taxPercent);
        $calc->setNumInstalments($numInstalments);
        $calc->CalculateInsuranceMethod();

        $calculate['colunms'] = $calc->colValues();     
        $calculate['totais'] = $calc->getTotalsPolicy();
        $calculate['instalments'] = $calc->getInstaments();
        
        return response()->json($calculate);                
       
    }

   
   
}


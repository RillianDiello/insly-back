<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;

class CalcController extends Controller
{

    public function makeCalc(Request $request){
        $params = $request->all();

        

        $valueOfCar = (float) $params['valueOfCar'];
        $taxPercent = (float) $params['taxPercent'];
        $numInstalments = (int) $params['numInstalments'];

        $calc = new Calc();
        $calc->setValueOfCar($valueOfCar);
        $calc->setTaxPercent($taxPercent);
        $calc->setNumInstalments($numInstalments);
        $calc->CalculateInsuranceMethod();

        $calculate['colunms'] = $calc->colValues();
        // $calculate['titles'] = $calc->getTitles();
    
        $calculate['totais'] = $calc->getTotalsPolicy();
        $calculate['instalments'] = $calc->getInstaments();
        
        return response()->json($calculate);                
       
    }

   
   
}

class Calc
{
    private $valueOfCar;
    private $taxPercent;
    private $numInstalments;
    private $calculate;

    public function __construct(){
        $this->valueOfCar = 0;
        $this->taxPercent = 0;
        $this->numInstalments = 0;
        $this->calculate = new CalculateInsurance();
    }


    public function setValueOfCar($valueOfCar){
        $this->valueOfCar= $valueOfCar;
    }
    public function setTaxPercent($taxPercent){
        $this->taxPercent= $taxPercent;
    }
    public function setNumInstalments($numInstalments){
        $this->numInstalments= $numInstalments;
    }

    public function getValueOfCar(){
        return $this->valueOfCar;;
    }
    public function getTaxPercent(){
        return $this->taxPercent;;
    }
    public function getNumInstalments(){
        return $this->numInstalments;;
    }

    public function getCalculate(){
        return $this->calculate;
    }

    public function CalculateInsuranceMethod(){
        
        $this->calculate->setBasePremium($this->getValueOfCar());        
        $this->calculate->setComission();
        $this->calculate->setTax($this->getTaxPercent());
        $this->calculate->setTotalCost();
        $this->calculate->setArrayInstalments($this->getNumInstalments());
     
    }

    public function getTotalsPolicy(){
        return [            
            'base_premium' => number_format($this->calculate->getBasePremium(),2, '.', ''),
            'comission' => number_format($this->calculate->getComission(),2, '.', ''),
            'tax' => number_format($this->calculate->getTax(),2, '.', ''),
            'total_cost' => number_format($this->calculate->getTotalCost(),2, '.', '')
        
        ];
    }
    public function colValues(){
        return [        
        'base_premium' => 'Base Premiun (%)',  
        'comission' => 'Commision (%)',
        'tax' => 'Tax (%)',
        'total_cost' => 'Total Cost'
        ];
    }


    public function getInstaments(){
        
        $retorno = [];
        foreach ($this->calculate->getArrayInstalments() as $key => $value) {

            $retorno[$key] = $value->getCalculateInstalment($key);
            
        }

        return $retorno;
    }

    public function getTitles(){
        $titles = [
            '#',  'Policy'
        ];

        foreach ($this->calculate->getArrayInstalments() as $key => $value) {
            array_push($titles, 'Instalment' . $key);
        }

        echo $titles;
    }

   
}


class CalculateInsurance 
{
    private $base_premium;
    private $comission;
    private $tax;
    private $totalCost;
    private $arrayInstalments;   

    const COMISSIONBASE = 17.0;
    const BASEPRICE = 11.0;
    const BASEPRICEFRIDAY = 13.0;
    const FRIDAY = 5;
    

    public function __construct(){
        $this->base_premium = 0;
        $this->comission = 0;
        $this->tax = 0;
        $this->totalCost = 0;
        $this->arrayInstalments=[];    
    }

    public function setBasePremium($value)
    {     
        if($this->checkWeekDay() && $this->checkInterval())
        {
            $this->base_premium = $value * (self::BASEPRICEFRIDAY/100);
        }else{
            $this->base_premium = $value * (self::BASEPRICE/100);
        }
        
    }

    public function setComission(){
        $this->comission = $this->getBasePremium() * (self::COMISSIONBASE/100);
    }

    public function setTax($tax){
        $this->tax = $this->getBasePremium() * ($tax/100);
    }

    public function setTotalCost(){
        $this->totalCost = $this->getBasePremium() + $this->getComission() + $this->getTax();
    }

    public function setArrayInstalments($numInstalments){
        $mediumValueBase = $this->getBasePremium()/ $numInstalments;               
        $mediumValueComission = $this->getComission()/ $numInstalments;
        $mediumValueTax = $this->getTax()/ $numInstalments;

        for ($i=0; $i < $numInstalments; $i++) {
            $calcInta  = new CalculateIntalment();
            $calcInta->setBaseIntalmen($mediumValueBase);
            $calcInta->setComission($mediumValueComission);
            $calcInta->setTaxIntalmen($mediumValueTax);
            $calcInta->setTotalInstalment();
            array_push($this->arrayInstalments,$calcInta);           
        }

    }
    
    public function getBasePremium(){
        return $this->base_premium;;
    }
    public function getComission(){
        return $this->comission;;
    }
    public function getTax(){
        return $this->tax;
    }
    public function getTotalCost(){
        return $this->totalCost;
    }

    public function getArrayInstalments(){
        return $this->arrayInstalments;
    }

    private function checkInterval() {
        $now = new DateTime('now');
        $start = new DateTime('08:25:00');
        $end = new DateTime('12:25:00');

        if ( $start <= $now && $now <= $end ) {
            return true;
        }
        return false;

    } 
    private function checkWeekDay(){
        $now = new DateTime('now');
        $day = date('w', strtotime($now->format('Y-m-d')));
        if($day == self::FRIDAY ) {
            return true;
        }
        return false;

    }
    

}

class CalculateIntalment 
{
    private $baseIntalmen;
    private $comissionIntalmen;
    private $taxIntalmen;
    private $totalInstalment;

    public function __construct(){
        $this->baseIntalmen = 0;
        $this->comissionIntalmen = 0;
        $this->taxIntalmen = 0;           
    }

    public function setBaseIntalmen($value)
    {     
        $this->baseIntalmen = $value;
    }

    public function setComission($value){
        $this->comissionIntalmen = $value;
    }

    public function setTaxIntalmen($value){
        $this->taxIntalmen = $value;
    }

    public function setTotalInstalment(){
        $this->totalInstalment = $this->getBaseIntalmen() + $this->getComissionIntalmen()
                + $this->getTaxIntalmen();
    }
    
    public function getBaseIntalmen(){
        return $this->baseIntalmen;;
    }
    public function getComissionIntalmen(){
        return $this->comissionIntalmen;;
    }
    public function getTaxIntalmen(){
        return $this->taxIntalmen;;
    }

    public function getTotalInstalment(){
        return $this->totalInstalment;
    }


    public function getCalculateInstalment($key){
        return [            
            'base_premium' => number_format($this->getBaseIntalmen(),2, '.', ''),
            'comission' => number_format($this->getComissionIntalmen(),2, '.', ''),
            'tax' => number_format($this->getTaxIntalmen(),2, '.', ''),
            'total_cost' => number_format($this->getTotalInstalment(),2, '.', '')
        ];
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Calc extends Model
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
            'policy' => 'Polcy'          ,      
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

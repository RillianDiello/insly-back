<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalculateIntalment extends Model
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

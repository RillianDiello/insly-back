<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class CalculateInsurance extends Model
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
        $mediumValueBase = round(($this->getBasePremium()/ $numInstalments),2);               
        $mediumValueComission = round(($this->getComission()/ $numInstalments),2);
        $mediumValueTax = round(($this->getTax()/ $numInstalments),2);

      
        for ($i=0; $i < $numInstalments; $i++) {
            $calcInta  = new CalculateIntalment();
            if($i == ($numInstalments -1)){
                
                $calcInta->setBaseIntalmen($this->getBasePremium() - ($i * $mediumValueBase));
                $calcInta->setComission($this->getComission() - ($i * $mediumValueComission));
                $calcInta->setTaxIntalmen($this->getTax() - ($i * $mediumValueTax));
                $calcInta->setTotalInstalment();
            }else{
                $calcInta->setBaseIntalmen($mediumValueBase);
                $calcInta->setComission($mediumValueComission);
                $calcInta->setTaxIntalmen($mediumValueTax);
                $calcInta->setTotalInstalment();
            }
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

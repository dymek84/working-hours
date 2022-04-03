<?php

namespace App\WorkingHours;

use App\Calculations;
use DateTime;

class WorkingHours
{
    
    /**
     * startDateAndTime
     *
     * @var unix timestamp
     */
    protected int $startDateAndTime;
    
    /**
     * stopDateAndTime
     *
     * @var unix timestamp
     */
    protected int $stopDateAndTime;

     
    /**
     * exceptions - days where start or finish time was changed   
     *
     * @var array
     */
    protected $exceptions = array();
    
    /**
     * holidays - days when comapny is close 
     *
     * @var array
     */
    protected  $holidays = array();    
    
    /**
     * weekendArray
     *
     * @var array
     */
    protected $weekendArray = array("Saturday", "Sunday");
    
    protected DateTime $finishTime;
    /**
     * StartCalculation
     *
     * @param  mixed $startDateAndTime
     * @param  mixed $stopDateAndTime
     * @param  mixed $exceptions
     * @param  mixed $holidays
     * @param  mixed $weekendArray
     * @return void
     */
    public static function StartCalculation(int $startDateAndTime,int $stopDateAndTime,$exceptions,$holidays,$weekendArr) 
    {
      
       
        
    }
}



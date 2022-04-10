<?php

class Cdate
{

    public $date;
    public $startHour;
    public $finishHour;

    public function __construct($date, $startHour, $finishHour)
    {
        $this->date = $date;
        $this->startHour = $startHour;
        $this->finishHour = $finishHour;
    }


    public function getDate()
    {
        return $this->date;
    }
    public function getStart()
    {
        return $this->startHour;
    }
    public function getFinish()
    {
        return $this->finishHour;
    }
    public function isSaturday()
    {
        if (date("l", strtotime($this->date)) == "Saturday") {
            return true;
        } else {
            return false;
        }
    }
    public function isSunday()
    {
        if (date("l", strtotime($this->date)) == "Saturday") {
            return true;
        } else {
            return false;
        }
    }
}

$newDate = new Cdate("", "", "");

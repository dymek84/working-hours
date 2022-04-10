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
        if (date("l", strtotime($this->date)) == "Sunday") {
            return true;
        } else {
            return false;
        }
    }
    public function getWeekDayName()
    {
        return date("l", strtotime($this->date));
    }

    public function getDuration()
    {
        return (strtotime($this->finishHour) - strtotime($this->startHour)) / 60 / 60;
    }
    public function getBreaks()
    {
        return calculateBreaks($this);
    }

    public function getDurationWithoutBreaks()
    {
        return $this->getDuration() - $this->getBreaks();
    }
}
$test = new Cdate("2022-04-02", "07:00:00", "16:30:00");
echo $test->getDurationWithoutBreaks();

function calculateBreaks($Cdate)
{
    $startTime = ($Cdate->getStart() == "09:30:00" ? "10:00:00" : $Cdate->getStart() == "12:30:00") ? "13:00:00" : $Cdate->getStart();
    $stopTime = $Cdate->getFinish();
    $firstBreak = "09:30:00";
    $secondBreak = "12:30:00";
    if ($startTime <= $firstBreak && $stopTime <= $firstBreak || $startTime >= $secondBreak && $stopTime >= $secondBreak || $startTime >= $firstBreak && $stopTime <= $secondBreak) {
        return 0;
    } elseif (($startTime <= $firstBreak && $stopTime >= $firstBreak && $stopTime <= $secondBreak) || ($startTime >= $firstBreak && $startTime <= $secondBreak && $stopTime >= $secondBreak)) {
        return 0.5;
    } elseif ($startTime <= $firstBreak && $stopTime >= $secondBreak) {
        return 1;
    } else {
        return 999;
    }
}

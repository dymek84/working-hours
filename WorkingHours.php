<?php
require "Cdate.php";
function newCalculation($startDateAndTime, $stopDateAndTime, $exceptions, $HollidayArray, $FinishTime)
{
    $iDateFrom = strtotime($startDateAndTime);
    $iDateTo = strtotime($stopDateAndTime);
    $double_array = array();
    $CdateArray = array();
    if (date('Y-m-d', $iDateFrom) == date('Y-m-d', $iDateTo)) {
        $StartDateExplode = explode(" ", $startDateAndTime);
        $StopDateExplode = explode(" ", $stopDateAndTime);
        $newDate1 = new Cdate(date('Y-m-d', $iDateFrom), empty($StartDateExplode[1]) ? "07:00:00" : $StartDateExplode[1], empty($StopDateExplode[1]) ? $FinishTime : $StopDateExplode[1]);
        array_push($CdateArray, $newDate1);
    } else {
        if ($iDateTo >= $iDateFrom) {
            array_push($double_array, date('Y-m-d H:i:s', $iDateFrom)); // first entry
            $StartDateExplode = explode(" ", $startDateAndTime);
            $StopDateExplode = explode(" ", $stopDateAndTime);
            $newDate1 = new Cdate(date('Y-m-d', $iDateFrom), empty($StartDateExplode[1]) ? "07:00:00" : $StartDateExplode[1], $FinishTime);
            array_push($CdateArray, $newDate1);
            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                if (date('Y-m-d', $iDateFrom) == date('Y-m-d', $iDateTo)) {
                    array_push($double_array, date('Y-m-d H:i:s', $iDateTo));
                    $newDate2 = new Cdate(date('Y-m-d', $iDateTo), "07:00:00", empty($StopDateExplode[1]) ? "" : $StopDateExplode[1]);
                    array_push($CdateArray, $newDate2);
                } elseif ($iDateFrom < $iDateTo) {
                    array_push($double_array, date('Y-m-d', $iDateFrom));
                    $newDate3 = new Cdate(date('Y-m-d', $iDateFrom), "07:00:00", $FinishTime);
                    array_push($CdateArray, $newDate3);
                }
            }
        }
    }
    $days_array = array();
    foreach ($CdateArray as $day) { // if exception day is holiday day ( means somebady work in holiday w have to add this day to array)
        if (checkIfIsInCdateArray($exceptions, $day->getDate()) && in_array($day->getDate(), $HollidayArray)) {
            array_push($days_array, getOneDayFromArrayCdate($exceptions, $day->getDate()));
        } elseif (!in_array($day->getDate(), $HollidayArray)) {
            array_push($days_array, $day);
        }
    }
    //return $days_array;

    $arrayWithDeductedWeekend = array();
    if (!exceptionCheck("Saturday", $exceptions)) { //if there is no saturday we have to deduct saturday and sunday from array
        foreach ($days_array as $days) {
            if (!$days->isSaturday() || !$days->isSunday()) {
                array_push($arrayWithDeductedWeekend, $days);
            }
        }
    } else {
        foreach ($days_array as $days) { //if we have saturday in exception (means that somebody work in saturday and put start/stop time into exceptions) we have to add start/stop time for saturday and deduct sunday
            if ($days->isSaturday()) {
                foreach ($exceptions as $exc) {
                    if ($exc->getDate() == $days->getDate()) {
                        array_push($arrayWithDeductedWeekend, $exc);
                    }
                }
            } elseif (!$days->isSunday()) {
                array_push($arrayWithDeductedWeekend, $days);
            }
        }
    }
    return $arrayWithDeductedWeekend;
    $arraWithDeductedWeekendHolidays = array();
    foreach ($arrayWithDeductedWeekend as $days) {
        if (checkIfIsInCdateArray($exceptions, $days->getDate())) {
            array_push($arraWithDeductedWeekendHolidays, getOneDayFromArrayCdate($exceptions, $days));
        }
    }
}

function checkIfIsInCdateArray($Cdaterray, $date)
{
    foreach ($Cdaterray as $Cdate) {
        if ($Cdate->getDate() == $date) {
            return true;
        } else {
            return false;
        }
    }
}
function getOneDayFromArrayCdate($array, $date)
{
    foreach ($array as $Cdate) {
        if ($Cdate->getDate() == $date) {
            return $Cdate;
        } else {
            return "";
        }
    }
}

function exceptionCheck($dayToCheck, $exceptionArray)
{
    if (is_array($exceptionArray) || is_object($exceptionArray)) {
        foreach ($exceptionArray as $day) {
            return $day->getWeekDayName() == $dayToCheck ? true : false;
        }
    }
}

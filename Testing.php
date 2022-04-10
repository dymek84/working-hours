<?php
require "Cdate.php";
///$exceptionsa = array(["2022-04-01 08:00:00"]);
//foreach ($exceptionsa as $item) {
//    $item_array = explode(" ", $item);
//   $date = $item_array[0];
//   $time = $item_array[1];
//}



$exceptionsa = array("2022-04-02 07:30:55", "2022-03-29 07:30:00",);
$hollidays = array("2022-04-01",);
print_r(newCalculation('22-03-28 07:30:00', '22-04-04 14:30:00', $exceptionsa, $hollidays)) . "\r\n";


function newCalculation($startDateAndTime, $stopDateAndTime, $exceptions, $HollidayArray)
{
    $iDateFrom = strtotime($startDateAndTime);
    $iDateTo = strtotime($stopDateAndTime);
    $double_array = array();
    $CdateArray = array();
    if (date('Y-m-d', $iDateFrom) == date('Y-m-d', $iDateTo)) {
        $item_array = explode(" ", $startDateAndTime);
        $item_array2 = explode(" ", $stopDateAndTime);
        $newDate1 = new Cdate(date('Y-m-d', $iDateFrom), empty($item_array[1]) ? "" : $item_array[1], empty($item_array2[1]) ? "" : $item_array2[1]);
        array_push($CdateArray, $newDate1);
    } else {

        if ($iDateTo >= $iDateFrom) {
            array_push($double_array, date('Y-m-d H:i:s', $iDateFrom)); // first entry
            $item_array = explode(" ", $startDateAndTime);
            $item_array2 = explode(" ", $stopDateAndTime);
            $newDate1 = new Cdate(date('Y-m-d', $iDateFrom), empty($item_array[1]) ? "" : $item_array[1], "");
            array_push($CdateArray, $newDate1);
            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                if (date('Y-m-d', $iDateFrom) == date('Y-m-d', $iDateTo)) {
                    array_push($double_array, date('Y-m-d H:i:s', $iDateTo));
                    $newDate2 = new Cdate(date('Y-m-d', $iDateTo), "", empty($item_array2[1]) ? "" : $item_array2[1]);
                    array_push($CdateArray, $newDate2);
                } elseif ($iDateFrom < $iDateTo) {
                    array_push($double_array, date('Y-m-d', $iDateFrom));
                    $newDate3 = new Cdate(date('Y-m-d', $iDateFrom), "", "");
                    array_push($CdateArray, $newDate3);
                }
            }
        }
    }
    $test =  sizeof($CdateArray);
    echo $test;
    //  return $CdateArray;
    $days_array = array();
    foreach ($CdateArray as $day) {
        //$date = 
        $timestampofday = strtotime($day->getDate());
        if (!in_array(date("Y-m-d", $timestampofday), $HollidayArray)) {
            array_push($days_array, $day);
        }
    }
    //return $days_array;

    $arrayWithDeductedWeekend = array();
    if (!exceptionCheck("Saturday", $exceptions)) {
        foreach ($days_array as $days) {

            if (!(date("l", strtotime($days->getDate())) == "Saturday") && !(date("l", strtotime($days->getDate())) == "Sunday")) {
                array_push($arrayWithDeductedWeekend, $days);
            }
        }
    } else {
        foreach ($days_array as $days) {

            if (!(date("l", strtotime($days->getDate())) == "Sunday")) {
                if (date("l", strtotime($days->getDate())) == "Saturday") {
                    array_push($arrayWithDeductedWeekend, changeExceptionDate("Saturday", $exceptions));
                } else {
                    array_push($arrayWithDeductedWeekend, $days);
                }
            }
        }
    }
    return $arrayWithDeductedWeekend;
}
function exceptionCheck($dayToCheck, $exceptionArray)
{
    if (is_array($exceptionArray) || is_object($exceptionArray)) {
        foreach ($exceptionArray as $day) {
            return date("l", strtotime($day)) == $dayToCheck ? true : false;
        }
    }
}
function calBreaks($arrayOfCustomDates, $end, $firstBreak, $secondBreak)
{
    $start = $arrayOfCustomDates[0]->getStart();
    $end =  $arrayOfCustomDates[sizeof($arrayOfCustomDates) - 1]->getFinish();
    $stopTime = date("H", strtotime($end));
    $startTime = date("H", strtotime($start));


    if (sizeof($arrayOfCustomDates) == 1) {
        if ($startTime <= $firstBreak && $stopTime <= $firstBreak || $startTime >= $secondBreak && $stopTime >= $secondBreak || $startTime >= $firstBreak && $stopTime <= $secondBreak) {
            return 0;
        } elseif (($startTime <= $firstBreak && $stopTime >= $firstBreak && $stopTime <= $secondBreak) || ($startTime >= $firstBreak && $startTime <= $secondBreak && $stopTime >= $secondBreak)) {
            return 0.5;
        } elseif ($startTime <= $firstBreak && $stopTime >= $secondBreak) {
            return 1;
        } else {
            return 999;
        }
    } elseif (sizeof($arrayOfCustomDates) == 2) {
        if ($startTime <= $firstBreak) { //2
            if ($stopTime <= $firstBreak) {
                return 1;
            } elseif ($stopTime >= $firstBreak && $stopTime <= $secondBreak) {
                return 1.5;
            } elseif ($stopTime >= $secondBreak) {
                return 2;
            }
        } elseif ($startTime >= $firstBreak && $startTime <= $secondBreak) {
            if ($stopTime <= $firstBreak) {
                return 0.5;
            } elseif ($stopTime >= $firstBreak && $stopTime <= $secondBreak) {
                return 1;
            } elseif ($stopTime >= $secondBreak) {
                return 1.5;
            }
        } elseif ($startTime >= $secondBreak) {
            if ($stopTime <= $firstBreak) {
                return 0;
            } elseif ($stopTime >= $firstBreak && $stopTime <= $secondBreak) {
                return 0.5;
            } elseif ($stopTime >= $secondBreak) {
                return 1;
            }
        }
    } else {
        $workdays_number = sizeof($arrayOfCustomDates) - 2;
        if ($startTime <= $firstBreak) { //2
            if ($stopTime <= $firstBreak) {
                return 1 + $workdays_number;
            } elseif ($stopTime >= $firstBreak && $stopTime <= $secondBreak) {
                return 1.5 + $workdays_number;
            } elseif ($stopTime >= $secondBreak) {
                return 2 + $workdays_number;
            }
        } elseif ($startTime >= $firstBreak && $startTime <= $secondBreak) {
            if ($stopTime <= $firstBreak) {
                return 0.5 + $workdays_number;
            } elseif ($stopTime >= $firstBreak && $stopTime <= $secondBreak) {
                return 1 + $workdays_number;
            } elseif ($stopTime >= $secondBreak) {
                return 1.5 + $workdays_number;
            }
        } elseif ($startTime >= $secondBreak) {
            if ($stopTime <= $firstBreak) {
                return 0 + $workdays_number;
            } elseif ($stopTime >= $firstBreak && $stopTime <= $secondBreak) {
                return 0.5 + $workdays_number;
            } elseif ($stopTime >= $secondBreak) {
                return 1 + $workdays_number;
            }
        }
    }
}

<?php
require "Cdate.php";




$exceptionsa = array(new Cdate("2022-04-02", "07:30:55", "16:30:00"), new Cdate("2022-03-29", "07:35:00", "16:30:00"));
$hollidays = array("2022-04-01",);
print_r(newCalculation('22-04-04 07:30:00', '22-04-05 11:30:00', $exceptionsa, $hollidays, "16:30:00")) . "\r\n";
echo calBreaks(newCalculation('22-04-04 07:30:00', '22-04-05 11:30:00', $exceptionsa, $hollidays, "16:30:00"));


function calBreaks($arrayOfCustomDates)
{
    $start = $arrayOfCustomDates[0]->getStart();
    $end =  $arrayOfCustomDates[sizeof($arrayOfCustomDates) - 1]->getFinish();
    $stopTime = date("H", strtotime($end));
    $startTime = date("H", strtotime($start));
    $firstBreak = "09:30:00";
    $secondBreak = "12:30:00";

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

$exceptionsa = array("2022-04-02 07:30:55", "2022-03-29 07:30:00",);
$hollidays = array("2022-04-01",);
print_r(newCalculation('22-04-03 07:30:00', '22-04-08 14:30:00', $exceptionsa, $hollidays)) . "\r\n";


function checkDateAndChangeToTimestamp($date)
{
    if (!is_int($date)) {
        $date = strtotime($date);
    }
    return $date;
}
function newCalculation($startDateAndTime, $stopDateAndTime, $exceptions, $HollidayArray)
{
    $iDateFrom = strtotime($startDateAndTime);
    $iDateTo = strtotime($stopDateAndTime);
    $double_array = array();


    if ($iDateTo >= $iDateFrom) {
        array_push($double_array, date('Y-m-d H:i:s', $iDateFrom)); // first entry


        while ($iDateFrom < $iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            if (date('Y-m-d', $iDateFrom) == date('Y-m-d', $iDateTo)) {
                array_push($double_array, date('Y-m-d H:i:s', $iDateTo));
            } elseif ($iDateFrom < $iDateTo) {
                array_push($double_array, date('Y-m-d', $iDateFrom));
            }
        }
    }

    return $double_array;
}
function createDateRangeArray($startDateAndTime, $stopDateAndTime, $exceptions, $HollidayArray)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.
    // arraySize 1=sameday 2=nextday 2<means days beetwen - 2 (first and last day)
    // could test validity of dates here but I'm already doing
    // that in the main script
    // If not numeric then convert timestamps
    // if (!is_int($startDateAndTime)) {
    //     $startDateAndTime = strtotime($startDateAndTime);
    //  }
    // if (!is_int($stopDateAndTime)) {
    //     $stopDateAndTime = strtotime($stopDateAndTime);
    // }
    //  if (array_key_exists($row['worknumber'], $array)) 
    // {			
    //    $cc += $i;	
    //   $bb += $i;		
    //  $array[$row['worknumber']] += $i;				
    //}
    //else
    // {
    //    $cc += $i;	
    //   $bb += $i;	
    //  $array[$row['worknumber']] = $i;
    //  $array2[$row['worknumber']] = $row['company'];
    // }			

    $aryRange = [];
    $iDateFrom = strtotime($startDateAndTime);
    $iDateTo = strtotime($stopDateAndTime);

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d H:i:s', $iDateFrom)); // first entry
        $start = explode($iDateFrom, " ");

        while ($iDateFrom < $iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            if (date('Y-m-d', $iDateFrom) == date('Y-m-d', $iDateTo)) {
                array_push($aryRange, date('Y-m-d H:i:s', $iDateTo));
            } elseif ($iDateFrom < $iDateTo) {
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
    }


    $arrayWithDeductedWeekend = [];
    if (!exceptionCheck("Saturday", $exceptions)) {
        foreach ($aryRange as $days) {

            if (!(date("l", strtotime($days)) == "Saturday") && !(date("l", strtotime($days)) == "Sunday")) {
                array_push($arrayWithDeductedWeekend, $days);
            }
        }
    } else {
        foreach ($aryRange as $days) {

            if (!(date("l", strtotime($days)) == "Sunday")) {
                if (date("l", strtotime($days)) == "Saturday") {
                    array_push($arrayWithDeductedWeekend, changeExceptionDate("Saturday", $exceptions));
                } else {
                    array_push($arrayWithDeductedWeekend, $days);
                }
            }
        }
    }
    return deductHolidaysFromArray($arrayWithDeductedWeekend, $HollidayArray);

    foreach ($arrayWithDeductedWeekend as $days) {
    }
}
function changeExceptionDate($DayName, $ArrayOfExceptions)
{
    foreach ($ArrayOfExceptions as $dayExc) {
        if (date("l", strtotime($dayExc)) == $DayName) {
            return $dayExc;
        }
    }
}
function exceptionCheck($dayToCheck, $exceptionArray)
{
    if (is_array($exceptionArray) || is_object($exceptionArray)) {
        foreach ($exceptionArray as $day) {
            return date("l", strtotime($day)) == $dayToCheck ? true : false;
        }
    }
}



function deductHolidaysFromArray($arrayOfDates, $arrayOfHolidays)
{
    $days_array = array();

    foreach ($arrayOfDates as $day) {

        $timestampofday = strtotime($day);
        if (!in_array(date("Y-m-d", $timestampofday), $arrayOfHolidays)) {
            $days_array[] = date("Y-m-d H:i:s", $timestampofday);
        }
    }
    return $days_array;
}

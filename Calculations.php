<?php

namespace App\Calculations;

use App\Timei;


function isSameDay($start, $end)
{
    if (date("D", strtotime($start)) == date("D", strtotime($end))) {
        return true;
    } else {
        return false;
    }
}
function isNextDay($start, $end)
{
    $newDate = date("D", strtotime('+1 day', strtotime($start)));
    if ($newDate == date("D", strtotime($end))) {
        return true;
    } else {
        return false;
    }
}
/**
 * calBreaks
 *
 * @param  mixed $start unixTiimeStamp
 * @param  mixed $end unixTiimeStamp
 * @param  mixed $skipdates array()
 * @param  mixed $firstBreak 
 * @param  mixed $secondBreak
 * @return void
 */
function calBreaks($start, $end, $skipdates, $firstBreak, $secondBreak)
{

    $stopTime = date("H", strtotime($end));
    $startTime = date("H", strtotime($start));


    if (isSameDay($start, $end)) {
        if ($startTime <= $firstBreak && $stopTime <= $firstBreak || $startTime >= $secondBreak && $stopTime >= $secondBreak || $startTime >= $firstBreak && $stopTime <= $secondBreak) {
            return 0;
        } elseif (($startTime <= $firstBreak && $stopTime >= $firstBreak && $stopTime <= $secondBreak) || ($startTime >= $firstBreak && $startTime <= $secondBreak && $stopTime >= $secondBreak)) {
            return 0.5;
        } elseif ($startTime <= $firstBreak && $stopTime >= $secondBreak) {
            return 1;
        } else {
            return 999;
        }
    } elseif (isNextDay($start, $end)) {
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
        $workdays_number = count(get_workdays($start, $end, $skipdates)) - 2;
        //echo $workdays_number;
        //echo "<br>";
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
//$time = new Timei();
function get_workdays($from, $to, $skipdates)
{
    $days_array = array();
    $skipdays = array("Saturday", "Sunday");
    //$skipdates = get_holidays();
    $i = 0;
    $current = $from;
    if ($current == $to) {
        $timestamp = strtotime($from);
        if (!in_array(date("l", $timestamp), $skipdays) && !in_array(date("Y-m-d", $timestamp), $skipdates)) {
            $days_array[] = date("Y-m-d", $timestamp);
        }
    } elseif ($current < $to) // different dates
    {
        while ($current < $to) {
            $timestamp = strtotime($from . " +" . $i . " day");
            if (!in_array(date("l", $timestamp), $skipdays) && !in_array(date("Y-m-d", $timestamp), $skipdates)) {
                $days_array[] = date("Y-m-d", $timestamp);
            }
            $current = date("Y-m-d", $timestamp);
            $i++;
        }
    }
    return $days_array;
    //echo count($days_array);
}
$test_array = [
    '20-03-28 13:00:00' => '20-03-29 16:30:00', //0-0
    '20-03-29 12:00:00' => '20-03-30 14:30:00', //1-1
    '20-03-29 14:15:00' => '20-03-31 14:30:00', //2-2
    '20-03-29 14:30:00' => '20-04-04 14:30:00', //6-5
    '20-03-29 07:30:00' => '20-03-30 14:30:00', //1-1
    '20-03-29 11:45:00' => '20-03-29 14:30:00', //0-0
];
$a1 = date("Y-m-d", strtotime('20-03-29 14:30:00'));
$a2 = date("Y-m-d", strtotime('20-04-04 14:30:00'));

function createDateRangeArray($strDateFrom, $strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange = [];

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}

function daysArrayBetween2Dates($begin, $end)
{
    $i = date($begin);
    for ($i = $begin; $i <= $end; $i->modify('+1 day')) {

        print_r(date("Y-m-d", strtotime($i)));
    }
}
echo daysArrayBetween2Dates($a1, $a2);
//print_r(date("Y-m-d", strtotime($i)));

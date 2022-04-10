<?php


function isSameDay($start, $end)
{
    if (date("D", strtotime($start)) == date("D", strtotime($end))) {
        //  return true;
    } else {
        //  return false;
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
$a1 = date("Y-m-d", strtotime('20-04-02 14:30:00'));
$a2 = date("Y-m-d", strtotime('20-04-04 14:30:00'));

function createDateRangeArray($strDateFrom, $strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.
    //arraySize 1=sameday 2=nextday 2<means days beetwen - 2 (first and last day)
    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange = [];

    $iDateFrom = strtotime($strDateFrom);
    $iDateTo = strtotime($strDateTo);

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}



$test_array = [
    '20-03-28 13:00:00' => '20-03-29 16:30:00', //0
    '20-03-29 12:00:00' => '20-03-30 14:30:00', //1
    '20-03-29 14:15:00' => '20-03-31 14:30:00', //2
    '20-03-29 14:30:00' => '20-04-04 14:30:00', //5
    '20-03-29 07:30:00' => '20-03-30 14:30:00', //1
    '20-03-29 11:45:00' => '20-03-29 14:30:00', //0
];
function getHolidays()
{
    return $holidays = array('20-04-04');
}
function get_work_days($start_date, $end_date)
{

    $begin = strtotime($start_date);
    $end   = strtotime($end_date);

    if ($begin > $end)
        return 0;
    else {
        $no_days  = 0;
        $weekends = 0;
        while ($begin <= $end) {
            $no_days++;          // no of days in the given interval
            $what_day = date("N", $begin);
            if ($what_day >= 6)   // 6 and 7 are weekend days
                $weekends++;

            $begin += 86400;     // +1 day
        }
        $working_days = $no_days - $weekends;

        echo $working_days;
    }
}
foreach ($test_array as $value => $key) {
    echo get_work_days($value, $key);
    //echo date("N", strtotime('2019-11-25 7:00:00')); 26 27 28 29 30
}



function countBusinessDays($start, $stop)
{
    if ($start > $stop) {
        $tmpStart = clone $start;
        $start = clone $stop;
        $stop = clone $tmpStart;
    }

    // Adding the time to the end date will include it
    $period = new \DatePeriod($start->setTime(0, 0, 0), new \DateInterval('P1D'), $stop->setTime(23, 59, 59), \DatePeriod::EXCLUDE_START_DATE);
    // $periodIterator = new Timei($period);
    $businessDays = 0;



    return $businessDays;
}

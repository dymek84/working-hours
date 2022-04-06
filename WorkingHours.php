<?php
echo "Test array of days in date range, '20-03-29 14:30:00' => '20-04-04 14:30:00', expected 
[0] => 2020-03-29
[1] => 2020-03-30
[2] => 2020-03-31 ->(holiday)
[3] => 2020-04-01
[4] => 2020-04-02 -> saturday
[5] => 2020-04-03 -> sunday
[6] => 2020-04-04 -> \r\n \r\n";
echo print_r(createDateRangeArray('20-03-29 14:30:00', '20-04-04 14:30:00')) . "\r\n";
echo "Test 2 numbers days in date range, '20-03-29 14:30:00' => '20-04-04 14:30:00', expected\r\n 7 -> ";
echo numberOfDaysBeetween2Dates('20-03-29 14:30:00', '20-04-04 14:30:00') . "\r\n";
echo "Test 3 array of days in date range, '20-03-29 14:30:00' => '20-04-04 14:30:00', expected\r\n 5 -> ";
echo sizeof(deductWeekendFromArray(createDateRangeArray('20-03-29 14:30:00', '20-04-04 14:30:00'))) . "\r\n";
echo "test 4 check if gived date is in timestamp, we will give( timestamp, string and datetime ) \r\n";
echo strtotime('20-04-04 14:30:00') . "\r\n";
echo date('Y-m-d', strtotime('20-04-04 14:30:00')) . "\r\n";
echo "expected '1585951200 -> 1586003400' and we have \r\n";
echo checkDateAndChangeToTimestamp(date('Y-m-d H:i:s', strtotime('20-04-04 14:30:00'))) . " -> ";
echo checkDateAndChangeToTimestamp(strtotime('20-04-04 14:30:00'));
function checkDateAndChangeToTimestamp($date)
{
    if (!is_int($date)) {
        $date = strtotime($date);
    }
    return $date;
}
function createDateRangeArray($startDateAndTime, $stopDateAndTime)
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


    $aryRange = [];

    $iDateFrom = strtotime($startDateAndTime);
    $iDateTo = strtotime($stopDateAndTime);

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}

function numberOfDaysBeetween2Dates($startDateAndTime, $stopDateAndTime)
{
    return  sizeof(createDateRangeArray($startDateAndTime, $stopDateAndTime));
}

function deductWeekendFromArray($arrayOfDates)
{
    $days_array = array();
    $skipdays = array("Saturday", "Sunday");
    foreach ($arrayOfDates as $day) {

        $timestampofday = strtotime($day);
        if (!in_array(date("l", $timestampofday), $skipdays)) {
            $days_array[] = date("Y-m-d", $timestampofday);
        }
    }
    return $days_array;
}

function deductHolidaysFromArray($arrayOfDates, $arrayOfHolidays)
{
    $days_array = array();

    foreach ($arrayOfDates as $day) {

        $timestampofday = strtotime($day);
        if (!in_array(date("Y-m-d", $timestampofday), $arrayOfHolidays)) {
            $days_array[] = date("Y-m-d", $timestampofday);
        }
    }
    return $days_array;
}

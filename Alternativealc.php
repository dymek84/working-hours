<?php

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

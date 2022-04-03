<?php
function isSameDay( $start, $end )
{
	if(date("D",strtotime($start)) == date("D",strtotime($end)))
	{
		return true;
	}
	else
	{
		return false;
	}
}
function isNextDay( $start, $end )
{
	$newDate = date("D", strtotime('+1 day', strtotime($start)));
	if($newDate == date("D",strtotime($end)))
	{
		return true;
	}
	else
	{
		return false;
	}
}
function calBreaks($start,$end,$skipdates,$firstBreak,$secondBreak)
{	
	
	$stopTime = date("H", strtotime($end));
	$startTime = date("H", strtotime($start));
	
	
	if(isSameDay( $start, $end ))
	{
		if ($startTime <= $firstBreak && $stopTime <= $firstBreak || $startTime >= $secondBreak && $stopTime >= $secondBreak|| $startTime >= $firstBreak && $stopTime <= $secondBreak)
		{
			return 0;
		}
		elseif(($startTime <= $firstBreak && $stopTime >= $firstBreak && $stopTime <= $secondBreak)||($startTime >= $firstBreak && $startTime <= $secondBreak && $stopTime >= $secondBreak))
		{
			return 0.5;
		}
		elseif( $startTime <= $firstBreak && $stopTime >= $secondBreak)
		{
			return 1;
		
		}		
		else
		{
			return 999;
		}
	}
	elseif(isNextDay( $start, $end ))
	{
		 if ( $startTime <= $firstBreak)
		 {//2
			 if($stopTime <= $firstBreak)
			 {
				return 1; 
			 }
			 elseif( $stopTime >= $firstBreak && $stopTime <= $secondBreak)
			 {
				 return 1.5;
			 }
			 elseif( $stopTime >= $secondBreak)
			 {
				 return 2;
			 }
		 }
		 elseif( $startTime >= $firstBreak && $startTime <= $secondBreak)
		 {
			 if($stopTime <= $firstBreak)
			 {
				return 0.5; 
			 }
			 elseif( $stopTime >= $firstBreak && $stopTime <= $secondBreak)
			 {
				 return 1;
			 }
			 elseif( $stopTime >= $secondBreak)
			 {
				 return 1.5;	
			 }
			
		 }
		 elseif( $startTime >= $secondBreak)
		 {
			 if($stopTime <= $firstBreak)
			 {
				return 0; 
			 }
			 elseif( $stopTime >= $firstBreak && $stopTime <= $secondBreak)
			 {
				 return 0.5;
			 }
			 elseif( $stopTime >= $secondBreak)
			{
				 return 1;
			}
		}		
	}
	else
	{
		$workdays_number = count(get_workdays($start,$end,$skipdates))-2;
		//echo $workdays_number;
		//echo "<br>";
		if ( $startTime <= $firstBreak)
		 {//2
			 if($stopTime <= $firstBreak)
			 {
				return 1 + $workdays_number; 
			 }
			 elseif( $stopTime >= $firstBreak && $stopTime <= $secondBreak)
			 {
				 return 1.5 + $workdays_number;
			 }
			 elseif( $stopTime >= $secondBreak)
			 {
				 return 2 + $workdays_number;
			 }
		 }
		 elseif( $startTime >= $firstBreak && $startTime <= $secondBreak)
		 {
			 if($stopTime <= $firstBreak)
			 {
				return 0.5 + $workdays_number; 
			 }
			 elseif( $stopTime >= $firstBreak && $stopTime <= $secondBreak)
			 {
				 return 1 + $workdays_number;
			 }
			 elseif( $stopTime >= $secondBreak)
			 {
				 return 1.5 + $workdays_number;	
			 }
			
		 }
		 elseif( $startTime >= $secondBreak)
		 {
			 if($stopTime <= $firstBreak)
			 {
				return 0 + $workdays_number; 
			 }
			 elseif( $stopTime >= $firstBreak && $stopTime <= $secondBreak)
			 {
				 return 0.5 + $workdays_number;
			 }
			 elseif( $stopTime >= $secondBreak)
			{
				 return 1 + $workdays_number;
			}
		}		
	}			
}

function get_workdays($from,$to,$skipdates) 
{
    $days_array = array();
    $skipdays = array("Saturday", "Sunday");
    //$skipdates = get_holidays();
    $i = 0;
    $current = $from;
    if($current == $to) 
    {
        $timestamp = strtotime($from);
        if (!in_array(date("l", $timestamp), $skipdays)&&!in_array(date("Y-m-d", $timestamp), $skipdates)) 
		{
            $days_array[] = date("Y-m-d",$timestamp);
        }
    }
    elseif($current < $to) // different dates
    {
        while ($current < $to) {
            $timestamp = strtotime($from." +".$i." day");
            if (!in_array(date("l", $timestamp), $skipdays)&&!in_array(date("Y-m-d", $timestamp), $skipdates)) 
			{
                $days_array[] = date("Y-m-d",$timestamp);
            }
            $current = date("Y-m-d",$timestamp);
            $i++;
        }
    }
    return $days_array;
	//echo count($days_array);
}
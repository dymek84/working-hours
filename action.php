<?php
include("config.php");
session_start();
include("calculation.php");
include("Github/WorkingHours.php");
# newelementform.php  #
$today = date("Y-m-d");
$todaytime = date("H:i:s");

if ($_SERVER["REQUEST_METHOD"] == "POST"  && $_POST['worknumber'] != "") {
	$elementID = $_POST['eleid'] != "" ? $_POST['eleid'] : "";
	$addinfo = $_POST['addinfo'] != "" ? $_POST['addinfo'] : "";
	$myid = $_SESSION["myid"];
	$weight = $_POST['weight'] != "" ? $_POST['weight'] : "0";
	$worknumber = $_POST['worknumber'] != "" ? $_POST['worknumber'] : null;
	$companyname = $_POST['companyname'] != "" ? $_POST['companyname'] : null;
	$elementname = $_POST['elementname'] != "" ? $_POST['elementname'] : null;
	$preparing = $_POST['preparing'] != "" ? $_POST['preparing'] : "0";
	$second_man_name = $_POST['secondperson'] != "false" ? $_POST['secondperson'] : "";
	$startdate = $_POST['startdate'] != "" ? $_POST['startdate'] : $today;
	$starttime = $_POST['starttime'] != "" ? $_POST['starttime'] : $todaytime;
	$stopdate = $_POST['stopdate'] != "" ? $_POST['stopdate'] : $today;
	$stoptime = $_POST['stoptime'] != "" ? $_POST['stoptime'] : $todaytime;
	$newtime = roundToQuarterHour($stoptime);
	$startdatum = date("$startdate $starttime");
	$stopdatum = date("$stopdate $newtime");
	//$HollidayArray = array();
	//$duration = getDurationFromCdateArray(newCalculation($startdatum, $stopdatum, getUserExceptionsDatesArray($myid, $startdate, $stopdate), $HollidayArray, $_SESSION["finishTime"]));
	if ($_POST['action'] == 'add') {
		$sql = "INSERT INTO `web`.`elements2` 
	( `userId`, `element`, `worknumber`, `company`, `first_day_start`, `first_day_finish`, `weight`, `second_man_name`, `preparing`, `addInfo`, `type`) VALUES 
	( '$myid', '$elementname', '$worknumber', '$companyname', '$startdatum', '$stopdatum', '$weight', '$second_man_name', '$preparing', '$addinfo', 'complete')";
		mysqli_query($link, $sql) or die(mysqli_error($link));
		$_SESSION['test'] = 'element';
		header("location: index.php");
		exit;
	} elseif ($_POST['action'] == 'close') {
		$sql = "INSERT INTO `web`.`elements2` 
		( `userId`, `element`, `worknumber`, `company`, `first_day_start`, `first_day_finish`, `weight`, `second_man_name`, `preparing`, `addInfo`, `type`) VALUES 
		( '$myid', '$elementname', '$worknumber', '$companyname', '$startdatum', '$stopdatum',  '$weight', '$second_man_name', '$preparing', '$addinfo', 'notcomplete')";
		mysqli_query($link, $sql) or die(mysqli_error($link));
		$_SESSION['test'] = 'element';
		header("location: index.php");
		exit;
	} elseif ($_POST['action'] == 'update') {

		$sql = "UPDATE `web`.`elements2` SET `second_day_start`='$startdatum', `second_day_finish`='$stopdatum', `type`='complete' WHERE  `id`=$elementID;";


		//$sql = "INSERT INTO `web`.`elements2` 
		//( `userId`, `element`, `worknumber`, `company`, `first_day_start`, `first_day_finish`, `weight`, `second_man_name`, `preparing`, `addInfo`, `type`) VALUES 
		//( '$myid', '$elementname', '$worknumber', '$companyname', '$startdatum', '$stopdatum',  '$weight', '$second_man_name', '$preparing', '$addinfo', 'notcomplete')";
		mysqli_query($link, $sql) or die(mysqli_error($link));
		$_SESSION['test'] = 'element';
		header("location: index.php");
		exit;
	}
}

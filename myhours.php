<?php
class CustomHours
{
    public $worknumber;
    public $company;
    public $mon = 0;
    public $tue = 0;
    public $wed = 0;
    public $thr = 0;
    public $fri = 0;
    public $sat = 0;
    public $total = 0;
    public function __construct($worknumber)
    {
        $this->worknumber = $worknumber;
    }
    public function getCompanyName()
    {
        return $this->company;
    }
    public function setCompanyName($value)
    {
        $this->company = $value;
    }
    public function getTotal()
    {
        $this->total = $this->mon + $this->tue + $this->wed + $this->thr + $this->fri + $this->sat;
        return $this->total;
    }
    public function addToTotal($value)
    {
        $this->total += $value;
    }
}
include("./components/header.php");
include('config.php');
$week_start = new DateTime();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $weeknumber = array_key_exists('weeknumber', $_POST) ? trim($_POST['weeknumber']) : null;
    if ($weeknumber != null) {
        $week_start->setISODate(date("Y"), $weeknumber);
    } else {
        $week_start->setISODate(date("Y"), date("W"));
    }
}
$myid = $_SESSION["myid"];
function calculateBreaks($start, $stop)
{
    $startTime = (date("H:i:s", strtotime($start)) == "09:30:00" ? "10:00:00" : date("H:i:s", strtotime($start)) == "12:30:00") ? "13:00:00" : date("H:i:s", strtotime($start));
    $stopTime = date("H:i:s", strtotime($stop));
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

$Mon = $week_start->format('Y-m-d');
$Tue = date('Y-m-d', strtotime($Mon . ' +1 day'));
$Wed = date('Y-m-d', strtotime($Mon . ' +2 day'));
$Thr = date('Y-m-d', strtotime($Mon . ' +3 day'));
$Fri = date('Y-m-d', strtotime($Mon . ' +4 day'));
$Sat = date('Y-m-d', strtotime($Mon . ' +5 day'));
$allDifferentCompanyfound = array();
$sql = "SELECT * FROM elements2 where userId=$myid AND  first_day_start >= '$Mon' AND first_day_start < '$Sat 23:59:59' OR userId=$myid AND  second_day_start >= '$Mon' AND second_day_start < '$Sat 23:59:59'";
$sql2 = "SELECT DISTINCT worknumber, company FROM elements2 WHERE userId=$myid AND  first_day_start >= '$Mon' AND first_day_start < '$Sat 23:59:59' OR userId=$myid AND  second_day_start >= '$Mon' AND second_day_start < '$Sat 23:59:59'";
$sql3 = "SELECT * FROM elements2 WHERE userId=$myid";
$res_data2 = mysqli_query($link, $sql2);
$custom = array();
while ($row = mysqli_fetch_array($res_data2)) {
    $allDifferentCompanyfound[$row['worknumber']] = $row['company'];
    $aa = new CustomHours($row['worknumber']);
    $aa->setCompanyName($row['company']);
    $custom[$row['worknumber']] =  $aa;
}
$res_data = mysqli_query($link, $sql);
$weeknumberAvailable = array();
while ($row = mysqli_fetch_array($res_data)) {
    if (date('Y-m-d', strtotime($row['first_day_start'])) == $Mon) {
        $duration = (strtotime($row['first_day_finish']) - strtotime($row['first_day_start'])) / 60 / 60;
        $custom[$row['worknumber']]->mon += $duration  - calculateBreaks($row['first_day_start'], $row['first_day_finish']);
    }
    if (date('Y-m-d', strtotime($row['second_day_start'])) == $Mon) {
        $duration = (strtotime($row['second_day_finish']) - strtotime($row['second_day_start'])) / 60 / 60;
        $custom[$row['worknumber']]->mon += $duration  - calculateBreaks($row['second_day_start'], $row['second_day_finish']);
    }
    if (date('Y-m-d', strtotime($row['first_day_start'])) == $Tue) {
        $duration = (strtotime($row['first_day_finish']) - strtotime($row['first_day_start'])) / 60 / 60;
        $custom[$row['worknumber']]->tue += $duration  - calculateBreaks($row['first_day_start'], $row['first_day_finish']);
    }
    if (date('Y-m-d', strtotime($row['second_day_start'])) == $Tue) {
        $duration = (strtotime($row['second_day_finish']) - strtotime($row['second_day_start'])) / 60 / 60;
        $custom[$row['worknumber']]->tue += $duration  - calculateBreaks($row['second_day_start'], $row['second_day_finish']);
    }
    if (date('Y-m-d', strtotime($row['first_day_start'])) == $Wed) {
        $duration = (strtotime($row['first_day_finish']) - strtotime($row['first_day_start'])) / 60 / 60;
        $custom[$row['worknumber']]->wed += $duration  - calculateBreaks($row['first_day_start'], $row['first_day_finish']);
    }
    if (date('Y-m-d', strtotime($row['second_day_start'])) == $Wed) {
        $duration = (strtotime($row['second_day_finish']) - strtotime($row['second_day_start'])) / 60 / 60;
        $custom[$row['worknumber']]->wed += $duration  - calculateBreaks($row['second_day_start'], $row['second_day_finish']);
    }
    if (date('Y-m-d', strtotime($row['first_day_start'])) == $Thr) {
        $duration = (strtotime($row['first_day_finish']) - strtotime($row['first_day_start'])) / 60 / 60;
        $custom[$row['worknumber']]->thr += $duration  - calculateBreaks($row['first_day_start'], $row['first_day_finish']);
    }
    if (date('Y-m-d', strtotime($row['second_day_start'])) == $Thr) {
        $duration = (strtotime($row['second_day_finish']) - strtotime($row['second_day_start'])) / 60 / 60;
        $custom[$row['worknumber']]->thr += $duration  - calculateBreaks($row['second_day_start'], $row['second_day_finish']);
    }
    if (date('Y-m-d', strtotime($row['first_day_start'])) == $Fri) {
        $duration = (strtotime($row['first_day_finish']) - strtotime($row['first_day_start'])) / 60 / 60;
        $custom[$row['worknumber']]->fri += $duration  - calculateBreaks($row['first_day_start'], $row['first_day_finish']);
    }
    if (date('Y-m-d', strtotime($row['second_day_start'])) == $Fri) {
        $duration = (strtotime($row['second_day_finish']) - strtotime($row['second_day_start'])) / 60 / 60;
        $custom[$row['worknumber']]->fri += $duration  - calculateBreaks($row['second_day_start'], $row['second_day_finish']);
    }
}
$res_data3 = mysqli_query($link, $sql3);


?>
<style>
    /* Tables */
    table {
        font-family: Agenda-Light, sans-serif;
        width: 100%;
        margin-bottom: 1em;
        border-collapse: collapse;
        font-weight: bold;

    }



    tr {

        background: #ffffcc;
    }

    th {
        background: #fff;
        color: black;
        font-weight: bold;
    }

    th,
    td {
        padding: 0.5em;
        border: 1px solid #999;
        text-align: center;
    }
</style>
</head>

<body class="sb-nav-fixed">

    <?php include("./components/navbar.php"); ?>




    <main><br>
        <div class="container-fluid px-4">

            <?php
            $weeks = array();
            while ($row3 = mysqli_fetch_array($res_data3)) {
                $weekNumber = new DateTime($row3['first_day_start']);
                //$weekNumber->setISODate(strtotime($row3['first_day_start']), date("W"));
                $weekNumber = $weekNumber->format('W');
                $weeks[$weekNumber] = $weekNumber;
            }

            ?>
            <div style="overflow-x:auto;">
                <table>
                    <?php $Totaloftotal = 0;
                    $totalmon = 0;
                    $totaltue = 0;
                    $totalwed = 0;
                    $totalthr = 0;
                    $totalfri = 0;
                    $totalsat = 0; ?>
                    <thead>
                        <tr>
                            <th>Werknr.</th>
                            <th>AANNEMER</th>
                            <th>Buigstaatserie/
                                Omschrijving</th>
                            <th>Ma</th>
                            <th>Di</th>
                            <th>Wo</th>
                            <th>Do</th>
                            <th>Vr</th>
                            <th>Za</th>
                            <th>Totaal </th>
                        </tr>
                    </thead>

                    <tbody style="color: blue;">
                        <?php foreach ($allDifferentCompanyfound as $key => $value) { ?>
                            <tr>
                                <td><?php echo  $key; ?></td>
                                <td><?php echo $custom[$key]->getCompanyName(); ?> </td>
                                <?php if ($key == "20600") {
                                    echo " <td> </td>";
                                } else {
                                    echo " <td>Lassen</td>";
                                } ?>
                                <td><?php echo $custom[$key]->mon; ?></td>
                                <td><?php echo $custom[$key]->tue; ?></td>
                                <td><?php echo $custom[$key]->wed; ?></td>
                                <td><?php echo $custom[$key]->thr; ?></td>
                                <td><?php echo $custom[$key]->fri; ?></td>
                                <td><?php echo $custom[$key]->sat; ?></td>
                                <td style="background: #ffffff;text-align: right;font-weight: bold;color: red;"><?php echo $custom[$key]->getTotal(); ?></td>
                                <?php $Totaloftotal += $custom[$key]->getTotal();
                                $totalmon += $custom[$key]->mon;
                                $totaltue += $custom[$key]->tue;
                                $totalwed += $custom[$key]->wed;
                                $totalthr += $custom[$key]->thr;
                                $totalfri += $custom[$key]->fri;
                                $totalsat += $custom[$key]->sat; ?>
                            </tr><?php } ?>
                        <tr>
                            <td> &nbsp; </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="background: #ffffff;text-align: right;font-weight: bold;color: red;"></td>
                        </tr>
                    </tbody>

                    <tfoot style=" border: 1.5px solid #000;color: white;color: blue;">
                        <tr>
                            <th style="background: #ffffcc;"></th>
                            <th style="background: #ffffcc;"></th>
                            <th style="background: #ffffcc;"></th>
                            <th style="background: #ffffcc;color: blue;"><?php echo $totalmon; ?></th>
                            <th style="background: #ffffcc;color: blue;"><?php echo $totaltue; ?></th>
                            <th style="background: #ffffcc;color: blue;"><?php echo $totalwed; ?></th>
                            <th style="background: #ffffcc;color: blue;"><?php echo $totalthr; ?></th>
                            <th style="background: #ffffcc;color: blue;"><?php echo $totalfri; ?></th>
                            <th style="background: #ffffcc;color: blue;"><?php echo $totalsat; ?></th>
                            <th style="background: #ffffff;text-align: right;font-weight: bold;color: red;"><?php echo $Totaloftotal; ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <style>
                h2 {
                    position: relative;
                    text-align: center;
                    color: #353535;
                    font-size: 30px;
                    font-family: Agenda-Light, sans-serif;
                    font-weight: bold;
                }

                h3 {
                    position: relative;
                    text-align: center;
                    color: #353535;
                    font-size: 20px;
                    font-family: Agenda-Light, sans-serif;
                    font-weight: bold;
                }
            </style><?php $weekNumber = new DateTime();

                    $weekNumber = $weekNumber->format('W'); ?>
            <h2>Select Another Week</h2>
            <h3>Current Week: <?php echo $weekNumber; ?></h3>
            <form action="" method="post" id="myForm">
                <?php foreach ($weeks as $key) {
                    echo '  <button class="custom-btn" type="submit" name="weeknumber" value="' . $key . '"> Week ' . $key . '</button>';
                }
                ?>
            </form>
        </div>
    </main>



    <?php include("./components/footer.php"); ?>
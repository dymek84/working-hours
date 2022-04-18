<?php include("./components/header.php");
include("config.php");
$myid = $_SESSION["myid"];
$sql = "SELECT * FROM elements2 WHERE userId=$myid ORDER BY id DESC LIMIT 1";
$res_data = mysqli_query($link, $sql);
$row = mysqli_fetch_array($res_data);
$worknumber2 = isset($row['worknumber']) ? $row['worknumber'] : "";
$company2 = isset($row['company']) ? $row['company'] : "";
$complete = isset($row['type']) ? $row['type'] : "";
$today = "";
$todaytime = "";
$info = "";
$element_to_complete = "";
if ($complete == "complete") {
    $today = isset($row['second_day_finish']) ? date("Y-m-d", strtotime($row['second_day_finish'])) : date("Y-m-d", strtotime($row['first_day_finish']));
    $todaytime = isset($row['second_day_finish']) ? date("H:i", strtotime($row['second_day_finish'])) : date("H:i", strtotime($row['first_day_finish']));
} elseif ($complete == "notcomplete") {
    $today = date("Y-m-d");
    // $today = isset($row['second_day_finish']) ? date("Y-m-d", strtotime($row['second_day_finish'])) : "";
    $todaytime =  date("H:i", strtotime("07:00"));
    $info = "You have to complete this element: <b>" . $worknumber2 . " - " . $company2 . " - " . $row['element'] . "</b><br> Started -> <b>" . $today . " " . $todaytime . "</b>";
    $element_to_complete = $row['element'];
}

?>


<style>
    [data-toggle="collapse"].collapsed .if-not-collapsed,
    [data-toggle="collapse"]:not(.collapsed) .if-collapsed {
        display: none;
    }
</style>

</head>

<body class="sb-nav-fixed">

    <?php include("./components/navbar.php"); ?>




    <main>

        <div class="container-fluid px-4">


            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <form action="action.php" method="post" id="dischargeform" name="form1">
                        <div class="form-group row">
                            <h6 class="mt-4"><?php echo $info; ?></h6>
                            <div class="col">
                                <a class="btn btn-sm btn-dark mb-3 collapsed" data-toggle="collapse" href="#collapseContent1" role="button" aria-expanded="false" aria-controls="collapseContent1">
                                    <span class="if-collapsed"><b>+</b> Preparing</span>
                                    <span class="if-not-collapsed"><b>-</b> Preparing</span></a>
                            </div>
                            <div class="col">
                                <a class="btn btn-sm btn-dark mb-3 collapsed" data-toggle="collapse" href="#collapseContent" role="button" aria-expanded="false" aria-controls="collapseContent">
                                    <span class="if-collapsed"><b>+</b> 2nd Man</span>
                                    <span class="if-not-collapsed"><b>-</b> 2nd Man</span></a>
                            </div>

                        </div>
                        <input type="hidden" name="eleid" value="<?php echo $row['id']; ?>">
                        <div class="form-group row">
                            <div class="col">
                                <label><img src="https://img.icons8.com/wired/15/000000/floor-plan.png" /> Worknumber</label>
                                <div id="the-basics">
                                    <input type="text" class="form-control" name="worknumber" value="<?php echo $worknumber2; ?>" id="worknumber" placeholder="Worknumber" required>
                                </div>
                            </div>
                            <div class="col">
                                <label><img src="https://img.icons8.com/wired/15/000000/org-unit.png" /> Company</label>
                                <div id="bloodhound">
                                    <input type="text" class="form-control" name="companyname" value="<?php echo $company2; ?>" id="company" placeholder="company" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <label><img src="https://img.icons8.com/wired/15/000000/module.png" /> Element</label>
                                <div id="the-basics">
                                    <input class="form-control" type="text" name="elementname" id="elementname" placeholder="Element" value="<?php echo $element_to_complete; ?>" required>
                                </div>
                            </div>
                            <div class="col">
                                <label><img src="https://img.icons8.com/wired/15/000000/weight-kg.png" /> Weight</label>
                                <div id="the-basics">
                                    <input class="form-control" type="text" name="weight" id="weight" placeholder="Weight">
                                </div>
                            </div>

                        </div>

                        <div class="form-group row">

                            <div class=" col collapse" id="collapseContent1">
                                <label> Preparing Time</label>
                                <div id="bloodhound">
                                    <input class="form-control" type="text" name="preparing" id="preparing" placeholder="Preparing">
                                </div>
                            </div>
                            <?php
                            $sql1 = "SELECT * FROM users";
                            $res_data1 = mysqli_query($link, $sql1);
                            ?>
                            <div class="col collapse" id="collapseContent">

                                <div id="the-basics">
                                    <label for="secondperson">2nd Man Name (only if same time)</label>
                                    <select class="form-control" id="secondperson" name="secondperson">
                                        <option name="secondperson" id="secondperson" value="false">[Select Name]</option>
                                        <?php
                                        while ($row1 = mysqli_fetch_array($res_data1)) {
                                            if ($row1['userId'] == $myid)
                                                continue;

                                            echo '<option value="' . $row1['username'] . '">' . $row1['username'] . '</option>';
                                        }
                                        ?>
                                    </select>


                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col">
                                <label><img src="https://img.icons8.com/wired/15/000000/calendar--v1.png" /> Start Day</label>
                                <div id="the-basics">
                                    <input id="dpicker2" type="text" placeholder="Start Date" class="form-control" input value="<?php echo $today; ?>" id="startdate" name="startdate">
                                </div>
                            </div>
                            <div class="col">
                                <label> <img src="https://img.icons8.com/wired/15/000000/realtime-protection.png" /> Start Hour</label>
                                <div id="bloodhound">
                                    <input class="form-control" type="text" placeholder="Start Time" id="picker1" value="<?php echo $todaytime; ?>" name="starttime">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label><img src="https://img.icons8.com/wired/15/000000/calendar--v1.png" /> Stop Day</label>
                                <div id="the-basics">
                                    <input id="dpicker1" class="form-control" type="text" placeholder="Stop Date" name="stopdate">
                                </div>
                            </div>
                            <div class="col">
                                <label> <img src="https://img.icons8.com/wired/15/000000/realtime-protection.png" /> Stop Hour </label>
                                <div id="bloodhound">
                                    <input name="stoptime" class="form-control" type="text" placeholder="Stop Time" id="picker2">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <label><img src="https://img.icons8.com/wired/15/000000/info-squared.png" /> Additional Info</label>
                                <div id="the-basics">
                                    <textarea class="form-control form-control-sm" id="addinfo" name="addinfo" rows="1" placeholder="Additional information about work and element"></textarea>
                                </div>
                            </div>
                        </div>
                        <BR><BR>
                        <!--<input type="hidden" id="action" name="action" value="close">-->
                        <?php if ($complete == "complete") { ?>
                            <div class="form-group row">
                                <div class="col">
                                    <button class="btn btn-sm btn-dark" type="submit" name="action" value="add">Add Element</button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-sm btn-dark" type="submit" name="action" value="close">Close Day</button>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="form-group row">

                                <button class="btn btn-sm btn-dark" type="submit" name="action" value="update">Update Element</button>

                            </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>

    </main>



    <?php include("./components/footer.php"); ?>
<?php include("./components/header.php");
$valid = 0;
include("Github/WorkingHours.php");
require_once "config.php";
if (isset($_SESSION['test']) && $_SESSION['test'] == "feedback") {
    $valid = 1;
    $_SESSION['test'] = "";
} elseif (isset($_SESSION['test']) && $_SESSION['test'] == "element") {
    $valid = 2;
    $_SESSION['test'] = "";
}
?>
<script src='http://ifightcrime.github.io/bootstrap-growl/jquery.bootstrap-growl.min.js'></script>
</head>



<body class="sb-nav-fixed">

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">
                        Contact Us
                    </h4><button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="feedback.php" method="post" id="myForm">
                        <p> Send your message in the form below and we will get back to you as early as possible. </p>
                        <div class="form-group">
                            <label for="name"> Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="50">
                        </div>

                        <div class="form-group">
                            <label for="name"> Message:</label>
                            <textarea class="form-control" type="textarea" name="message" id="message" placeholder="Your Message Here" maxlength="6000" rows="7"></textarea>
                        </div>
                        <button type="submit" class="btn btn-lg btn-dark btn-block" id="btnContactUs">Post It! &rarr;</button>
                    </form>
                    <div id="success_message" style="width:100%; height:100%; display:none; ">
                        <h3>Sent your message successfully!</h3>
                    </div>
                    <div id="error_message" style="width:100%; height:100%; display:none; ">
                        <h3>Error</h3> Sorry there was an error sending your form.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("./components/navbar.php"); ?>
    <main class="main">

        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <?php if ($valid == 1) {; ?>
                <script>
                    $(function() {

                        setTimeout(function() {
                            $.bootstrapGrowl("I get you feedback.", {
                                type: 'success'
                            });
                        }, 2000);
                    });
                </script>
            <?php } else { ?>
                <script>
                    $(function() {

                        setTimeout(function() {
                            $.bootstrapGrowl("Element has been added", {
                                type: 'success'
                            });
                        }, 2000);
                    });
                </script>
            <?php } ?>

            <div class="row">



                <div class="col">
                </div>
                <div class="col">

                    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#myModal">Send Feedback about WebApp</button>

                </div>
                <div class="col">
                </div>
                <BR><BR><BR><BR>
                <div class="row">
                    <div class="col-xl-3 col-md-6">

                        <div class="card bg-dark text-white mb-4">
                            <div class="card-body">If you work in saturday you need to add date start/finish time to exceptions, othervise this date will not be taken into account in the calculations, is same for any other date when usually company is close ( look info board in lunchroom )</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">

                                <div class="small text-white"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-light text-dark mb-4">
                            <div class="card-body">Your finish time is <b>16:00 </b>if you want to change your finish time go to settings or click below</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-dark stretched-link" href="settings.php">Change finish time</a>
                                <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>
    <?php
    include("./components/footer.php");


    ?>
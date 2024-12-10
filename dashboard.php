<?php
// Include the configuration file
include('config.php');

// Check if the user is logged in
if(!isset($_SESSION["user_type"]))
{
    // If not, redirect to the login page
    header("location:login.php");
}

// Set the title of the page
$title = 'Dashboard';

// Include the header and sidebar files
include('header.php');
include('sidebar.php');

// Initialize the query string
$query = '';

// If the user is not a superadmin, add a condition to the query to filter by teacher ID
if ($_SESSION["user_type"] !== 'Superadmin') 
{
    $query = " AND teacher_id = '".$_SESSION["user_id"]."' ";
}

// Get the total count of pending, ongoing, not verified, and solved reports
$pending = get_total_count($connect, " SELECT * FROM $REPORTS_TABLE WHERE status IN ('Pending') $query ");
$ongoing = get_total_count($connect, " SELECT * FROM $REPORTS_TABLE WHERE status IN ('Ongoing') $query ");
$not_verified = get_total_count($connect, " SELECT * FROM $REPORTS_TABLE WHERE status IN ('Not Verified') $query ");
$solved = get_total_count($connect, " SELECT * FROM $REPORTS_TABLE WHERE status IN ('Solved') $query ");

?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-2">
                    <div class="clock-container" style="background-color: #333; padding: 10px; border-radius: 5px; display: inline-block; min-width: 200px;">
                        <div class="clock-display">
                            <span id="time" style="font-size: 24px; color: #fff;"></span>
                            <span id="date" style="font-size: 16px; display: block; margin-top: 2px; color: #fff;"></span>
                        </div>
                    </div>
                </div>
                <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
                    <div class="col-12">
                        <div class="row">
                            <!-- Reports Section - Moved Up and Made Bigger -->
                            <div class="col-12 col-md-4">
                                <div class="small-box bg-secondary" style="font-size: 1.2em; position: relative;">
                                    <?php 
                                    $has_new_pending = ($pending > 0);  // Only show notification if there are pending reports
                                    if ($has_new_pending) { ?>
                                        <div class="notification-dot"></div>
                                    <?php } ?>
                                    <div class="inner">
                                        <p style="font-size: 1.3em;">Pending Reports</p>
                                        <h3 style="font-size: 2.5em;"><?php echo $pending; ?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                    <a href="pending.php" class="small-box-footer">See more <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="small-box bg-primary" style="font-size: 1.2em; position: relative;">
                                    <?php 
                                    $has_new_ongoing = ($ongoing > 0);  // Only show notification if there are ongoing reports
                                    if ($has_new_ongoing) { ?>
                                        <div class="notification-dot"></div>
                                    <?php } ?>
                                    <div class="inner">
                                        <p style="font-size: 1.3em;">Ongoing Reports</p>
                                        <h3 style="font-size: 2.5em;"><?php echo $ongoing; ?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-history"></i>
                                    </div>
                                    <a href="ongoing.php" class="small-box-footer">See more <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="small-box bg-success" style="font-size: 1.2em;">
                                    <div class="inner">
                                        <p style="font-size: 1.3em;">Solved Reports</p>
                                        <h3 style="font-size: 2.5em;"><?php echo $solved; ?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check-circle"></i>
                                    </div>
                                    <a href="solved.php" class="small-box-footer">See more <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <p>Sections</p>
                                        <h3><?php echo get_total_count($connect, " SELECT * FROM $SECTIONS_TABLE ")?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-th-large"></i>
                                    </div>
                                    <a href="sections.php" class="small-box-footer">See more <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <p>Teachers</p>
                                        <h3><?php echo get_total_count($connect, " SELECT * FROM $USER_TABLE WHERE user_type != 'Superadmin' ")?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-user-tie"></i>
                                    </div>
                                    <a href="teachers.php" class="small-box-footer">See more <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <p>Students</p>
                                        <h3><?php echo get_total_count($connect, " SELECT * FROM $STUDENT_TABLE ")?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <a href="students.php" class="small-box-footer">See more <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <p>Rooms</p>
                                        <h3><?php echo get_total_count($connect, " SELECT * FROM $ROOMS_TABLE ")?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-store-alt"></i>
                                    </div>
                                    <a href="rooms.php" class="small-box-footer">See more <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div style="background-color: #333; padding: 10px; border-radius: 5px; text-align: center; display: flex; justify-content: center; align-items: center;">
                                <h3 class="card-title" style="color: #fff; margin: 0; display: inline-block;">Overall Report Status</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                <?php } else {?>
                    <div class="col-12 col-md-12">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="small-box bg-secondary" style="font-size: 1.2em; position: relative;">
                                            <?php 
                                            $has_new_pending = ($pending > 0);  // Only show notification if there are pending reports
                                            if ($has_new_pending) { ?>
                                                <div class="notification-dot"></div>
                                            <?php } ?>
                                            <div class="inner">
                                                <p style="font-size: 1.3em;">Pending Reports</p>
                                                <h3 style="font-size: 2.5em;"><?php echo $pending; ?></h3>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-info-circle"></i>
                                            </div>
                                            <a href="pending.php" class="small-box-footer">See more <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="small-box bg-primary" style="font-size: 1.2em; position: relative;">
                                            <?php 
                                            $has_new_ongoing = ($ongoing > 0);  // Only show notification if there are ongoing reports
                                            if ($has_new_ongoing) { ?>
                                                <div class="notification-dot"></div>
                                            <?php } ?>
                                            <div class="inner">
                                                <p style="font-size: 1.3em;">Ongoing Reports</p>
                                                <h3 style="font-size: 2.5em;"><?php echo $ongoing; ?></h3>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-history"></i>
                                            </div>
                                            <a href="ongoing.php" class="small-box-footer">See more <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="small-box bg-success" style="font-size: 1.2em;">
                                            <div class="inner">
                                                <p style="font-size: 1.3em;">Solved Reports</p>
                                                <h3 style="font-size: 2.5em;"><?php echo $solved; ?></h3>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-check-circle"></i>
                                            </div>
                                            <a href="solved.php" class="small-box-footer">See more <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card">
                                    <div style="background-color: #333; padding: 10px; border-radius: 5px; text-align: center; display: flex; justify-content: center; align-items: center;">
                                        <h3 class="card-title" style="color: #fff; margin: 0; display: inline-block;">Overall Report Status</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

</div>

<?php
// Include the footer file
include('footer.php');
?>

<head>
    <!-- Add this CSS in the head section -->
    <style>
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        .notification-dot {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 15px;
            height: 15px;
            background-color: #ff3333;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
            box-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
            z-index: 1000;
        }
        .clock-container {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .clock-display {
            color: #fff;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }
        .time {
            font-size: 48px;
        }
        .date {
            font-size: 24px;
        }
    </style>
</head>

<script>
    $(function () {
        // Get the data for the pie chart
        var donutData        = {
            labels: [
                'Pending',
                'Ongoing',
                // 'Not Verified',
                'Solved',
            ],
            datasets: [
                {
                    data: [
                        <?php echo $pending;?>,
                        <?php echo $ongoing;?>,
                        // <?php echo $not_verified;?>,
                        <?php echo $solved;?>
                    ],
                    // data: data.pie,
                    backgroundColor : ['#6c757d', '#007bff', '#28a745'], // Changed #ffc107 (yellow) to #6c757d (gray)
                }
            ]
        }

        // Get the context for the pie chart
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieData        = donutData;
        var pieOptions     = {
            maintainAspectRatio : false,
            responsive : true,
        }

        // Create the pie chart
        new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        })

    });
</script>

<script language="javascript" type="text/javascript">
/* Visit http://www.yaldex.com/ for full source code
and get more free JavaScript, CSS and DHTML scripts! */
// Begin

const today = new Date();

var timerID = null;
var timerRunning = false;
function stopclock (){
if(timerRunning)
	clearTimeout(timerID);
	timerRunning = false;
}
function showtime () {
	var now = new Date();
	var hours = now.getHours();
	var minutes = now.getMinutes();
	var seconds = now.getSeconds()
	var timeValue = "" + ((hours >12) ? hours -12 :hours)
	if (timeValue == "0") timeValue = 12;
	timeValue += ((minutes < 10) ? ":0" : ":") + minutes
	timeValue += ((seconds < 10) ? ":0" : ":") + seconds
	timeValue += (hours >= 12) ? " P.M." : " A.M."
	document.getElementById("time").innerHTML = timeValue;
	document.getElementById("date").innerHTML = today.toDateString();
	timerID = setTimeout("showtime()",1000);
	timerRunning = true;
}
function startclock() {
	stopclock();
	showtime();
}
window.onload=startclock;
// End -->
</SCRIPT>	

</body>
</html>
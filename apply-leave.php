<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['emplogin'])==0)
    {   
header('location:index.php');
}
else{
if(isset($_POST['apply'])) {
    $empid = $_SESSION['eid'];
    $leavetype = $_POST['leavetype'];
    $fromdate = $_POST['fromdate'];  
    $todate = $_POST['todate'];
    $description = $_POST['description'];  
    $dayc = $_POST['days']; // Ensure this value is captured
    $status = 0;
    $isread = 0;

    if($fromdate > $todate) {
        $error = "ToDate should be greater than FromDate";
    } else {
        $sql = "INSERT INTO tblleaves (LeaveType, ToDate, FromDate, Description, Status, IsRead, empid, dayC) 
                VALUES (:leavetype, :todate, :fromdate, :description, :status, :isread, :empid, :days)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':leavetype', $leavetype, PDO::PARAM_STR);
        $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
        $query->bindParam(':todate', $todate, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':isread', $isread, PDO::PARAM_STR);
        $query->bindParam(':empid', $empid, PDO::PARAM_STR);
        $query->bindParam(':days', $dayc, PDO::PARAM_INT); // Bind the days value
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();

        if($lastInsertId) {
            $msg = "Leave applied successfully";
        } else {
            $error = "Something went wrong. Please try again";
        }
    }
}

    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        
        <!-- Title -->
        <title>Employe | Apply Leave</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />
        
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css"/>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet"> 
        <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <style>
        .errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
        </style>
 


    </head>
    <body>
  <?php include('includes/header.php');?>
            
       <?php include('includes/sidebar.php');?>
   <main class="mn-inner">
                <div class="row">
                    <div class="col s12">
                        <div class="page-title">Apply for Leave</div>
                    </div>
                    <a href ="leaveCredits.php" target="blank">
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                                <span class="card-title">Leave Credit</span>
                                    <?php
$sql = "SELECT id from  tblleaves";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$totalleaves=$query->rowCount();
?>   
                                <span class="stats-counter"><span class="counter"><?php echo htmlentities($totalleaves);?></span></span>
                      
                            </div>
                            <div class="progress stats-card-progress">
                                <div class="success" style="width: 70%"></div>
                            </div>
                        </div>
                    </div></a>
                    <div class="col s12 m12 l8">
                        <div class="card">
                            <div class="card-content">
                                <form id="example-form" method="post" name="addemp">
                                    <div>
                                        <h3>Apply for Leave</h3>
                                        <section>
                                            <div class="wizard-content">
                                                <div class="row">
                                                    <div class="col m12">
                                                        <div class="row">
     <?php if(isset($error) && $error){ ?>
    <div class="errorWrap"><strong>ERROR </strong>:<?php echo htmlentities($error); ?> </div>
<?php } else if(isset($msg) && $msg){ ?>
    <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
<?php } ?>

<div class="input-field col s12">
    <label for="leavetype"></label>
    <select name="leavetype" id="leavetype" autocomplete="off" onchange="toggleFileUpload()">
        <option value="" name="select">Select leave type...</option>
        <?php
        $sql = "SELECT LeaveType from tblleavetype";
        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($results as $result) { ?>
                <option value="<?php echo htmlentities($result->LeaveType); ?>">
                    <?php echo htmlentities($result->LeaveType); ?>
                </option>
        <?php }
        } ?>
    </select>
</div>

<!-- Add the From Date input field -->
<div class="input-field col m6 s12">
    <label for="fromdate">From Date (Weekdays Only)</label>
    <input type="text" id="fromdate" name="fromdate" placeholder="mm/dd/yyyy" required>
</div>

<!-- Add the To Date input field -->
<div class="input-field col m6 s12">
    <label for="todate">To Date (Weekdays Only)</label>
    <input type="text" id="todate" name="todate" placeholder="mm/dd/yyyy" required>
</div>
<!-- days counter (from(date) - to (date)) -->
<div>
    <hr>
    <label for="Days of leave">Days of leave</label>
    <input type="text" id="days" name="days" value="0"readonly>
    <hr>
    
</div>
<div class="input-field col m12 s12">
<label for="birthdate">Description</label> 
   

<textarea id="textarea1" name="description" class="materialize-textarea" length="500" required></textarea>
</div>
<!-- // This is the file upload section -->
<div class="input-field col s12" id="fileUploadDiv" style="display: none;">
    <br>
    <label for="fileUpload">Please attach supporting document here (.pdf)</label><br>
    <input type="file" id="fileUpload" name="fileUpload" required accept= ".pdf">
    <hr>    
</div>
<!-- function when selecting Sick Leave function -->
<script>
    function toggleFileUpload() {
        const leaveType = document.getElementById("leavetype").value;
        const fileUploadDiv = document.getElementById("fileUploadDiv");
        const fileUpload = document.getElementById("fileUpload");

        // Show or hide the file upload div based on the selected leave type
        if (leaveType === "Sick Leave") {
            fileUploadDiv.style.display = "block";
            fileupload.setAttribute("required", "true");
            


        } else {
            fileUploadDiv.style.display = "none";
            fileUpload.removeAttribute("required"); // Clear the file input if not sick leave
        }
    }
</script>
</div>
      <button type="submit" onclick="saveFile()" name="apply" id="apply" class="waves-effect waves-light btn indigo m-b-xs">Apply</button>                                             

                                                </div>
                                            </div>
                                        </section>
                                     
                                    
                                        </section>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div class="left-sidebar-hover"></div>
        
        <!-- Javascripts -->
        <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/js/alpha.min.js"></script>
        <script src="assets/js/pages/form_elements.js"></script>
        <script src="assets/js/pages/form-input-mask.js"></script>
        <script src="assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    </body>
</html>
<?php } ?>  
<!-- // this is for handling file uploads to upload.php -->
<script>
    async function saveFile(){
        let formData = new FormData();
        formData.append('file', fileUpload.files[0]);
        await fetch('/upload.php', {
            method: 'POST',
            body: formData
        })
        

    }

</script>

<!-- Configure Flatpickr for weekday-only selection -->
<!-- <script>
    flatpickr("#fromdate", {
        dateFormat: "Y-m-d",
        disable: [
            function(date) {
                // Disable weekends: Sunday (0), Saturday (6)
                return (date.getDay() === 0 || date.getDay() === 6);
            }
        ],
        locale: {
            firstDayOfWeek: 1 // Start calendar on Monday
        }
    });

    flatpickr("#todate", {
        dateFormat: "Y-m-d",
        disable: [
            function(date) {
                // Disable weekends: Sunday (0), Saturday (6)
                return (date.getDay() === 0 || date.getDay() === 6);
            }
        ],
        locale: {
            firstDayOfWeek: 1 // Start calendar on Monday
        }
    });


    var fromDateInput = document.getElementById("fromdate");
    var toDateInput = document.getElementById("todate");
    
    
    function range(fromDateInput, toDateInput) {
        const result = [];
        for (let i = start; i <= end; i++) {
            result.push(i);
        }
        return result;
    }
</script> -->
<script>
    // Function to calculate the number of weekdays between two dates
    function calculateWeekdays() {
        const fromDate = document.getElementById("fromdate").value;
        const toDate = document.getElementById("todate").value;
        const daysField = document.getElementById("days");

        if (fromDate && toDate) {
            const start = new Date(fromDate);
            const end = new Date(toDate);
            let count = 0;

            // Loop through each date in the range
            for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                // Check if the day is a weekday (Monday to Friday)
                if (d.getDay() !== 0 && d.getDay() !== 6) {
                    count++;
                }
            }

            // Update the days field with the count of weekdays
            daysField.value = count;
        } else {
            daysField.value = ""; // Clear the field if dates are incomplete
        }
    }

    // Attach event listeners to the date fields
    document.getElementById("fromdate").addEventListener("change", calculateWeekdays);
    document.getElementById("todate").addEventListener("change", calculateWeekdays);
</script>
<!-- From Date Input -->
<label for="fromdate">From Date:</label>
<input type="date" id="fromdate" name="fromdate" placeholder="mm-day-yyyy">

<!-- To Date Input -->
<label for="todate">To Date:</label>    
<input type="date" id="todate" onchange="calculateWeekdays()" value="0" name="todate">

<!-- Days Counter -->
<label for="days">Days of Leave:</label>
<input type="text" id="days" name="days"  readonly>

<!-- <script>
    // Function to calculate the difference in days
    function calculateDays() {
        const fromDate = document.getElementById("fromdate").value;
        const toDate = document.getElementById("todate").value;
        const daysField = document.getElementById("days");

        if (fromDate && toDate) {
            const from = new Date(fromDate);
            const to = new Date(toDate);

            // Calculate the difference in time and convert to days
            const timeDiff = to - from;
            const daysDiff = timeDiff / (1000 * 60 * 60 * 24);

            // Ensure the difference is positive
            if (daysDiff >= 0) {
                daysField.value = daysDiff + 1; // Include both start and end dates
            } else {
                daysField.value = "Invalid date range";
            }
        } else {
            daysField.value = ""; // Clear the field if dates are incomplete
        }
    }

    // Attach event listeners to the date fields
    document.getElementById("fromdate").addEventListener("change", calculateDays);
    document.getElementById("todate").addEventListener("change", calculateDays);
</script> -->
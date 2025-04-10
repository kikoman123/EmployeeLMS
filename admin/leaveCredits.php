<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else {
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin | Leave Credits</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css"/>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php include('includes/header.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title" style="font-size:24px;">Leave Credits</div>
                </div>
                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Employee Leave Credits</span>
                            <table class="responsive-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Employee Name</th>
                                        <th>Leave Type</th>
                                        <th>Total Credits</th>
                                        <th>Used Credits</th>
                                        <th>Remaining Credits</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT tblemployees.FirstName, tblemployees.LastName, tblleavecredits.LeaveType, 
                                            tblleavecredits.TotalCredits, tblleavecredits.UsedCredits, tblleavecredits.RemainingCredits 
                                            FROM tblleavecredits 
                                            JOIN tblemployees ON tblleavecredits.empid = tblemployees.id";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>
                                    <tr>
                                        <td><?php echo htmlentities($cnt); ?></td>
                                        <td><?php echo htmlentities($result->FirstName . " " . $result->LastName); ?></td>
                                        <td><?php echo htmlentities($result->LeaveType); ?></td>
                                        <td><?php echo htmlentities($result->TotalCredits); ?></td>
                                        <td><?php echo htmlentities($result->UsedCredits); ?></td>
                                        <td><?php echo htmlentities($result->RemainingCredits); ?></td>
                                    </tr>
                                    <?php $cnt++; } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="../assets/js/alpha.min.js"></script>
    </body>
</html>
<?php } ?>
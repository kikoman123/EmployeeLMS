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
    $dayc = $_POST['days']; // Capture the days value
    $status = 0;
    $isread = 0;

    if($fromdate > $todate) {
        $error = "ToDate should be greater than FromDate";
    } else {
        $sql = "INSERT INTO tblleaves (LeaveType, ToDate, FromDate, Description, Status, IsRead, empid, dayc) 
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
        <title>Employee | Leave Details </title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />
        
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css"/>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

                <link href="assets/plugins/google-code-prettify/prettify.css" rel="stylesheet" type="text/css"/>  
        <!-- Theme Styles -->
        <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
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
                        <div class="page-title" style="font-size:24px;">Leave Details</div>
                    </div>
                   
                    <div class="col s12 m12 l12">
                        <div class="card">
                            <div class="card-content">
                                <span class="card-title">Leave Details</span>
                                <?php if($msg){?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php }?>
                                <table id="example" class="display responsive-table ">
                               
                                 
                                    <tbody>
<?php 
$lid=intval($_GET['leaveid']);
    $sql = "SELECT tblleaves.id as lid, tblemployees.FirstName, tblemployees.LastName, tblemployees.EmpId, 
        tblemployees.id, tblemployees.Gender, tblemployees.Phonenumber, tblemployees.EmailId, 
        tblleaves.LeaveType, tblleaves.ToDate, tblleaves.FromDate, tblleaves.Description, 
        tblleaves.PostingDate, tblleaves.Status, tblleaves.AdminRemark, tblleaves.AdminRemarkDate, 
        tblleaves.dayc 
        FROM tblleaves 
        JOIN tblemployees ON tblleaves.empid = tblemployees.id 
        WHERE tblleaves.id = :lid";
$query = $dbh -> prepare($sql);
$query->bindParam(':lid',$lid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{         
      ?>  

                                        <tr>
                                            <td style="font-size:16px;"> <b>Employe Name :</b></td>
                                              <td>
                                                <?php echo htmlentities($result->FirstName." ".$result->LastName);?></td>
                                              <td style="font-size:16px;"><b>Emp Id :</b></td>
                                              <td><?php echo htmlentities($result->EmpId);?></td>
                                              <td style="font-size:16px;"><b>Gender :</b></td>
                                              <td><?php echo htmlentities($result->Gender);?></td>
                                          </tr>

                                          <tr>
                                             <td style="font-size:16px;"><b>Emp Email id :</b></td>
                                            <td><?php echo htmlentities($result->EmailId);?></td>
                                             <td style="font-size:16px;"><b>Emp Contact No. :</b></td>
                                            <td><?php echo htmlentities($result->Phonenumber);?></td>
                                            <td>&nbsp;</td>
                                             <td>&nbsp;</td>
                                        </tr>

  <tr>
                                             <td style="font-size:16px;"><b>Leave Type :</b></td>
                                            <td><?php echo htmlentities($result->LeaveType);?></td>
                                             <td style="font-size:16px;"><b>Leave Date . :</b></td>
                                            <td>From <?php echo htmlentities($result->FromDate);?> to <?php echo htmlentities($result->ToDate);?></td>
                                            <td style="font-size:16px;"><b>Posting Date</b></td>
                                           <td><?php echo htmlentities($result->PostingDate);?></td>
                                        </tr>

        <tr>
                <td style="font-size:16px;"><b>Days of Leave: </b></td>
                <td colspan="5"><?php
                 echo htmlentities($result->dayc); // Capture the days value
                ?></td>
        </tr>
<tr>
                                             <td style="font-size:16px;"><b>Employe Leave Description : </b></td>
                                            <td colspan="5"><?php echo htmlentities($result->Description);?></td>
                                            
                                        </tr>

<tr>
<td style="font-size:16px;"><b>leave Status :</b></td>
<td colspan="5"><?php $stats=$result->Status;
if($stats==1){
?>
<span style="color: green">Approved</span>
 <?php } if($stats==2)  { ?>
<span style="color: red">Not Approved</span>
<?php } if($stats==0)  { ?>
 <span style="color: blue">waiting for approval</span>
 <?php } ?>
</td>
</tr>

<tr>
<td style="font-size:16px;"><b>Admin Remark: </b></td>
<td colspan="5"><?php
if($result->AdminRemark==""){
  echo "waiting for Approval";  
}
else{
echo htmlentities($result->AdminRemark);
}
?></td>
 </tr>

 <tr>
<td style="font-size:16px;"><b>Admin Action taken date : </b></td>
<td colspan="5"><?php
if($result->AdminRemarkDate==""){
  echo "NA";  
}
else{
echo htmlentities($result->AdminRemarkDate);
}
?></td>
 </tr>

   </form>                                     </tr>
                                         <?php $cnt++;} }?>
                                    </tbody>
                                </table>
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
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/alpha.min.js"></script>
        <script src="assets/js/pages/table-data.js"></script>
         <script src="assets/js/pages/ui-modals.js"></script>
        <script src="assets/plugins/google-code-prettify/prettify.js"></script>
        
    </body>
</html>
<?php } ?>
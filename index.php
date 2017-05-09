<?php
$title = "Survey";
session_start(); // as usual, all php need this

//check if user is login/registerd or not
//if not. redirect to login.php
if(!isset($_SESSION['user'])){
    header('Location: login.php');
}
$user = $_SESSION['user'];

$queryGetSurvey = "select ID,SurveyName, SurveyDesc, ExpiryDate from surveys where OwnedBy = (select ID from users where Email = '$user')";

require_once dirname(__FILE__)."\include\DbConnect.php";
$db = new DbConnect();
$conn = $db->connect();
$result = mysqli_query($conn,$queryGetSurvey);

if(mysqli_num_rows($result)>0){
   while ($row = mysqli_fetch_assoc($result)){
       $rows[] = $row;
   }
}else{
    $noSurvey = '<div class="alert alert-info">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    	<strong>No survey</strong> created yet.
    </div>';
}

mysqli_close($conn);

include dirname(__FILE__).'/include/header.php';
?>
    <div class="page-header">
        <h1>Recent Form</h1>
    </div>
<?php
        if(isset($noSurvey)){
            echo $noSurvey;
        }else{?>
            <div class="row">
                <?php
                foreach ($rows as $row) {?>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php echo $row['SurveyName']?>
                                        <small class="pull-right">Expiry Date:<?php echo $row['ExpiryDate']?></small>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <?php echo $row['SurveyDesc'];?>
                                </div>
                                <div class="panel-footer clearfix">
                                    <a class="btn btn-info pull-left" href="createForm.php?id=<?php echo $row['ID']?>">Edit</a>
                                    <a class="btn btn-success pull-left" href="result.php?surveyId=<?php echo $row['ID']?>">Result</a>
                                    <a class="btn btn-danger pull-right" href="deleteForm.php?id=<?php echo $row['ID'];?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </div>
                            </div>
                    	</div>
               <?php }
                ?>
            </div>
        <?php }?>
<?php
include dirname(__FILE__).'/include/footer.php';
?>
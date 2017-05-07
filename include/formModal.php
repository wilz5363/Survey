<?php
/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 3/14/2017
 * Time: 7:23 AM
 */


if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['surveySubmitBtn'])){

    $sName = $_POST['sName'];
    $sType = $_POST['sType'];
    $user = $_SESSION['user'];
    $expiryDate = $_POST['sExpiryDate'];

    $query = "insert into surveys(SurveyName , SurveyDesc, OwnedBy, ExpiryDate) 
                select '".$sName."','".$sType."',ID,'".$expiryDate."' from users where Email= '".$user."'";

    require_once dirname(__FILE__)."\DbConnect.php";
    $db = new DbConnect();
    $conn = $db->connect();

    $result = mysqli_query($conn,$query);
    if($result){
        $last_id = mysqli_insert_id($conn);
        header("Location: createForm.php?id=".$last_id);

    }
    else{
        echo mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<div class="modal fade" id="formModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--u create bs3-form inside this div-->
            <form action="" method="post" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Create New Survey</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sName">Title</label>
                        <input type="text" class="form-control" name="sName" id="sName" placeholder="Enter Survey Name">
                    </div>

                    <div class="form-group">
                        <label for="sType">Survey Description</label>
                        <input type="text" class="form-control" name="sType" id="sType" placeholder="Enter Survey Description">
                    </div>

                    <div class="form-group">
                        <label for="sExpiryDate">Expiry Date</label>
                        <input type="date" class="form-control" name="sExpiryDate" id="sExpiryDate">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" name="surveySubmitBtn" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

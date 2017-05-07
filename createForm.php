<?php
$title = "Create Form";
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $sID = $_GET['id'];
        $user = $_SESSION['user'];
        $queryGetTitle = "Select SurveyName, SurveyDesc from surveys where 
                    ID = '$sID' and OwnedBy=(select ID FROM users where Email = '$user')";

        //23/3/2017 3AM
        //I made changes here.We delete Question need to take base on ID bcause
        //Delete from Questions where ID = ???, so
        $queryGetQuestions = "SELECT questions.ID,Question, Answer
FROM questions LEFT JOIN  answers
ON answers.QuestionId = questions.ID
WHERE questions.SurveyId = '$sID'";

        include dirname(__FILE__) . ".\include\DbConnect.php";
        $db = new DbConnect();
        $conn = $db->connect();

        //get
        $resultTitle = mysqli_query($conn, $queryGetTitle);


        if (mysqli_num_rows($resultTitle) > 0) {
            $rows = mysqli_fetch_assoc($resultTitle);
            $_SESSION['formTitle'] = $rows['SurveyName'];

            $questions = array();

            $resultQuestions = mysqli_query($conn, $queryGetQuestions);
            if(mysqli_num_rows($resultQuestions) > 0){
                //23/March 3Am
                // here i also made changes since we got another column ID added instead of jz Question and Answer
                $results = array();
                $preQuestion = '';
                $count = 0;
                while ($row = mysqli_fetch_assoc($resultQuestions)){

                    if($row['Question']!= $preQuestion){
                        $count++;
                        $results[$count]['ID'] = $row['ID'];
                        $results[$count]['Question'] = $row['Question'];
                        $results[$count]['Answer'][] = $row['Answer'];
                        $preQuestion = $row['Question'];

                    }else{
                        $results[$count]['Answer'][] = $row['Answer'];
                    }

                }

//                echo json_encode($results, 128);
                //to see the results in json, uncomment below
                //exit();
            }else{
                $noQuestions= '<div class="alert alert-warning">
                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                	<strong>No Question</strong> Set For This Survey Yet.
                </div>';
            }


        } else {
            $noForm = '<div class="alert alert-danger">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            	<strong>Warning! </strong> No such Survey Form in database.
            </div>';
        }
    } else {
        header("Location: index.php");
    }
    mysqli_close($conn);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['surveySubmitBtn'])) {
    include dirname(__FILE__) . '\phpseclib\Math\BigInteger.php';
    include dirname(__FILE__). '\phpseclib\crypto.php';
    $sId = $_GET['id'];
    if(isset($_POST['sQues']) && isset($_POST['sAns'])){
        $sQues = $_POST['sQues'];
        $sAns = $_POST['sAns'];

        require_once dirname(__FILE__) . '\include\DbConnect.php';
        $db = new DbConnect();
        $conn = $db->connect();

        $qInsertQues = "insert into questions(Question, SurveyId) VALUE ('$sQues','$sId')";
        if (mysqli_query($conn, $qInsertQues)) {
            $quesId = mysqli_insert_id($conn);
            //print $quesId;
            //$sAns is array, $ans is jz taking the value...using foreach
            foreach ($sAns as $ans) {
                $qInsertAns = "insert into answers(Answer,QuestionId) VALUE ('$ans','$quesId')";


                if (!mysqli_query($conn, $qInsertAns)) {
                    mysqli_error($conn);
                }else{
                    $ansId = mysqli_insert_id($conn);
                    $hash = new Math_BigInteger(generate1024bithash());
                    $qInsertCryptoTable = "insert into crypto_table(AnswerId, Answer_Hash) VALUE (".$ansId.",'".$hash."')";
                    mysqli_query($conn, $qInsertCryptoTable);
                }
            }

        } else {
            echo "Something went wrong";
        }
    }

    mysqli_close($conn);

    header("Refresh:0");
}

include dirname(__FILE__) . '.\include\header.php';
?>
<div class="page-header">
    <h1><?php echo $_SESSION['formTitle']; ?></h1>
</div>
<div class="row">
<?php if(isset($noQuestions)){
   echo $noQuestions;
}else if (isset($noForm)){
    echo $noForm;
} else{
    /*
     * 23/March 3AM
     * here i added the result[ID] so tht url can see as deleteQuesAns.php?id=7 on delete button
     * see deleteQuesAns.php
     */
        foreach ($results as $result){
            echo '<div class="col-md-6">
                    <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <div class="btn-group pull-right">
                            <a class="btn btn-info pull-right" href="updateQuesAns.php">Update</a>
                            <a class="btn btn-danger pull-right" href="deleteQuesAns.php?id='.$result['ID'].'">Delete</a>
                        </div>
                        <h4>'.$result['Question'].'</h4>
                    </div>
                    <div class="panel-body">';

                        foreach ($result['Answer'] as $answer) {
                            echo $answer."<br>";
                        }
                        echo '</div>
                    </div>
                </div>';
        }
}?>
</div>

<a class="btn btn-primary pull-right" data-toggle="modal" href="#modal-id">Add Question</a>
<div class="modal fade" id="modal-id">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3><?php echo $_SESSION['formTitle']; ?></h3>
                    <!--                    <h4 class="modal-title">Survey Title</h4>-->
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sQues">Question</label>
                        <input type="text" class="form-control" name="sQues" id="sQues" placeholder="Input Question">
                    </div>

                    <div id="addAnswer">
                        <div class="form-group">
                            <label for="sAns">Answer</label>
                            <input type="text" class="form-control" name="sAns[]" id="sAns"
                                   placeholder="Please Enter Your Answer">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" onclick="addAnswer()">Add Answer</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php
include dirname(__FILE__) . "/include/footer.php";
?>








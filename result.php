<?php
$title = "Result";
session_start();

$surveyId = $_GET['surveyId'];

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}

require_once dirname(__FILE__)."/include/DbConnect.php";
include '.\phpseclib\crypto.php';
include '.\phpseclib\Math\BigInteger.php';
$db = new DbConnect();
$conn = $db->connect();

$sqlGetQuestionAndAnswer = "select questions.Question, answers.Answer
                                from questions, answers 
                                where questions.ID = answers.QuestionId and questions.SurveyId =".$surveyId;
$qResult = mysqli_query($conn, $sqlGetQuestionAndAnswer);
$qaArrays = array();
$qA['Answers'] = array();
$answers = array();
$fQuestion = "";
while ($result = mysqli_fetch_assoc($qResult)){
    $nextQuestion = $result['Question'];
    if($nextQuestion == $fQuestion){
        array_push($qA['Answers'], $result['Answer'] );
        array_push($qaArrays, $qA );
    }else{
        $fQuestion = $nextQuestion;
        unset($answers);
        $qA['Question'] = $fQuestion;
        array_push($qA['Answers'], $result['Answer']);
    }
}
$sqlGetAggregateData = "select QuestionId, FirstSelection, FirstKey, SecondSelection, SecondKey from aggregate_table where QuestionId in (select ID FROM  questions where SurveyId = ".$surveyId.") order by QuestionId";
$aggregateDataResult = mysqli_query($conn, $sqlGetAggregateData);
$sqlGetAnswerHash = "select AnswerId, Answer_Hash from crypto_table where AnswerId in (SELECT ID from answers where QuestionId in (SELECT ID from questions where SurveyId = ".$surveyId."))";
$AnswerHashResult = mysqli_query($conn,$sqlGetAnswerHash);
$AnswerHashes = array();
$AnswerHashesArray = array();
$hashRowNum = 0;
while($row = mysqli_fetch_assoc($AnswerHashResult)) {
    $hash = $row['Answer_Hash'];

    if($hashRowNum %2 == 0){
        array_push($AnswerHashes, $hash);
        array_push($AnswerHashesArray, $AnswerHashes);
    }else{
        array_push($AnswerHashes, $hash);
    }
    $hashRowNum++;
}
$aQuestion = "";
$firstRowData = 0;
$aCipherText = null;
$aKey = null;
$bCipherText = null;
$bKey = null;
$aggregateDataArray = array();
$aggregateData = array();
$aggregateKey = array();
while ($row = mysqli_fetch_assoc($aggregateDataResult)){
   $nextRow = $row['QuestionId'];
   if($nextRow == $aQuestion){
       
       $nACipherText = new Math_BigInteger($row['FirstSelection']);
       $nAKey = new Math_BigInteger($row['FirstKey']);

       $nBCipherText = new Math_BigInteger($row['SecondSelection']);
       $nBKey = new Math_BigInteger($row['SecondKey']);

       $aKey = $aKey->add($nAKey);
       $aCipherText = $aCipherText->add($nACipherText);

       $bKey = $bKey->add($nBKey);
       $bCipherText = $bCipherText->add($nBCipherText);
   }else{
       if($firstRowData != 0 ){
           array_push($aggregateKey, $aKey);
           var_dump($aKey);
           array_push($aggregateKey, $bKey);
           var_dump($bKey);
           array_push($aggregateData, $aCipherText);
           array_push($aggregateData, $bCipherText);
       }
        $aCipherText = new Math_BigInteger($row['FirstSelection']);
        $aKey = new Math_BigInteger($row['FirstKey']);
        var_dump($aKey->value);
        $bCipherText = new Math_BigInteger($row['SecondSelection']);
        $bKey = new Math_BigInteger($row['SecondKey']);
        $aQuestion = $row['QuestionId'];
    }
    $firstRowData++;
}

var_dump($aggregateKey);
var_dump($aggregateData);
var_dump($AnswerHashesArray);

exit();

include dirname(__FILE__).'/include/header.php';
?>

<div class="page-header">
    <h1>Result</h1>
</div>

<div class="row">
    <?php
        $questionCount = 0;
        foreach ($qaArrays as $question){?>
            <div class="col-md-4">
                <h6><?php echo $question['Question'];?></h6>
                <!--this is the tag to show the chart. using canvas tag-->
                <canvas id="myChart<?php echo $questionCount?>" width="400" height="400"></canvas>
            </div>
        <?php
        $questionCount++;} ?>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script>
    var data = [];

    <?php
            $dataCount = 0;
        foreach ($qaArrays as $qa){?>

     data[<?php echo $dataCount?>] = {
        //This one most prob will use for asnwer
        labels: [
            "<?php echo $qa['Answers'][0];?>",
            "<?php echo $qa['Answers'][1];?>"
        ],
        datasets: [
            {
                //data for how many ppl select this answer
                data: [
                        1,
                        1
                    ],
                //colour to differentiate answer
                backgroundColor: [
                    "#FF6384",
                    "#36A2EB"
                ],
                hoverBackgroundColor: [
                    "#FF6384",
                    "#36A2EB"
                ]
            }]
    };


    <?php
    $dataCount++;
        }


    // For a pie chart
    for($index = 0; $index < $dataCount; $index++){
        echo "var ctx = document.getElementById('myChart".$index."');";
        echo "var myPieChart".($index+1)." = new Chart(ctx,{".
                "type: 'pie',".//if wanna use donut, changepie to doughnut
                "data: data[".$index."]})";
    }

?>

</script>

<?php
include dirname(__FILE__).'/include/footer.php';
?>
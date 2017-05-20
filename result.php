<?php
$title = "Result";
//session_start();

$surveyId = $_GET['surveyId'];

//if(!isset($_SESSION['user'])) {
//    header('Location: login.php');
//}

require_once "include/DbConnect.php";
require_once "include/CipherDbConnect.php";
include 'phpseclib/Math/BigInteger.php';
include 'phpseclib/crypto.php';
include 'viewmodel/QuestionAnswerViewModel.php';
include "viewmodel/CryptoViewModel.php";

$db = new DbConnect();
$conn = $db->connect();

$cipherDb = new CipherDbConnect();
$cipherConn = $cipherDb->connect();

$allQuestionAndAnwers = "select questions.ID as QuestionId, questions.Question, answers.ID as AnswerId, answers.Answer from questions, answers where surveyId =".$surveyId." and questions.ID = answers.QuestionId";
$surveyResult = mysqli_query($conn, $allQuestionAndAnwers);
$answerIds = array();
while ($data = mysqli_fetch_assoc($surveyResult)){
    $result = new QuestionAnswerViewModel();

    $result->setQuestionId($data['QuestionId']);
    $result->setQuestion($data['Question']);
    $result->setAnswerId($data['AnswerId']);
    $result->setAnswer($data['Answer']);

    $answerIds[] = $result->getAnswerId();
    $surveyTempData[]=$result;
}
//
//$cipherData = mysqli_query($cipherConn)

//var_dump($surveyData);
//exit();

$allCipherData = "select AnswerId, Answer_Hash, Answer_Key, Answer_CipherText from crypto_table where AnswerId in (".implode(',', $answerIds).")";
$cipherResult = mysqli_query($cipherConn, $allCipherData);
$cryptoData = array();

while ($cipherData = mysqli_fetch_assoc($cipherResult)){

    $cipherText = new Math_BigInteger($cipherData['Answer_CipherText']);
    $cipherKey = new Math_BigInteger($cipherData['Answer_Key']);
    $cipherHash = new Math_BigInteger($cipherData['Answer_Hash']);

    $selectCount = decryption($cipherText, $cipherKey, $cipherHash);

    $cryptoData[] = new CryptoViewModel($cipherData['AnswerId'], $cipherHash, $cipherKey, $cipherText, $selectCount);

}

$surveyData = array();
foreach ($surveyTempData as $survey){
    foreach ($cryptoData as $crypto){
        if($survey->getAnswerId() == $crypto->getAnswerId()){
            $survey->setSelectionCount($crypto->getCount());
            $surveyData[] = $survey;
        }
    }
}
$answerDatas = array();
$tempData = array();
$lastQuestion = "";
foreach ($surveyData as $tempAnswerData){
    if($lastQuestion === $tempAnswerData->getQuestion()){
        $tempData['Answer2'] = $tempAnswerData->getAnswer();
        $tempData['Count2'] = $tempAnswerData->getSelectionCount();

        array_push($answerDatas, $tempData);
    }else{
        $tempData = array();
        $lastQuestion = $tempAnswerData->getQuestion();
        $tempData['Answer1'] = $tempAnswerData->getAnswer();
        $tempData['Count1'] = $tempAnswerData->getSelectionCount();
    }
}

include dirname(__FILE__).'/include/header.php';
?>

<div class="page-header">
    <h1>Result</h1>
</div>

<div class="row">
    <?php
        $questionCount = 0;
        $lastQuestion = "";
        foreach ($surveyData as $data){
                if($lastQuestion === $data->getQuestion()){
                    continue;
                }else {
                    $lastQuestion = $data->getQuestion();
                    ?>
                    <div class="col-md-4">
                        <h6><?php echo $data->getQuestion(); ?></h6>
                        <!--this is the tag to show the chart. using canvas tag-->
                        <canvas id="myChart<?php echo $questionCount ?>" width="400" height="400"></canvas>
                    </div>
                    <?php
                    $questionCount++;
                }
        } ?>
</div>

<script>
    var data = [];

    <?php
            $dataCount = 0;
        foreach ($answerDatas as $data){?>

     data[<?php echo $dataCount?>] = {
        //This one most prob will use for asnwer
        labels: [
            "<?php echo $data['Answer1'];?>",
            "<?php echo $data['Answer2'];?>"
        ],
        datasets: [
            {
                //data for how many ppl select this answer
                data: [
                        <?php echo $data['Count1'] == "" ? 0 : $data['Count1'];?>,
                        <?php echo $data['Count2'] == "" ? 0 : $data['Count2'];?>
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
        echo "var ctx = document.getElementById('myChart".$index."'); \r\n";
        echo "var myPieChart".($index+1)." = new Chart(ctx,{".
                "type: 'pie',".//if wanna use donut, changepie to doughnut
                "data: data[".$index."]}); \r\n";
    }

?>
</script>

<?php
include dirname(__FILE__).'/include/footer.php';
?>
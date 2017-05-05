<?php
$title = "Result";
session_start();

$surveyId = $_GET['surveyId'];

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}

require_once dirname(__FILE__)."/include/DbConnect.php";
$db = new DbConnect();
$conn = $db->connect();

$queryGetQuestion = "select questions.Question, answers.Answer, answers.SelectionCount, crypto_table.Answer_Key, crypto_table.Answer_Hash
                           from questions, answers, crypto_table
                           where questions.ID = answers.QuestionId AND
                                 answers.ID = crypto_table.AnswerId
                                 and questions.SurveyId = ".$surveyId;


$qResult = mysqli_query($conn, $queryGetQuestion);
$qResult = mysqli_fetch_all($qResult);
var_dump($qResult);


include dirname(__FILE__).'/include/header.php';
?>

<div class="page-header">
    <h1>Result</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <h6><?php echo $response['question'];?></h6>
        <!--this is the tag to show the chart. using canvas tag-->
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script>


    var data = {
        //This one most prob will use for asnwer
        labels: [
            "<?php echo $response['answers'][0]['Answer'];?>",
            "<?php echo $response['answers'][1]['Answer'];?>"
        ],
        datasets: [
            {
                //data for how many ppl select this answer
                data: [<?php echo $response['answers'][0]['SelectionCount'];?>, <?php echo $response['answers'][1]['SelectionCount'];?>],
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

    //usual stuff lo..later will use to populate into our canvas
    var ctx = document.getElementById("myChart");

    // For a pie chart
    var myPieChart = new Chart(ctx,{
        type: 'pie',//if wanna use donut, changepie to doughnut
        data: data
    });
</script>


<?php
include dirname(__FILE__).'/include/footer.php';
?>
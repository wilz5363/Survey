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
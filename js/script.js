function addQuestion() {
    var size = 0;
    var addQuestion = document.getElementById("add question");

    var newQuestion = document.createElement("div");
    newQuestion.innerHTML = "<div class='form-group' id='addQues" +size+"'> " +
        "<input type='question' class='form-control' placeholder= 'Please Enter Your Question'>";

    addQuestion.appendChild(newQuestion);


}

function addAnswer() {
    var addAnswer = document.getElementById("addAnswer");

    var newAnswer = document.createElement("div");
    newAnswer.innerHTML = "<div class='form-group'> " +
        "<input type='text' class='form-control' name='sAns[]' id='sAns' placeholder='Please Enter Your Answer'>";

    addAnswer.appendChild(newAnswer);


}



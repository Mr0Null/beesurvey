
var currentQuestion = 0;
var numberOfQuestions = 0;
var lastQuestion = new Array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
var answers = new Array();

var raw = "";
var data = "";
var questions = "";

$(document).ready(function(){
    raw = $('#surveyQuestions').text();
    data = JSON.parse(raw);
    questions = data.questions;

    questions.forEach(function(currQuestion) {
        numberOfQuestions++;
        generateQuestionElements(currQuestion);
    });

    $('#backBtn').click(function() {
        if (!$('#backBtn').hasClass('disabled')) {
            showPreviousQuestion();
        }
        setNavButtonStates();
    });

    $('#nextBtn').click(function() {
        if (!$('#nextBtn').hasClass('disabled')) {
            showNextQuestion();
        }
        setNavButtonStates();
    });

    $('#startBtn').click(function () {
        showNextQuestion();
        setNavButtonStates();
    });

    $('#submitBtn').click(function() {
        if (!$('#submitBtn').hasClass('disabled')) {
            submitSurvey();
        }
    });

    $('#closeError').click(function() {
        $('#surveyErrorbox').hide();
    });

    $('.survey-form-btn').click(function() {
        surveyButtonClick(this);
    });

    $('.survey-textbox').keydown(function(e) {
        if (e.keyCode == 13) {
            surveyButtonClick();
            $(this).blur();
        }
    });

    initialize(data);
});

function surveyButtonClick(e) {
    var type = questions[currentQuestion - 1].questionType;
    var answer = new Object();
    answer.questionID = currentQuestion;
    var chosenOption = 0;


    if (type == 'YesNo') {
        if ($(e).hasClass('noBTN')) {
            chosenOption = 1;
            answer.result = 'No';
        }else{
            chosenOption = 0;
            answer.result = 'Yes';
        }
    }else if (type == 'Text') {
        chosenOption = 0;
        var textboxID = '#q_' + currentQuestion + '_textbox';
        answer.result = $(textboxID).val();
    }else{
        // another types in future!
    }

   // console.log("salam", currentQuestion, chosenOption, answer);

    answers[currentQuestion] = answer;
    showNextQuestion(chosenOption);
    setNavButtonStates();
}

function initialize(data) {
    $('#surveyTitle').text(data.Title);
    $('#surveyGreatings').text(data.WelcomeMessage);
    $('#navigation').hide();
}

function generateQuestionElements(question) {
    var questionID = "q_" + question.questionId;
    var questionElement = $('<div id="' + questionID + '" class="question"></div>');
    var questionTextElement = $('<div class="question-text" style="margin-bottom: 10px;"></div>');
    var answerElement = $('<div class="answer"></div>');
    var type = question.questionType;
    questionElement.appendTo($('.questionContainer'));
    questionElement.append(questionTextElement);
    questionElement.append(answerElement);
    questionTextElement.text(question.questionTitle);
    if (type == "YesNo") {
        // generate yes no question
        var yesText = question.questionOptions[0].YesBTN;
        var noText = question.questionOptions[1].NoBTN;
        var btnHolder = $('<div class="btn-group-vertical col-lg-12 col-md-12 col-sm-12 col-xs-12" style="direction: ltr; margin-bottom: 10px;"></div>');
        var noBTN = $('<div class="btn-group"><button type="button" id="' + questionID + '_noBtn" class="btn btn-default noBTN survey-form-btn" style="" href="#">' + noText + '</button></div>');
        var yesBTN = $('<div class="btn-group"><button type="button" id="' + questionID + '_yesBtn" class="btn btn-default yesBTN survey-form-btn" style="" href="#">' + yesText + '</button></div>');
        btnHolder.appendTo(answerElement);
        btnHolder.append(yesBTN);
        btnHolder.append(noBTN);
    }else if (type = "Text") {
        // insert textbox
        var placeHolder = question.questionOptions[0].placeholder;
        var ID = questionID + "_textbox";
        var inputGroup = $('<div class="input-group" style="direction: ltr;"><span class="input-group-btn"><button type="button" class="btn btn_default survey-form-btn" href="#">ثبت</button></span><input type="text" class="form-control survey-textbox" placeholder="' + placeHolder + '" name="' + ID + '" id="' + ID + '" style="direction: rtl;"></div>');
        inputGroup.appendTo(answerElement);
    }else {
        // another options will be added! (like multi-selection or single selection or list selection
    }

    questionElement.hide();
}

function setNavButtonStates() {
    if (currentQuestion == numberOfQuestions) {
        $('#nextBtn').addClass('disabled');
        $('#submitBtn').removeClass('disabled');
    }else{
        $('#nextBtn').removeClass('disabled');
        $('#submitBtn').addClass('disabled');
    }
    if (currentQuestion == 1) {
        $('#backBtn').addClass('disabled');
    }else{
        $('#backBtn').removeClass('disabled');
    }
}

function showNextQuestion(chosenOption) {
    if (typeof(chosenOption) == 'undefined') chosenOption = 0;
    var newQuestion = 1;
    if (currentQuestion == 0) {
        // welcome message shown!
        $('#startBtn').hide();
        $('#surveyGreatings').hide();
        $('#navigation').show();
    }else if (currentQuestion != numberOfQuestions) {
        // normal usage!
        newQuestion = questions[currentQuestion - 1].nextQuestion[chosenOption];
        lastQuestion[newQuestion] = currentQuestion;
    }else{
        submitSurvey();
        return;
    }
    var currentQuestionID = '#q_' + currentQuestion;
    var newQuestionID = '#q_' + newQuestion;
    $(currentQuestionID).hide();
    $(newQuestionID).show();
    currentQuestion = newQuestion;
}

function showPreviousQuestion() {
    var prevQuestion = lastQuestion[currentQuestion];
    var prevQuestionID = '#q_' + prevQuestion;
    var currentQuestionID = '#q_' + currentQuestion;
    $(currentQuestionID).hide();
    $(prevQuestionID).show();
    currentQuestion = prevQuestion;
}

function submitSurvey() {
    $.ajax({
        url      : "submit.php",
        data     : {ID: data.surveyID, data: answers},
        type     : 'POST',
        //contentType : 'charset=UTF-8',
        dataType    : 'json',
        success : function(res) {
            console.log(res);
            if (res.status == 'success')
                showGreatings();
            else showError(res.status);
        },
        error   : function() {
            showError();
        }
    });
}

function showError(msg) {
    if (typeof(msg) == 'undefined') msg = 'در ثبت اطلاعات خطایی بوجود آمده‌است، لطفا دوباره تلاش کنید!';
    var text = '<strong>خطا!</strong> ' + msg;
    $('#surveyErrorbox').html(text);
    $('#surveyErrorbox').show();
    $('.questionContainer').hide();
    $('#navigation').hide();
}

function showGreatings() {
    var showText = data.EndMessage;
    showText = "<strong>اطلاعات با موفقیت ثبت شد!</strong> " + showText;
    $('#surveySuccessbox').html(showText);
    var ID = '#q_' + currentQuestion;
    $(ID).hide();
    $('#navigation').hide();
    $('#surveySuccessbox').show();
}
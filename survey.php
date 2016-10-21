<?php if (!$INDEX_IS_LOADED) die("Access Forbidden!"); ?>

<?php
if (file_exists("survey.json")) {
    $myfile = fopen("survey.json", "r") or die("Unable to open file!");
    echo '<div style="display: none;" id="surveyQuestions"> ' . fread($myfile,filesize("survey.json")) . '</div>';
    fclose($myfile);
}
?>

<form action="boz.php" method="post" class="form-vertical" id="surveyForm" style="margin-bottom: 10px;">
    <legend class="surveyTitle" id="surveyTitle" style="padding: 5px; text-align: center;"></legend>
    <div class="alert alert-danger" role="alert" id="surveyErrorbox" style="display: none;">
        <strong>خطا!</strong> در ثبت اطلاعات خطایی بوجود آمده‌است، لطفا دوباره تلاش کنید!
        <button type="button" class="close" aria-label="Close" style="float: left; right: 0;" id="closeError"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="alert alert-success" role="alert" id="surveySuccessbox" style="display: none;">
    </div>
    <div class="greatingMessage" id="surveyGreatings" style="margin-bottom: 15px;"></div>
    <button type="button" class="btn btn-success btn-md btn-block" id="startBtn">شروع</button>
    <div class="questionContainer"></div>
</form>


<div class="btn-group btn-group-justified" style="direction: ltr;" id="navigation">
    <div class="btn-group">
        <button type="button" id="nextBtn" class="btn btn-primary" style="" href="#">
            <div class="glyphicon glyphicon-chevron-left"></div>
            بعد
        </button>
    </div>
    <div class="btn-group">
        <button type="button" id="submitBtn" class="btn btn-success" style="">ارسال</button>
    </div>
    <div class="btn-group">
        <button type="button" id="backBtn" class="btn btn-primary" style="" href="#">
            قبل
            <div class="glyphicon glyphicon-chevron-right"></div>
        </button>
    </div>
</div>

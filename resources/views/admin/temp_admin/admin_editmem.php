<?php
include "../config.php";
include '../header.admin.php';
/**
 * Created by PhpStorm.
 * User: hp prodesk
 * Date: 4/4/2017
 * Time: 2:35 AM
 */

$id = $_GET['id'];

$db->bind("id", $id);
$r = $db->query("SELECT * FROM member WHERE ac_id = :id");

$usrname;
$pass;
$name;
$mail;
$no;
$tel;



foreach($r as $asd=>$row_data){

    $usrname=$row_data[ac_id];
    $pass = $row_data[ac_pw];
    $name = $row_data[name];
    $mail = $row_data[email];
    $no = $row_data[phone];
    $tel = $row_data[tel];
}

?>

<html>
<head>
    <link type="text/css" rel="stylesheet" href="/common/gd_reset.css">
    <link type="text/css" rel="stylesheet" href="/common/chosen.css">
    <link type="text/css" rel="stylesheet" href="/common/gd_layout.css">
    <link type="text/css" rel="stylesheet" href="/common/gd_common.css">
    <link type="text/css" rel="stylesheet" href="/common/gd_item-display.css">
    <link type="text/css" rel="stylesheet" href="/common/gd_goods-view.css">
    <link type="text/css" rel="stylesheet" href="/common/gd_contents.css">
    <link type="text/css" rel="stylesheet" href="/common/gd_share.css">
    <link type="text/css" rel="stylesheet" href="/common/gd_custom.css">
    <link type="text/css" rel="stylesheet" href="/common/bootstrap-datetimepicker.min.css">
    <link type="text/css" rel="stylesheet" href="/common/bootstrap-datetimepicker-standalone.css">


    <!--[if lte ie 8]>
    <link type="text/css" rel="stylesheet" href="/data/skin/front/story_g_us/css/gd_old-ie.css?ts=1489019719"/>
    <![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

</head>
<body>

<div class="join-form" style="width: 600px;margin: auto;">
    <form id="formJoin" name="formJoin" action="/member/member_ps.php" method="post" novalidate="novalidate">
        <input type="hidden" name="rncheck" value="">
        <input type="hidden" name="dupeinfo" value="">
        <input type="hidden" name="pakey" value="">
        <input type="hidden" name="foreigner" value="">
        <input type="hidden" name="adultFl" value="">
        <input type="hidden" name="mode" value="join">
        <fieldset>
            <legend>?????</legend>
            <div class="tit">
                <h3>Basic information</h3>

                <p>You should fill in the checked items.</p>
            </div>
            <!-- ????/?? ???? -->
            <div class="table1">
                <table>
                    <colgroup>
                        <col style="width:163px;">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th class="ta-l required" aria-required="true">ID</th>
                        <td>
                            <div class="txt-field" style="border: 1px solid rgb(208, 208, 208);">
                                <input type="text" class="text" data-pattern="gdMemberId" name="memId" id="memId" value="<?=$usrname;?>">
                            </div>
                        </td>
                    </tr>
                    <tr class="">
                        <th class="ta-l required" aria-required="true">Password</th>
                        <td>
                            <div class="txt-field">
                                <input type="password" class="text" id="newPassword" name="memPw" autocomplete="off" placeholder="" value="value="<?=$pass;?>"">
                            </div>
                        </td>
                    </tr>
                    <tr class="">
                        <th class="ta-l required" aria-required="true">Confirm password</th>
                        <td>
                            <div class="txt-field">
                                <input type="password" class="text check-id" name="memPwRe" autocomplete="off">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="ta-l required" aria-required="true">Name</th>
                        <td>
                            <div class="txt-field">
                                <input type="text" class="text" name="memNm" data-pattern="gdEngKor" value="<?=$name;?>" maxlength="30">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="ta-l">Email address</th>
                        <td>
                            <div class="email">
                                <div class="txt-field">
                                    <input type="text" class="text" name="email" id="email" value="<?=$mail;?>">
                                </div>
                                <div class="choice-select">
                                    <select name="emailDomain" id="emailDomain" class="tune" style="width: 120px; display: none;" tabindex="-1">
                                        <option value="self">(select)</option>
                                        <option value="naver.com">naver.com</option>
                                        <option value="hanmail.net">hanmail.net</option>
                                        <option value="daum.net">daum.net</option>
                                        <option value="nate.com">nate.com</option>
                                        <option value="hotmail.com">hotmail.com</option>
                                        <option value="gmail.com">gmail.com</option>
                                        <option value="icloud.com">icloud.com</option>
                                    </select><div class="chosen-container chosen-container-single chosen-container-single-nosearch" style="width: 120px;" title="" id="emailDomain_chosen"><a class="chosen-single chosen-sch" tabindex="-1"><span>(select)</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off" readonly="" tabindex="-1"></div><ul class="chosen-results"><li class="active-result result-selected" data-option-array-index="0">(select)</li><li class="active-result" data-option-array-index="1">naver.com</li><li class="active-result" data-option-array-index="2">hanmail.net</li><li class="active-result" data-option-array-index="3">daum.net</li><li class="active-result" data-option-array-index="4">nate.com</li><li class="active-result" data-option-array-index="5">hotmail.com</li><li class="active-result" data-option-array-index="6">gmail.com</li><li class="active-result" data-option-array-index="7">icloud.com</li></ul></div></div>


                                </div>
                            </div>
                            <div class="form-element">
                                <input type="checkbox" class="checkbox" id="maillingFl" name="maillingFl" value="y">
                                <label for="maillingFl" class="">I agree to receive email to this email
                                    address.</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="ta-l required">Mobile no.</th>
                        <td>
                            <div class="txt-field" style="display: inline-block;width:160px;">
                                <input type="text" id="cellPhone" name="cellPhone" class="text" maxlength="12" placeholder="Enter your number without -" data-pattern="gdNum" value="<?=$no;?>">
                            </div>
                            <div class="form-element">
                                <input type="checkbox" class="checkbox" id="smsFl" name="smsFl" value="y">
                                <label for="smsFl" class="">I agree to receive text messages to this mobile
                                    phone number.</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="ta-l">Telephone no.</th>
                        <td>
                            <div class="txt-field" style="display: inline-block;width:160px;">
                                <input type="text" id="phone" name="phone" class="text" maxlength="12" placeholder=" Enter your number without -" data-pattern="gdNum" value="<?=$tel;?>">
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- ????/?? ???? -->
            <!-- ????/?? ????? --><!-- ????/?? ????? -->
            <!-- ????/?? ???? --><!-- ????/?? ???? -->
        </fieldset>
        <div class="btn">
            <button type="button" class="skinbtn point1 j-cancel" id="btnCancel"><em>CANCEL</em></button>
            <button type="button" class="skinbtn point2 j-join btn-join" value="Sign UP"><em>UPDATE</em>
            </button>
        </div>
    </form>
</div>

<script type="text/javascript" src="/common/jquery.min.js"></script>
<script type="text/javascript" src="/common/chosen.jquery.min.js"></script>

<script type="text/javascript" src="/common/gd_ui.js"></script>
<script type="text/javascript" src="/common/gd_gettext.js"></script>

<script type="text/javascript" src="/common/underscore-min.js"></script>
<script type="text/javascript" src="/common/jquery.validate.min.js"></script>
<script type="text/javascript" src="/common/additional-methods.min.js"></script>
<script type="text/javascript" src="/common/numeral.min.js"></script>
<script type="text/javascript" src="/common/accounting.min.js"></script>
<script type="text/javascript" src="/common/money.min.js"></script>

<script type="text/javascript" src="/common/placeholders.jquery.min.js"></script>

<!--[if gt IE 8]-->
<script type="text/javascript" src="/common/ZeroClipboard.min.js"></script>
<!--[endif]-->

<script type="text/javascript" src="/common/jquery.vticker.js"></script>
<script type="text/javascript" src="/common/gd_member2.js"></script>

<script type="text/javascript" src="/common/moment.js"></script>
<script type="text/javascript" src="/common/ko.js"></script>
<script type="text/javascript" src="/common/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/common/jquery.serialize.object.js"></script>

<link href="/js/pgwslideshow.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/js/pgwslideshow_light.css" rel="stylesheet" type="text/css" media="screen" />
<script src="/js/pgwslideshow.js" type="text/javascript"></script>
<?
include '../footer.admin.php';
?>
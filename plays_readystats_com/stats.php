<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 9/6/14
 * Time: 7:27 AM
 */
$gameId = 1;
if(!empty($_REQUEST["g"]))
{
    $gameId = $_REQUEST["g"];
}
$teamId = 1;
if(!empty($_REQUEST["t"]))
{
    $teamId = $_REQUEST["t"];
}
$minperhalf = 14;
if(!empty($_REQUEST["m"]))
{
    $minperhalf = $_REQUEST["m"];
}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/pepper-grinder/jquery-ui.min.css" rel="stylesheet" />

    <link href="css/statstyle.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <![endif]-->

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/json2/20130526/json2.min.js"></script>
    <script type="text/javascript" src="js/stats.js"></script>
    <!-- Add custom CSS here -->
    <style>
    </style>

    <script language="JavaScript">
        var gameclockIntervalId = null;
        //
        // Document onload
        //
        $( document ).ready(function() {
            gameclockIntervalId = null;

            var gameid = <?php echo $gameId; ?>;
            $("#gamedata").data("gameid",gameid);
            var teamid = <?php echo $teamId; ?>;
            $("#gamedata").data("teamid",teamid);
            var minperhalf = <?php echo $minperhalf; ?>;
            $("#gamedata").data("minperhalf",minperhalf);
            $("#gamedata").data("gameclock",0);

            setupStatsPageLoad();

        });//document ready
    </script>
    <title>Game Stats! - ReadyStats.com </title>
</head>
<body>

<div id="tabs" style="position:relative">
        <ul>
            <li><a href="#tabs-1">Teams/Games</a></li>
            <li><a href="#tabs-2">Stats</a></li>
            <li><a href="#tabs-3">Log</a></li>
            <li><a href="#tabs-4">Report</a></li>
        </ul>
        <div id="tabs-1">
            Stat Keeper Name:<input type="text">
        </div>
    <div id="tabs-2" >
        <div id="slider" style="width:80%; cursor:pointer;">
            <div class="sliderlabels" style="left:0px">Stop</div>
            <div class="sliderlabels" style="left:20%">Start</div>
            <div class="sliderlabels" style="left:40%">TO</div>
            <div class="sliderlabels" style="left:60%">Half</div>
            <div class="sliderlabels" style="left:80%">End</div>
            <div class="sliderlabels" style="left:100%">OT</div>
            <div id="custom-handle" class="ui-slider-handle" style="background-color:#000000;"></div>
            <br>
        </div>
        <div class="ui-widget ui-helper-clearfix"> </br>&nbsp;</br> <span style="font-weight: bold; font-size:8pt;">OUT</span>
            <ul id="PlayerOut" class="PlayerOut ui-helper-reset ui-helper-clearfix"></ul>
        </div>
        <div class="ui-widget ui-helper-clearfix">
            <ul id="PlayerIn" class="PlayerIn ui-helper-reset ui-helper-clearfix">
                <li class='opponent' id='player_1000'>X</li>
            </ul>
        </div>
        <div id="statsContainer"  class="ui-widget-content ui-state-default">
            <div style="position: relative;">
                >>&nbsp;&nbsp;<span id="livestatus"></span>
            </div>
            <div class="statContainer" style="position: relative;">
                        <ul class="StatsDrop">
                            <li><div class="stat" id="stat_1" ><span class="stattext">FG Made</span></div></li>
                            <li><div class="stat" id="stat_2" ><span class="stattext">FG Missed</span></div></li>
                            <li><div class="stat" id="stat_8" ><span class="stattext">Rebound</span></div></li>
                            <li><div class="stat" id="stat_3"><span class="stattext">Assist</span></div></li>
                            <li><div class="stat" id="stat_7"><span class="stattext">Steal</span></div></li>
                            <li><div class="stat" id="stat_6"><span class="stattext">Turnover</span></div></li>
                            <li><div class="stat" id="stat_4"><span class="stattext">Fouled</span></div></li>
                            <li><div class="stat" id="stat_19"><span class="stattext">3-PT Made</span></div></li>
                            <li><div class="stat" id="stat_15"><span class="stattext">Made FT</span></div></li>
                            <li><div class="stat" id="stat_16"><span class="stattext">Miss FT</span></div></li>
                        </ul>
                </div>
        </div>
    </div>
    <div id="tabs-3">
        <input type="button" onclick="getStatGameLog()" value="reload" style="font-size: 8pt;">
        <table id="tableGameLog">
            <tr>
                <th>When</th>
                <th>Stat</th>
                <th>Action</th>
            </tr>

        </table>
    </div>
    <div id="tabs-4">
        <div id="report">
            <div id="teamreport">
                Team Stats
                <table id="tableTeamReport"  cellspacing="0" cellpadding="3" borer="0"></table>
            </div>
            <div id="playerreport">
                Player Stats
                <table id="tablePlayerReport" cellspacing="0" cellpadding="3" borer="0"></table>
            </div>
        </div>
    </div>
</div>
<div id="playerdata"></div>
<div id="statdata"></div>
<div id="gamelogdata"></div>
<div id="gamedata"></div>
</body>
</html>
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
    <!-- Add custom CSS here -->
    <style>
        .playerHighlight{
         border:2px solid red;
        }
        td{
            vertical-align: middle;
            top:50%;
        }
        .player{
            font-size:9pt;
            font-weight:normal;
            padding-top: 1.8em;
            z-index: 99;
        }
        .PlayerIn li.opponent{
            height:40px;
            width: 40px;
            color: #fff;
            background-color: #222;
            border:3px solid #000;
            border-radius: 40px;
            z-index: 99;
            font-size:14pt;
            font-weight: bolder;
            padding-top: 0.8em;
        }
        .stat-hover{
            border: 1px dotted #f00;
            background-color: #eee;
            color:#f00;
        }
        .stat-accepted{
            border: 3px dotted #002a80;
            background-color: #ffff00;
            color:#002a80;
        }
        .opponent{
            background-color: #888;
            z-index: 100;
        }
        tr.gamelogrow{
            height:20px;
            width:100%;
        }
        tr.gamelogrow-a{background-color: #aaa;}
        tr.gamelogrow-b{background-color: #fff;}

        div#report table td{
            border:1px solid #aaa;
            font-size: 10pt;
            text-align:left;
            font-weight: bolder;
            background-color: #fff;
            color: #333;
        }
        div#report table th{
            border:0px solid #888;
            font-size: 8pt;
            font-weight: bolder;
            text-align:center;
            background-color: transparent;
        }

        div#report table th.teamname, div#report table th.playername{
            border:1px solid #888;
            font-size: 12pt;
            font-weight: bolder;
            text-align:left;
            color:#000;
            background-color: #fff;
        }
        div#report table th.teamname{
            border:1px solid #888;
            font-size: 12pt;
            font-weight: bolder;
            text-align:left;
            color:#aaa;
            background-color: #000;
        }
        hr {
            display: block;
            height: 1px;
            border: 0;
            border-top: 1px solid #888;
            margin: 1em 0;
            padding: 0;
        }
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

            $( "#tabs" ).tabs({
                active:1,
                activate: function(event, ui){
                    $tabSelectedA = $(ui.newTab);
                    $tabSelectedID = $($tabSelectedA.context).attr("id");
                    if($tabSelectedID == 'ui-id-3'){getStatGameLog()}
                    if($tabSelectedID == 'ui-id-4'){getReport()}
                }
            });
            getPlayerList();
            getStatList();

            var $PlayerOut = $( "#PlayerOut" ),
                $PlayerIn = $( "#PlayerIn"),
                $StatList = $("ul.StatsDrop");

            $StatList.sortable();
            $StatList.disableSelection();


            var handle = $( "#custom-handle" );
            $( "#slider" ).slider({
                value:0,
                min:0,
                max:100,
                step:20,
                create: function() {
                    var minperhalf = $("#gamedata").data("minperhalf");
                    minperhalf = !isNaN(parseInt(minperhalf,10)) ? minperhalf : 14;

                    handle.text(minperhalf+ ":00");
                    handle.css("color","#fff");
                    handle.css("background-color","#000");
                    $("#gamedata").data("currentgamestatus","stopped");
                },
                slide: function( event, ui ) {
                    switch(parseInt(ui.value,10)) {
                        case "0": // OFF
                        default:
                            console.log("Off");
                            clearInterval(gameclockIntervalId);
                            gameclockIntervalId=null;
                            handle.css("color","#fff");
                            handle.css("background-color","#000");
                            $("#gamedata").data("currentgamestatus","stopped");
                            break;

                        case 20: // Start
                            console.log("Start");
                            if(gameclockIntervalId == null) {
                                gameclockIntervalId = setInterval("setGameClock()", 1010);
                            }else{
                                console.log("gameclockIntervalId = "+gameclockIntervalId);
                            }

                            handle.css("color","#fff");
                            handle.css("background-color","#0a0");
                            var gameclock = $("#gamedata").data("gameclock");
                            currentgamestatus = $("#gamedata").data("currentgamestatus");
                            console.log("gameclock ="+gameclock );
                            console.log("currentgamestatus ="+currentgamestatus );
                            if(gameclock < 2 && currentgamestatus != "half") {
                                clickGameStat("start");
                            }else{
                                if(currentgamestatus != "stopped"){
                                    clickGameStat("timein");
                                }//if
                            }//else if

                            $("#gamedata").data("currentgamestatus","start");
                            break;

                        case 40: // TO
                            console.log("Game Timeout");
                            clearInterval(gameclockIntervalId);
                            gameclockIntervalId=null;
                            handle.css("color","#fff");
                            handle.css("background-color","#a00");
                            clickGameStat("timeout");
                            $("#gamedata").data("currentgamestatus","timeout");
                            break;

                        case 60: // HALF
                            console.log("Game Half");
                            clearInterval(gameclockIntervalId);
                            gameclockIntervalId=null;
                            $("#gamedata").data("gameclock",0);
                            handle.css("color","#fff");
                            handle.css("background-color","#a00");
                            clickGameStat("half");
                            $("#gamedata").data("currentgamestatus","half");
                            break;

                        case 80: // End
                            console.log("Game End");
                            clearInterval(gameclockIntervalId);
                            gameclockIntervalId=null;
                            handle.css("color","#fff");
                            handle.css("background-color","#00b");
                            clickGameStat("end");
                            $("#gamedata").data("currentgamestatus","end");
                            break;

                        case 100: // OT
                            console.log("Game Overtime");
                            clearInterval(gameclockIntervalId);
                            gameclockIntervalId=null;
                            $("#gamedata").data("gameclock",0);
                            handle.text("00:00");
                            handle.css("color","#fff");
                            handle.css("background-color","#00b");
                            clickGameStat("overtime");
                            $("#gamedata").data("currentgamestatus","overtime");
                            break;

                    }//switch
                }
            });

            // Let the PlayerOut items be draggable
            $( "li", $PlayerOut ).draggable({
                revert: "invalid", // when not dropped, the item will revert back to its initial position
                containment: "document",
                helper: "clone",
                cursor: "move"
            });


            // Let the playerin be droppable, accepting the gallery items
            $PlayerIn.droppable({
                accept: "#PlayerOut > li",
                classes: {
                    "ui-droppable-active": "stat-accepted"
                },
                drop: function( event, ui ) {
                    PlayerInOut("IN", ui.draggable);
                }
            });

            // Let the gallery be droppable as well, accepting items from the trash
            $PlayerOut.droppable({
                accept: "#PlayerIn li",
                classes: {
                    "ui-droppable-active": "stat-accepted"
                },
                drop: function( event, ui ) {
                    PlayerInOut("OUT", ui.draggable);
                }
            });

            $( ".stat" ).droppable({
                accept: "#PlayerIn li",
                classes: {
                    "ui-droppable-hover": "stat-hover",
                    "ui-droppable-active": "stat-accepted"
                },
                drop: function( event, ui ) {dropAction(event, ui.draggable); },
                over: function( event, ui ) {$(event.target).addClass("stat-hover");},
                out: function( event, ui ) {$(event.target).removeClass("stat-hover");}
            });

            function clickGameStat(gamestatname)
            {
                var statid= 0, statnote="";

                switch(String(gamestatname).toLowerCase())
                {
                    case "start":
                    default:
                        statid = 9;
                        statnote = "game start";
                        break;

                    case "timeout":
                        statid = 12;
                        statnote = "timeout";
                        break;

                    case "timein":
                        statid = 22;
                        statnote = "time in";
                        break;

                    case "half":
                        statid = 10;
                        statnote = "halftime";
                        break;

                    case "end":
                        statid = 11;
                        statnote = "end";
                        break;

                    case "overtime":
                        statid = 22;
                        statnote = "overtime";
                        break;
                }//switch

                eSid=statid;
                eGid=$("#gamedata").data("gameid");
                ePid=1000;
                eStatKeeper="auto fill";
                eNote = statnote;
                eMeasurement = 1;
                eMeasurement = eSid==19 ? 3 : eMeasurement;
                eMeasurement = eSid==1 ? 2 : eMeasurement;
                eGameClock = $("#gamedata").data("gameclock");
                eGameClock = !isNaN(parseInt(eGameClock,10)) ? eGameClock : 0;

                $("#livestatus").html(eNote);

                console.log("sending:a=r&t=addstat&gid="+eGid+"&pid="+ePid+"&sid="+eSid+"&n="+eNote+"&sk="+eStatKeeper+"&m="+eMeasurement+"&gc="+eGameClock);
                $.ajax({
                    type: "GET",
                    url: "crud.php?a=r&t=addstat&gid="+eGid+"&pid="+ePid+"&sid="+eSid+"&n="+eNote+"&sk="+eStatKeeper+"&m="+eMeasurement+"&gc="+eGameClock,
                    dataType: "json"
                }).done(function( data ) {
                    console.log("data saved");
                });// post playerin/out stat

            }//clickGameStat


            // Player Drop IN OUT function
            function PlayerInOut(inOut, $item ) {
                $statid = "";
                if(inOut == "IN")
                {
                    $item.hide().appendTo($PlayerIn).show();
                    $statid = "stat_13";
                }else{ // OUT
                    $item.hide().appendTo($PlayerOut).show();
                    $statid = "stat_14";
                }
                var StatItem = $("#statdata").data($statid);
                var PlayerItem = $("#playerdata").data($item.attr("id"));

                eSid=StatItem.ID;
                eGid=$("#gamedata").data("gameid");
                ePid=PlayerItem.PlayerID;
                eStatKeeper="auto fill";
                eNote = StatItem.Name + " " + PlayerItem.FirstName + " " + PlayerItem.LastName;
                $("#livestatus").html(eNote);
                eMeasurement = 1;
                eMeasurement = eSid==19 ? 3 : eMeasurement;
                eMeasurement = eSid==1 ? 2 : eMeasurement;
                eGameClock = $("#gamedata").data("gameclock");
                eGameClock = !isNaN(parseInt(eGameClock,10)) ? eGameClock : 0;

                console.log("sending:a=r&t=addstat&gid="+eGid+"&pid="+ePid+"&sid="+eSid+"&n="+eNote+"&sk="+eStatKeeper+"&m="+eMeasurement+"&gc="+eGameClock);
                $.ajax({
                    type: "GET",
                    url: "crud.php?a=r&t=addstat&gid="+eGid+"&pid="+ePid+"&sid="+eSid+"&n="+eNote+"&sk="+eStatKeeper+"&m="+eMeasurement+"&gc="+eGameClock,
                    dataType: "json"
                }).done(function( data ) {
                    console.log("data saved");
                });// post playerin/out stat

            }//PlayerInOut
        });//document ready


        function setGameClock()
        {
            var minperhalf = $("#gamedata").data("minperhalf");
            var secPerHalf = !isNaN(parseInt(minperhalf,10)) ? parseInt(minperhalf,10)*60 : 960;

            var gametime = $("#gamedata").data("gameclock");
            gametime = !isNaN(parseInt(gametime)) ? gametime + 1 : 0;
            $("#gamedata").data("gameclock",gametime);

            var gameclock = secPerHalf - gametime;


            var sec = gameclock % 60;
            var min = Math.floor(gameclock/60);

            var minStr = min < 10 ? "0"+String(min) : String(min);
            var secStr = sec < 10 ? "0"+String(sec) : String(sec);
            var displayStr = minStr + ":" + secStr;

            if(min >= 30){
                displayStr = "30:00";
            }

            $("#custom-handle").text(displayStr);

        }//setGameClock


        function dropAction(event, $droppedItem)
        {
            console.log($droppedItem);
            $statItem = event.target;

            $($statItem).removeClass("stat-hover");


            var eSid=0, eGid=0,ePid=0, eStatKeeper=0, eNote=0;
            var StatItem = $("#statdata").data($statItem.id);
            var PlayerItem = $("#playerdata").data($droppedItem.attr("id"));
            console.log(PlayerItem);
            console.log(StatItem);

            eSid=StatItem.ID;
            eGid=$("#gamedata").data("gameid");
            ePid=PlayerItem.PlayerID;
            eStatKeeper="auto fill";
            eNote = StatItem.Name + " " + PlayerItem.FirstName + " " + PlayerItem.LastName;
            eGameClock = $("#gamedata").data("gameclock");
            eGameClock = !isNaN(parseInt(eGameClock,10)) ? eGameClock : 0;

            switch(String($statItem.id).toLowerCase())
            {
                default:
                    $("#livestatus").html(eNote);
                    break;
            }//switch stat item
            eMeasurement = 1;
            eMeasurement = eSid==19 ? 3 : eMeasurement;
            eMeasurement = eSid==1 ? 2 : eMeasurement;

            console.log("sending:a=r&t=addstat&gid="+eGid+"&pid="+ePid+"&sid="+eSid+"&n="+eNote+"&sk="+eStatKeeper+"&m="+eMeasurement+"&gc="+eGameClock);
            $.ajax({
                type: "GET",
                url: "crud.php?a=r&t=addstat&gid="+eGid+"&pid="+ePid+"&sid="+eSid+"&n="+eNote+"&sk="+eStatKeeper+"&m="+eMeasurement+"&gc="+eGameClock,
                dataType: "json"
            }).done(function( data ) {
                console.log("data saved");
            });


            //$gameid = $_REQUEST["gid"];
            //$playerid = $_REQUEST["pid"];
            //$statid = $_REQUEST["sid"];
            //$note = $_REQUEST["n"];
            //$statkeeper = $_REQUEST["sk"];

        }//dropAction

        function getPlayerList()
        {
            var $PlayerOut = $( "#PlayerOut" ),
                $PlayerIn = $( "#PlayerIn" );

            teamid = $("#gamedata").data("teamid");
            var baseLeft = 1;
            $.ajax({
                type: "GET",
                url: "crud.php?a=r&t=playerlist&id="+teamid,
                dataType: "json"
            }).done(function( data ) {

                var left=0,top=0,playername="";
                for(i=0; i<data.length; i++){

                    playnum = (data[i]).PlayerNumber == "" ? "" : "#"+(data[i]).PlayerNumber;
                    playername = playnum +" "+(data[i]).FirstName + " " + String((String((data[i]).LastName).split(''))[0]);
                    playerDivid = (data[i]).PlayerID;

                    //store player data
                    $("#playerdata").data('player_'+playerDivid, data[i]);

                        left = left+baseLeft+107;
                        if(i%4==0)
                        {
                            left = 0;
                            top = top + 42
                        }//break line

                    if(String(data[i].FirstName).toLowerCase() != "opponent")
                    {
                        if(parseInt((data[i]).isPlayerIn,10) == 0)
                        {
                            $("#PlayerOut").append(" <li class='ui-corner player' id='player_"+playerDivid+"'>"+playername+"</li>");
                        }else{
                            $("#PlayerIn").append(" <li class='ui-corner player' id='player_"+playerDivid+"'>"+playername+"</li>");
                        }
                    }//isnot opponent
                }//for
                left = left+baseLeft+107;
                if(i%4==0)
                {
                    left = 0;
                    top = top + 38
                }//break line
                //$("#PlayerList").append(" <div class='player' id='player_34' style='background-color:#888; height:50px; left:"+left+"; top:"+top+"'>&nbsp;<br>Opponent</div>");
                //$("div.player").draggable({ revert: true });
                // Let the PlayerOut items be draggable
                $( "li", $PlayerOut ).draggable({
                    revert: "invalid", // when not dropped, the item will revert back to its initial position
                    containment: "document",
                    helper: "clone",
                    cursor: "move"
                });
                $( "li", $PlayerIn ).draggable({
                    revert: "invalid", // when not dropped, the item will revert back to its initial position
                    containment: "document",
                    helper: "clone",
                    cursor: "move"
                });


                $("#livestatus").css("top",top+50);
            });
        }//getPlayerList


        function getStatList()
        {
            var baseLeft = 1;
            $.ajax({
                type: "GET",
                url: "crud.php?a=r&t=statlist",
                dataType: "json"
            }).done(function( data ) {
                for(i=0; i<data.length; i++){
                    stid = (data[i]).ID;
                    //store stats data
                    $("#statdata").data('stat_'+stid, data[i]);
                    console.log('storing '+data[i]);
                }//for
            });
        }//getStatList

        function getStatGameLog()
        {
            var minperhalf = $("#gamedata").data("minperhalf");
            var secPerHalf = !isNaN(parseInt(minperhalf,10)) ? parseInt(minperhalf,10)*60 : 960;

            gameid = $("#gamedata").data("gameid");
            var baseLeft = 1;
            $.ajax({
                type: "GET",
                url: "crud.php?a=r&t=statlog&gid="+gameid,
                dataType: "json"
            }).done(function( data ) {
                var headerRow = "<tr><th>When</th><th>Stat</th><th>Action</th></tr>";
                var dataRow = "";
                var playernamestat = "";
                $tableGameLog = $("#tableGameLog");
                $tableGameLog.html(headerRow);
                for(i=0; i<data.length; i++){
                    stid = (data[i]).statitemid;
                    //store player data
                    $("#gamelogdata").data('gamelog_'+stid, data[i]);
                    playernamestat = data[i].name+" "+data[i].FirstName+" " + data[i].LastName;
                    timedisplayVal  = secPerHalf - data[i].secondsfromstart;
                    var sec = timedisplayVal % 60;
                    var min = Math.floor(timedisplayVal/60);

                    var minStr = min < 10 ? "0"+String(min) : String(min);
                    var secStr = sec < 10 ? "0"+String(sec) : String(sec);
                    var displayStr = minStr + ":" + secStr;

                    if(timedisplayVal < 0){
                        displayStr = "00:00";
                    }

                    rowToggle = i % 2 ? 'a' : 'b';
                    var dataRow = "<tr class='gamelogrow gamelogrow-"+rowToggle+"'><td>"+displayStr+"</td>" +
                        "<td>"+playernamestat+"</td>" +
                        "<td><div class='ui-state-default ui-corner-all' onclick='deleteLogClickEvent(this)' id='gamelog_"+stid+"'><span class='ui-icon ui-icon-closethick'></span></div></td></tr>";
                    $tableGameLog.append(dataRow);
                }//for
            });
        }//getStatList

        function deleteLogEvent(statgameid)
        {
            var statItem = $("#gamelogdata").data(statgameid);

            var sid = statItem.statitemid;
            $.ajax({
                type: "GET",
                url: "crud.php?a=r&t=deletestat&sid="+sid
            }).done(function( data ) {
                console.log("data item removed");
                setTimeout("getStatGameLog()",1);
            });

        }//deleteLogEvent


        function deleteLogClickEvent(item)
        {
                var statgameid = $(item).attr("id");
                deleteLogEvent(statgameid);
        }//

        function getReport()
        {
            gameid = $("#gamedata").data("gameid");
            $.ajax({
                type: "GET",
                url: "crud.php?a=r&t=gamereport&gid="+gameid,
                dataType: "json"
            }).done(function( data ) {
                console.log(data);
                $("#tableTeamReport").html("");
                $("#tablePlayerReport").html("");
                var $statsReport = new Object();
                var $teamReport = {total_shots:0, made_shots:0, missed_shots:0, rebounds:0, steals:0, turnovers:0, points:0, assists:0};
                for(i=0; i<data.length; i++){
                    $respItem = data[i];
                    $player = typeof $statsReport[$respItem.playerid] != "undefined" ? $statsReport[$respItem.playerid] : {total_shots:0, made_shots:0, missed_shots:0, rebounds:0, steals:0, turnovers:0, points:0, assists:0, name:$respItem.firstname+" "+$respItem.lastname};
                    $value = parseInt($respItem.value,10);
                    switch(parseInt($respItem.stattypeid,10))
                    {
                        case 1: // made shot
                            $player.made_shots = $value/2;
                            $player.points += $value;
                            $player.total_shots += $value/2;
                            if($respItem.playerid != 1000)
                            {
                                $teamReport.made_shots += $value/2;
                                $teamReport.points += $value;
                                $teamReport.total_shots += $value/2;
                            }
                            break;

                        case 19: // made 3pt shot
                            $player.made_shots += $value/3;
                            $player.points += $value;
                            $player.total_shots += $value/3;
                            if($respItem.playerid != 1000)
                            {
                                $teamReport.made_shots += $value/3;
                                $teamReport.points += $value;
                                $teamReport.total_shots += $value/3;
                            }
                            break;


                        case 15: // made FT shot
                            $player.points += $value;
                            break;

                        case 2: // missed shot
                            $player.missed_shots = $value;
                            $player.total_shots += $value;
                            if($respItem.playerid != 1000)
                            {
                                $teamReport.missed_shots += $value;
                                $teamReport.total_shots += $value;
                            }
                            break;

                        case 6: // turnover
                            $player.turnovers += $value;
                            if($respItem.playerid != 1000)
                            {
                                $teamReport.turnovers += $value;
                            }
                            break;

                        case 7: // steals
                            $player.steals += $value;
                            if($respItem.playerid != 1000)
                            {
                                $teamReport.steals+= $value;
                            }
                            break;

                        case 3: // assists
                            $player.assists += $value;
                            if($respItem.playerid != 1000)
                            {
                                $teamReport.assists+= $value;
                            }
                            break;

                        case 5: // rebounds
                        case 8: // rebounds
                            $player.rebounds += $value;
                            if($respItem.playerid != 1000)
                            {
                                $teamReport.rebounds += $value;
                            }
                            break;

                        case 9://game start
                        case 10://halftime
                        case 11://game end
                        case 12://timeout
                        case 13://player out
                        case 14://player out
                            // do nothing for these
                            break;

                        default: //untracked stat
                             console.log("unknown stat" + $respItem.name);
                            $player[$respItem.name] += $value;
                            break;
                    }//switch stattypeid

                    $statsReport[$respItem.playerid] = $player;
                }//for
                console.log($statsReport);
                var headerCols = "<tr><th colspan=3>Pts / Shooting</th><th>Rebounds</th><th>Steals</th><th>Assists</th><th>Turnovers</th></tr>";
                var teamHeaderRow = "<tr><th colspan=7  class='teamname'>Team</th></tr>"+headerCols;
                var opponentHeaderRow = "<tr><td colspan=6 style='background-color: transparent; border:0px solid transparent;'>&nbsp;</td></tr><tr><th colspan=5 class='teamname'>Opponent</th></tr>"+headerCols;
                var dataRow = "";
                $player = $statsReport[1000];
                shotPercent = Math.round(($player.made_shots/$player.total_shots)*100);
                shotPercent = isNaN(shotPercent) ? 0 : shotPercent;
                opponentDataRow = "<tr><td>"+$player.points+"</td><td>"+$player.made_shots+"/"+$player.total_shots+"</td><td>"+shotPercent+"%</td><td>"+$player.rebounds+"</td><td>"+$player.steals+"</td><td>"+$player.assists+"</td><td>"+$player.turnovers+"</td></tr>";
                $player = $teamReport;
                shotPercent = Math.round(($player.made_shots/$player.total_shots)*100);
                shotPercent = isNaN(shotPercent) ? 0 : shotPercent;
                teamDataRow = "<tr><td>"+$player.points+"</td><td>"+$player.made_shots+"/"+$player.total_shots+"</td><td>"+shotPercent+"%</td><td>"+$player.rebounds+"</td><td>"+$player.steals+"</td><td>"+$player.assists+"</td><td>"+$player.turnovers+"</td></tr>";
                $("#tableTeamReport").append(teamHeaderRow);
                $("#tableTeamReport").append(teamDataRow);
                $("#tableTeamReport").append(opponentHeaderRow);
                $("#tableTeamReport").append(opponentDataRow);
                $("#tableTeamReport").append("<tr><td colspan=5 style='background-color: transparent; border:0px solid transparent;'>&nbsp;<br>&nbsp;</td></tr>");

                for($p in $statsReport)
                {
                    //console.log($p);
                    if($p != 1000) // only team players
                    {
                        $player = $statsReport[$p];
                        //console.log($player);
                        shotPercent = Math.round(($player.made_shots/$player.total_shots)*100);
                        shotPercent = isNaN(shotPercent) ? 0 : shotPercent;
                        playerHeaderRow = "<tr class='playerreportth'><th colspan=7 class='playername'>"+$player.name+"</th></tr>"+headerCols;
                        playerDataRow = "<tr  class='playerreporttd'><td>"+$player.points+"</td><td>"+$player.made_shots+"/"+$player.total_shots+"</td><td>"+shotPercent+"%</td><td>"+$player.rebounds+"</td><td>"+$player.steals+"</td><td>"+$player.assists+"</td><td>"+$player.turnovers+"</td></tr>";
                        $("#tablePlayerReport").append(playerHeaderRow);
                        $("#tablePlayerReport").append(playerDataRow);
                        $("#tablePlayerReport").append("<tr><td colspan=5 style='background-color: transparent; border:0px solid transparent;'>&nbsp;<hr/>&nbsp;</td></tr>");
                    }//is only team player condition
                }//foreach dissplay
            });
        }//getReport

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
                            <li><div class="stat" id="stat_1" >FG Made</div></li>
                            <li><div class="stat" id="stat_2" >FG Missed</div></li>
                            <li><div class="stat" id="stat_8" >Rebound</div></li>
                            <li><div class="stat" id="stat_7">Steal</div></li>
                            <li><div class="stat" id="stat_6">Turnover</div></li>
                            <li><div class="stat" id="stat_4">Fouled</div></li>
                            <li><div class="stat" id="stat_19">3-PT Made</div></li>
                            <li><div class="stat" id="stat_15">Made FT</div></li>
                            <li><div class="stat" id="stat_16">Miss FT</div></li>
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
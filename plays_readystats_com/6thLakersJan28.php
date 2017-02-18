<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 9/6/14
 * Time: 7:27 AM
 */
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/pepper-grinder/jquery-ui.min.css" rel="stylesheet" />

    <link href="css/gamestyle.css" rel="stylesheet">

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
        body {
            /*margin-top: 60px;*/
			background-color:#dddddd
        }
        hr{
            color: #223322;
            background-color: #223322;
            height: 2px;    }
        span.bywho{
            font-size: 8pt;
        }
        span.alert{
            background-color: #ffff00;
            color:#d2322d;
        }
        #content_row td button{
        }
        #content_row td{
            padding:10px;
            height:20px;
            text-align: center;
        }
        .playerHighlight{
         border:2px solid red;
        }
        td{
            vertical-align: middle;
            top:50%;
        }
        .stat{
            z-index: -1;
            height:85px;
            width:100%;
            background-color: #000;
            color:#fff;
            text-align: center;
            border: 1px solid #fff;
        }
        .gamestat{
            z-index: -1;
            height:85px;
            width:100%;
            background-color: #333;
            color:#ddd;
            text-align: center;
            border: 1px solid #fff;
        }
        div.playerin{
            background-color: #585;
            border: 1px solid #181;
        }
        div.stat-hover{
            border: 1px dotted #f00;
            background-color: #eee;
            color:#f00;
        }
        .teamplayer{
            background-color:darkorange;
            z-index: 99;
            font-size: 10pt;
            width: 110px;
            height: 75px;
            vertical-align: middle;
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

    </style>

    <script language="JavaScript">
        //
        // Document onload
        //
        $( document ).ready(function() {
            $( "#tabs" ).tabs({
                active:1,
                activate: function(event, ui){
                    $tabSelectedA = $(ui.newTab);
                    $tabSelectedID = $($tabSelectedA.context).attr("id");
                    if($tabSelectedID == 'ui-id-3'){getStatGameLog()}
                }
            });
            getPlayerList();
            getStatList();

            $( ".stat, .gamestat" ).droppable({
                classes: {
                    "ui-droppable-hover": "stat-hover"
                },
                drop: function( event, ui ) {dropAction(event, ui); },
                over: function( event, ui ) {$(event.target).addClass("stat-hover");},
                out: function( event, ui ) {$(event.target).removeClass("stat-hover");}
            });

        });//document ready





        function dropAction(event, ui)
        {
            $droppedItem = event.toElement;
            $statItem = event.target;

            $($statItem).removeClass("stat-hover");


            var eSid=0, eGid=0,ePid=0, eStatKeeper=0, eNote=0;
            var StatItem = $("#statdata").data($statItem.id);
            var PlayerItem = $("#playerdata").data($droppedItem.id);
            console.log(PlayerItem);
            console.log(StatItem);

            eSid=StatItem.ID;
            eGid=1;
            ePid=PlayerItem.PlayerID;
            eStatKeeper="auto fill";
            eNote = StatItem.Name + " " + PlayerItem.FirstName + " " + PlayerItem.LastName;

            switch(String($statItem.id).toLowerCase())
            {
                case "stat_13":
                    $($droppedItem).addClass("playerin");
                    break; // playerin

                case "stat_14":
                    $($droppedItem).removeClass("playerin");
                    break; //playerout

                default:
                    console.log(eNote);
                    $("#livestatus").html(eNote);
                    break;
            }//switch stat item

            console.log("sending:a=r&t=addstat&gid="+eGid+"&pid="+ePid+"&sid="+eSid+"&n="+eNote+"&sk="+eStatKeeper);
            $.ajax({
                type: "GET",
                url: "crud.php?a=r&t=addstat&gid="+eGid+"&pid="+ePid+"&sid="+eSid+"&n="+eNote+"&sk="+eStatKeeper,
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
            var baseLeft = 1;
            $.ajax({
                type: "GET",
                url: "crud.php?a=r&t=playerlist&id=1",
                dataType: "json"
            }).done(function( data ) {

                var left=0,top=0,playername="";
                for(i=0; i<data.length; i++){

                    playnum = (data[i]).PlayerNumber == "" ? "" : "#"+(data[i]).PlayerNumber;
                    playername = playnum +" "+(data[i]).FirstName + " " + String((String((data[i]).LastName).split(''))[0]);
                    playerDivid = (data[i]).PlayerID;
                    isPlayerInClass  = parseInt((data[i]).isPlayerIn,10) == 0 ? "" : "playerin";
                    opponentClass = String(data[i].FirstName).toLowerCase() != "opponent"? "teamplayer":"opponent";
                    //store player data
                    $("#playerdata").data('player_'+playerDivid, data[i]);

                        left = left+baseLeft+107;
                        if(i%4==0)
                        {
                            left = 0;
                            top = top + 42
                        }//break line

                        $("#PlayerList").append(" <div class='player "+isPlayerInClass+" "+opponentClass+"' id='player_"+playerDivid+"' style='height:45px; left:"+left+"; top:"+top+"'>&nbsp;<br>"+playername+"</div>");

                }//for
                left = left+baseLeft+107;
                if(i%4==0)
                {
                    left = 0;
                    top = top + 38
                }//break line
                //$("#PlayerList").append(" <div class='player' id='player_34' style='background-color:#888; height:50px; left:"+left+"; top:"+top+"'>&nbsp;<br>Opponent</div>");
                $("div.player").draggable({ revert: true });
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

            var baseLeft = 1;
            $.ajax({
                type: "GET",
                url: "crud.php?a=r&t=statlog&gid=1",
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
                    rowToggle = i % 2 ? 'a' : 'b';
                    var dataRow = "<tr class='gamelogrow gamelogrow-"+rowToggle+"'><td>"+data[i].datecreated+"</td>" +
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
    </script>
    <title>Game Stats! - ReadyStats.com </title>
</head>
<body>

<div id="tabs" style="position:relative">
        <ul>
            <li><a href="#tabs-1">Game</a></li>
            <li><a href="#tabs-2">Stats</a></li>
            <li><a href="#tabs-3">Log</a></li>
            <li><a href="#tabs-4">Report</a></li>
        </ul>
        <div id="tabs-1">
            Stat Keeper Name:<input type="text">
        </div>
    <div id="tabs-2"  style="position:relative">
        <div id="PlayerList"></div>
        <div style="position: absolute; left:10px; top:190px; width:500px;">Last Stat:<span id="livestatus"></span></div>
        <div style="position: absolute; left:0px; top:200px; width:100%">
            <table width="100%" cellspacing="3" border="0" cellpadding="3"">
                <tr>
                    <td colspan="2" width="50%"><div class="stat" id="stat_13">&nbsp;<br>In</div></td>
                    <td colspan="2" width="50%"><div class="stat" id="stat_14">&nbsp;<br>Out</div></td>
                </tr>
                <tr>
                    <td><div class="stat" id="stat_1">&nbsp;<br>Shot Made</div></td>
                    <td><div class="stat" id="stat_2">&nbsp;<br>Shot Missed</div></td>
                    <td><div class="stat" id="stat_3">&nbsp;<br>Assist</div></td>
                    <td><div class="stat" id="stat_4">&nbsp;<br>Fouled</div></td>
                </tr>
                <tr>
                    <td><div class="stat" id="stat_5">&nbsp;<br>Off Rebound</div></td>
                    <td><div class="stat" id="stat_8">&nbsp;<br>Def Rebound</div></td>
                    <td><div class="stat" id="stat_7">&nbsp;<br>Steal</div></td>
                    <td><div class="stat" id="stat_6">&nbsp;<br>Turnover</div></td>
                </tr>
                <tr>
                    <td><div class="gamestat" id="stat_9">&nbsp;<br>GAME START</div></td>
                    <td><div class="gamestat" id="stat_12">&nbsp;<br>TIMEOUT</div></td>
                    <td><div class="gamestat" id="stat_10">&nbsp;<br>HALFTIME</div></td>
                    <td><div class="gamestat" id="stat_11">&nbsp;<br>GAME END</div></td>
                </tr>
            </table>
        </div>
    </div>
    <div id="tabs-3">
        <table id="tableGameLog">
            <tr>
                <th>When</th>
                <th>Stat</th>
                <th>Action</th>
            </tr>

        </table>
    </div>
    <div id="tabs-4">game report</div>
</div>
<div id="playerdata"></div>
<div id="statdata"></div>
<div id="gamelogdata"></div>
</body>
</html>
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
    </style>

    <script language="JavaScript">
        //
        // Document onload
        //
        $( document ).ready(function() {
            //populateGameList();
            getPlayerList();
        });//document ready

        function getPlayerList()
        {
            var baseLeft = 1;
            $.ajax({
                type: "GET",
                url: "crud.php?a=r&t=playerlist",
                dataType: "json"
            }).done(function( data ) {
                var left=0,top=0,playername="";
                for(i=0; i<data.length; i++){
                    playername = (data[i]).FirstName + " " + String((String((data[i]).LastName).split(''))[0]);
                    left = left+baseLeft+80;
                    if(i%4==0)
                    {
                        left = 0;
                        top = top + 28
                    }//break line
                    $("#PlayerList").append(" <div class='player' style='left:"+left+"; top:"+top+"'>"+playername+"</div>");
                }//for
                $("div.player").draggable({ revert: true });
            });
        }//getPlayerList

        function save()
        {
            var event = $("#game").data("event");
            var players = $("#game").data("playerArray");
            var gametitle = $("#gametitle").val();
            var gameopp = $("#gameopp").val();
            var gameDataToSend = {event:event,players:players, gametitle:gametitle, opponent:gameopp};
            console.log(gameDataToSend);
            var epochTime = (new Date()).getTime();
                $.ajax({
                    type: "POST",
                    url: "crud.php?d="+epochTime,
                    data: { data: JSON3.stringify(gameDataToSend), a: "c" }
                })
                    .done(function( msg ) {
                        console.log("new data saved");
                        $("#gamelogSaved").html("<span>"+JSON3.stringify(gameDataToSend)+"</span>");
                    });

            clearData();
        }//save

        function populateGameList()
        {
            //http://plays.readystats.com/crud.php
                $.ajax({
                    type: "GET",
                    url: "crud.php?a=r&t=gamelist",
                    dataType: "json"
                }).done(function( data ) {
                    for(i in data){
                        $("#gametitlelist").append("<option value='"+data[i]+"'>"+i+"</option>");
                    }//for
                 });
        }//populateGameList

        function clearData(){
            $("#game").data("playerArray",[]);
            $("#game").data("event",null);
        }//clearData

        function resetgame(){
            console.log("reset function called");
            $("#game_kids").hide();
            $("#game_subplays").hide();
            $("#game_plays").show();
            $("#save").hide();
            $("#gamelog").html("");
            $(".isPlayer").css("border","1px solid #aaa");
            $("#action_note").html("");
            clearData();
        }//reset

        function storeplay(PlayStr){
            PlayStr = PlayStr.replace(/&nbsp;/g," ");

            $("#game").data("event",PlayStr);
        }//storeplay

        function removeA(arr) {
            var what, a = arguments, L = a.length, ax;
            while (L > 1 && arr.length) {
                what = a[--L];
                while ((ax= arr.indexOf(what)) !== -1) {
                    arr.splice(ax, 1);
                }
            }
            return arr;
        }

        function SetGameValue()
        {
            var gametitle = $("#gametitlelist option:selected").text();
            var opp = $("#gametitlelist option:selected").val();
            gametitle = gametitle  == "-- New Game --" ? "" : gametitle;
            $("#gametitle").val(gametitle);
            $("#gameopp").val(opp);
        }//SetGameValue

    </script>
    <title>Game Stats! - ReadyStats.com </title>
</head>
<body>
<div id="PlayerList"></div>
<div style="position: absolute; left:0; top:150;">
            <table align="center" id="game_plays" cellspacing="10" >
                <tr><td><button name="ShowPlayer">&nbsp;IN&nbsp;</button>&nbsp;&nbsp;<button name="ShowPlayer">OUT</button></td></tr>
                <tr><td><button name="ShowPlayer">ASSIST</button></td></tr>
                <tr><td><button name="ShowPlayer">GOAL</button></td></tr>
                <tr><td><button name="ShowPlayer">SAVED GOAL</button></td></tr>
                <tr><td><button>OPPONENT SCORE</button></td></tr>
                <tr><td><button name="subplay">MISSED PLAY</button></td></tr>
                <tr><td><button name="ShowPlayer">GREAT PASS</button></td></tr>
                <tr><td><button>-START-</button>&nbsp;<button>-HALF-</button>&nbsp;<button>-END-</button></td></tr>
                
            </table>
            <table align="center" id="game_subplays" cellspacing="10" >
                <tr><TD><button name="ShowPlayer">MISSED SHOT</button></td></tr>
                <tr><TD><button name="ShowPlayer">MISSED CROSS</button></td></tr>
                <tr><TD><button name="ShowPlayer">MISSED CORNER</button></td></tr>
                <tr><TD><button name="ShowPlayer">LOST AT FORWARD</button></td></tr>
                <tr><TD><button name="ShowPlayer">LOST AT DEF</button></td></tr>
            </table>
            <table align="center" id="game_new" cellspacing="1" >
                <tr><td>Running Game<select id="gametitlelist" name="gametitlelist" onchange="SetGameValue(this);"><option value="">-- New Game --</option></select></td></tr>
                <tr><td>Game:<input type="text" maxlength="50" id="gametitle" name="gametitle"></td></tr>
                <tr><td>Opponent:<input type="text" maxlength="50" id="gameopp" name="gameopp"></td></tr>
                <tr><td><button>-CREATE GAME-</button></td></tr>
            </table>

            <div><a href="#" onclick="resetgame()">[reset]</a></div>
</div>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-52677013-1', 'auto');
    ga('send', 'pageview');

</script>
<div id="game"></div>
<div id="gamelog"></div>
<div id="gamelogSaved"></div>
</body>
</html>
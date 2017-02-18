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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="plays_readystats_com/css/bootstrap-responsive.min.css" rel="stylesheet">

    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/scripts.js"></script>
    <script type="text/javascript" src="js/movement.js"></script>
    <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/json2/20130526/json2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/json3/3.3.2/json3.min.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" rel="stylesheet" />
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
            populateGameList();
            $("#game_kids").hide();
            $("#game_subplays").hide();
            $("#gamelogSaved").hide();
            $("#save").button({icons: {primary: "ui-icon-disk"},text:true}).hide();
            //$("#gamelog").hide();
            //$("td button").addClass("ripple-btn dark-ripples");
            //setInterval("save()",10000);
                $("button")
                .mouseup(function(){console.log(this); $(this).removeClass("ui-state-focus").removeClass("ui-state-hover");})
                .click(function (event) {
                    //makeRipple(this);
                    event.preventDefault();
                    //console.log(this);
                    var playstr = $(this).html();
                        console.log(playstr);
                    switch (String($(this).attr("name")).toLowerCase()) {
                        case "save":
                            save();
                            resetgame();
                            break;

                        case "subplay":
                            $("#action_note").html(playstr);
                            storeplay(playstr);
                            $("#game_kids").hide();
                            $("#game_subplays").show();
                            $("#game_plays").hide();
                            break;

                        case "showplayer":
                            $("#action_note").html(playstr);
                            storeplay(playstr);
                            $("#game_kids").show();
                            $("#game_subplays").hide();
                            $("#game_plays").hide();
                            $("#save").show();
                            break;

                        default:
                            if($(this).hasClass("isPlayer")){
                                var kidArr = $("#game").data("playerArray");
                                if(typeof kidArr != "object")
                                {
                                    $(this).addClass("playerHighlight");
                                    $(this).css('borderColor','red');
                                    kidArr = [playstr];
                                    $("#game").data("playerArray",kidArr);
                                }else{
                                    if($(this).hasClass("playerHighlight")){
                                        $(this).removeClass("playerHighlight");
                                        $(this).css('borderColor','#AAA');
                                        removeA(kidArr, playstr);
                                    }else{
                                        $(this).addClass("playerHighlight");
                                        $(this).css('borderColor','red');
                                        // add players name to the player list
                                        kidArr.push(playstr )
                                    }//add or remove
                                }// if else kidArray object
                            }else{ // it's not a player button
                                    storeplay(playstr);
                                    $("#action_note").html("Added:"+playstr);
                                    $("#action_note").show();
                                    $("#action_note").fadeOut(750);
                                    save();
                            }//if-else player
                            break;
                    } //switch
                }); //click

        });//document ready

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
                        $("#gamelogSaved").html("<div>"+JSON3.stringify(gameDataToSend)+"</div>");
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

<div class="container">
    <div class="row clearfix" id="content_row">
        <div class="col-xs-1 column" style="text-align:center;position: relative;">
            <table align="center" id="game_notes" cellspacing="10" ><tr><td align="center">
                <table STYLE="width:305px;">
                    <tr><td align="center" STYLE="width:150px;"><div id="action_note" STYLE="font-weight: bold; color:red; text-align: center;"></div></td>
                        <td align="center" STYLE="width:150px;"><BUTTON name="save" id="save" class='ripple-btn dark-ripples'>save</BUTTON></td></tr></table>
            </td></tr></table>
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
            <table align="center" id="game_kids">
                <tr><td><button class="isPlayer">Greyden</button></td><td><button class="isPlayer">Liam</button></td></tr>
                <tr><td><button class="isPlayer">Logan</button></td><td><button class="isPlayer">Ashagre</button></td></tr>
                <tr><td><button class="isPlayer">Alex</button></td><td><button class="isPlayer">James</button></td></tr>
                <tr><td><button class="isPlayer">Hayden</button></td><td><button class="isPlayer">Dom</button></td></tr>
                <tr><td><button class="isPlayer">Aiden</button></td><td><button class="isPlayer">Caleb</button></td></tr>
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
            </table>

            <div><a href="#" onclick="resetgame()">[reset]</a></div>
        </div>

    </div>
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
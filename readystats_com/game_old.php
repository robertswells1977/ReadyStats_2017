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
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/ripple.css" rel="stylesheet">

    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/scripts.js"></script>
    <script type="text/javascript" src="js/movement.js"></script>
    <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/json2/20130526/json2.min.js"></script>

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
    </style>

    <script language="JavaScript">

        function makeRipple(item)
        {
            var $div = $('<div/>'),
                btnOffset = $(item).offset(),
                xPos = event.pageX - btnOffset.left,
                yPos = event.pageY - btnOffset.top;

            $div
                .addClass('circle')
                .css({
                    top: yPos - 65,
                    left: xPos - 65
                })
                .appendTo($(item));

            window.setTimeout(function(){
                $div.remove();
            }, 1500);
            event.preventDefault();
        }//makeRipple


        //
        // Document onload
        //
        $( document ).ready(function() {
            $("#game_kids").hide();
            $("#game_subplays").hide();
            $("#gamelogSaved").hide();
            $("#save").button({icons: {primary: "ui-icon-disk"},text:true}).hide();
            //$("#gamelog").hide();
            $("td button").addClass("ripple-btn dark-ripples");
            //setInterval("save()",10000);



                $("button")
                .mouseup(function(){console.log(this); $(this).removeClass("ui-state-focus").removeClass("ui-state-hover");})
                .click(function (event) {
                    //makeRipple(this);

                    if($(this).hasClass("isPlayer"))
                    {
                        $(this).css("border","2px solid red");
                    }//if player border red

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
                            storeplay(playstr);
                            if(!$(this).hasClass("isPlayer")){
                                $("#action_note").html("Added:"+playstr);
                                $("#action_note").show();
                                $("#action_note").fadeOut(750);
                                save();}
                            break;
                    } //switch
                }); //click

        });//document ready

        function save()
        {
            var gamelogTxt = $("#gamelog").html();

                $.ajax({
                    type: "POST",
                    url: "crud.php",
                    data: { data: gamelogTxt , a: "c" }
                })
                    .done(function( msg ) {
                        console.log("new data saved");
                        $("#gamelogSaved").html(gamelogTxt);
                    });
            var gamelogTxt = $("#gamelog").html("");

        }//save

        function resetgame(){
            console.log("reset function called");
            $("#game_kids").hide();
            $("#game_subplays").hide();
            $("#game_plays").show();
            $("#save").hide();
            $("#gamelog").html("");
            $(".isPlayer").css("border","1px solid #aaa");
            $("#action_note").html("");


        }//reset

        function storeplay(PlayStr){
            var d = new Date();
            var nowEpoch = d.getTime();
            var record  = "<div id='record'><span id='time'>"+d+"</span><span id='epochTime'>("+nowEpoch+")</span><span id='event'>"+PlayStr+"</span></div><br/>\n";

            var gamelogStr = $("#gamelog").html();
            var newStr = gamelogStr + record;
            $("#gamelog").html(newStr);
            console.log("store function called:"+PlayStr);

        }//store

    </script>
    <title>ReadyStats.com - Game Stats</title>
</head>
<body>

<div class="container">
    <div class="row clearfix" id="content_row">
        <div class="col-xs-12 column" style="text-align:center;position: relative;">
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
                <tr><td><button class="isPlayer">Logan</button></td><td><button class="isPlayer">Caleb</button></td></tr>
                <tr><td><button class="isPlayer">Alex</button></td><td><button class="isPlayer">Girik</button></td></tr>
                <tr><td><button class="isPlayer">Hayden</button></td><td><button class="isPlayer">James</button></td></tr>
            </table>
            <table align="center" id="game_subplays" cellspacing="10" >
                <tr><TD><button name="ShowPlayer">MISSED SHOT</button></td></tr>
                <tr><TD><button name="ShowPlayer">MISSED CROSS</button></td></tr>
                <tr><TD><button name="ShowPlayer">MISSED CORNER</button></td></tr>
                <tr><TD><button name="ShowPlayer">LOST AT FORWARD</button></td></tr>
                <tr><TD><button name="ShowPlayer">LOST AT DEF</button></td></tr>
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
<div id="gamelog"></div>
<div id="gamelogSaved"></div>
</body>
</html>
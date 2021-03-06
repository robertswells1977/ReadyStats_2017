<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Plays - ReadyStats</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

	<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
	<!--script src="js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/pepper-grinder/jquery-ui.min.css" rel="stylesheet" />

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://www.readystats.com/img/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://www.readystats.com/img/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://www.readystats.com/img/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="http://www.readystats.com/img/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="http://www.readystats.com/img/favicon.png">
  -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/scripts.js"></script>
    <script type="text/javascript" src="js/movement.js"></script>
  <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/json2/20130526/json2.min.js"></script>

<script>
    $(document).ready(function () {

        //setTimeout("checkURL();",500);
        var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i).test(navigator.userAgent.toLowerCase());
        //h = isNaN(parseInt(h,10) ?  510 : h;
        //w = isNaN(parseInt(w,10) ?  675 : w;

        $(".inout").droppable({
            accept: ".player",
            activeClass: "ui-state-hover",
            hoverClass: "ui-state-active",
            drop: function( event, ui ) {
                var draggableId = ui.draggable.html();
                var droppableId = $(this).html();
                alert(draggableId + " is "+ droppableId);
            }
        });

        $("#playname_text").hide();
        $("#content_nav").hide();
        $("#legend_content").hide();
        $("#playList").hide();
        $("#save_dialog" ).dialog({
            autoOpen: false,
            height: 200,
            width: 150,
            modal: true});

        $("#info_dialog").dialog({
            autoOpen: false,
            height: 200,
            width: 350,
            modal: true,
            title:"How to begin",
            position: { my: "center center", at: "center center", of: $("#gamefield") }
        });


        $("#newplayinfo_dialog").dialog({
            autoOpen: false,
            height: 200,
            width: 450,
            modal: true,
            title:"How to make your own play set",
            position: { my: "center center", at: "center center", of: $("#gamefield") }
        });

        $("#playList").data("isPaused",true);
        $("#playList").data("list", ["start"]);
        $("#move_choice").hide();
        $("#rec_overlay").removeClass("overlay",3000);
        $("#playList").data("current_idx",0);
        $("div.player").draggable({ opacity: 0.7, helper: "clone" });

        $("div#move_choice div.animate_speed").click(function () {
            setBallSpeed($(this).attr('id'))
        });

        $("div.player").on("dragstop", function (event, ui) {
            setPlayer(ui.position.left, ui.position.top, this);
        });

        $("#selectplay_dialog").dialog({
            autoOpen: false,
            height: 300,
            width: 250,
            modal: true,
            title:"Select a Strategy"});


        $("#move_button").button({icons: {primary: "ui-icon-play"},text:true}).hide();
        $("#next_button").button({icons: {primary: "ui-icon-seek-next"},text:true}).hide();
        $("#prev_button").button({icons: {primary: "ui-icon-seek-prev"},text:true}).hide();
        $("#set_button").button({icons: {primary: "ui-icon-plusthick"},text:true}).hide();
        $("#save_button").button({icons: {primary: "ui-icon-disk"},text:true}).hide();
        $("#open_button").button({icons: {primary: "ui-icon-circlesmall-plus"},text:true});
        $("#begin_button").button({icons: {primary: "ui-icon-flag"},text:true});
        $("#make_new_play_button").button({icons: {primary: "ui-icon-flag"},text:true});
        $("#make_new_play").button({icons: {primary: "ui-icon-star"},text:true});


        $(".play_buttons").button({icons: {primary: "ui-icon-clipboard"},text:true});

        $("#prev_button").button({disabled:true});
        $("#gamefield").dblclick(function(){setCurrentPos()});

        $(".play_buttons")
            .mouseup(function(){console.log(this); $(this).removeClass("ui-state-focus").removeClass("ui-state-hover");})
            .click(function (event) {
                event.preventDefault();
                //console.log(this);

                switch ($(this).attr("id")) {

                    case "load_defense":
                        $("#playList").data("current_idx",0);
                        $("#info_dialog").dialog("close");
                        $("#selectplay_dialog").dialog("close");

                        $("#playname_text").html("Defense Strategy").show();
                        var obj = getLoadedPlays("defense");
                        loadPlays(obj);
                        play();
                        break;

                    case "load_game_start":
                        $("#playList").data("current_idx",0);
                        $("#info_dialog").dialog("close");
                        $("#selectplay_dialog").dialog("close");
                        $("#playname_text").html("Game Start Strategy").show();
                        var obj = getLoadedPlays("start");
                        loadPlays(obj);
                        play();
                        break;

                    case "load_goal_kick":
                        $("#playList").data("current_idx",0);
                        $("#info_dialog").dialog("close");
                        $("#selectplay_dialog").dialog("close");
                        $("#playname_text").html("Goal Kick Strategy").show();
                        var obj = getLoadedPlays("goal_kick");
                        loadPlays(obj);
                        play();
                        break;

                    case "load_new_goal_kick":
                        $("#playList").data("current_idx",0);
                        $("#info_dialog").dialog("close");
                        $("#selectplay_dialog").dialog("close");
                        $("#playname_text").html("NEW Goal Kick Strategy").show();
                        var obj = getLoadedPlays("new_goal_kick");
                        loadPlays(obj);
                        play();
                        break;

                    default:
                        break;
                }//switch
            });//


        $(".buttonevent")
            .mouseup(function(){console.log(this); $(this).removeClass("ui-state-focus").removeClass("ui-state-hover");})
            .click(function (event) {
                event.preventDefault();
                //console.log(this);
                switch ($(this).attr("id")) {
                    case "set_button":
                        setCurrentPos();
                        break;

                    case "move_button":
                        play();
                        break;//move_button

                    case "next_button":
                        $("#move_button").button({icons: {primary: "ui-icon-play"},text:true});
                        $("#move_button > span.ui-button-text").html("Play");
                        $("#playList").data("isPaused",true);
                        var curr_idx = $("#playList").data("current_idx");
                        runSet(curr_idx,true);
                        break;//next_button

                    case "prev_button":
                        $("#move_button").button({icons: {primary: "ui-icon-play"},text:true});
                        $("#move_button > span.ui-button-text").html("Play");
                        $("#playList").data("isPaused",true);
                        var curr_idx = $("#playList").data("current_idx");
                        curr_idx = curr_idx - 2;
                        runSet(curr_idx,true);
                        break;//next_button


                    case "log_button":
                        dumpPlayList();
                        break;//log_button

                    case "save_button":
                        showSaveDialog();
                        break;//log_button

                    case "open_button":
                        $("#selectplay_dialog").dialog( "option", "position", { my: "center center", at: "center center", of: $("#gamefield") }).dialog("open");
                        $("#load_game_start").removeClass("ui-state-focus").removeClass("ui-state-hover");
                        break;

                    case "record_button":
                        RecordPlay();
                        break;

                    case "make_new_play":
                        $("#playList").data("list", ["start"]);
                        setPlayerCoordTable();
                        SetStartingPositions(
                            {box_a:[310,110,1000],
                                box_b:[313, 214, 1000],
                                box_c:[167,158, 1000],
                                box_d:[384,116, 1000],
                                box_e:[406,148, 1000],
                                box_f:[379,176, 1000],
                                ball:[327, 252, 500]}
                        );
                        $("#selectplay_dialog").dialog("close");
                        $("#newplayinfo_dialog").dialog( "option", "position", { my: "center center", at: "center center", of: $("#gamefield") }).dialog("open");
                        $("#set_button").show();
                        $("#playname_text").html("Make Your Own Play").show();
                        break;

                    case "begin_button":
                        $("#info_dialog").dialog("close");
                        $("#open_button").trigger("click");
                        break;

                    case "make_new_play_button":
                        play();
                        $("#playList").data("current_idx",0);
                        $("#newplayinfo_dialog").dialog("close");
                        break;

                    default:
                        break;
                } //switch
            }); //click


        if(isMobile )
        {
            $("#gamefield").css({height:240, width:405});
            $("#player_in").css({left:'auto', top:0, right:0, height:"8px", width:"25px", font:"bold italic 8px/10px arial,sans-serif"});
            $("#player_out").css({left: 'auto', bottom:0, right:0, height:"8px", width:"25px", font:"bold italic 8px/10px arial,sans-serif"});
            $("#box_a").css({left: 'auto', top:35, right:0});
            $("#box_b").css({left: 'auto', top:65, right:0});
            $("#box_c").css({left: 'auto', top:95, right:0});
            $("#box_d").css({left: 'auto', top:125, right:0});
            $("#box_e").css({left: 'auto', top:155, right:0});
            $("#box_f").css({left: 'auto', top:185, right:0});
            $("div.player").draggable("option", "cursorAt",{top: 50, left: 50 });
        }//if height/width
        else{
            $("#box_a").css({left: 'auto', top:65, right:0});
            $("#box_b").css({left: 'auto', top:95, right:0});
            $("#box_c").css({left: 'auto', top:125, right:0});
            $("#box_d").css({left: 'auto', top:155, right:0});
            $("#box_e").css({left: 'auto', top:185, right:0});
            $("#box_f").css({left: 'auto', top:215, right:0});

        }

    }); //ready

function checkURL()
{
    var qs = getQuerystring("q");
    console.log("checkURL - "+qs);

    if(qs !== null)
    {
      switch(qs)
      {
          case "load_new_goal_kick":
              $("#load_new_goal_kick").trigger("click");
              console.log("load_new_goal_kick");
              break;

          default:
              break;
      }//switch
    }//if not null
    //var urldocument.location.href;
}//checkURL

    function getQuerystring(key)
    {
        key = key.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
        var regex = new RegExp("[\\?&]"+key+"=([^&#]*)");
        var qs = regex.exec(window.location.href);
        if(qs == null)
            return null;
        else
            return qs[1];
    }

</script>
</head>

<body>
<div id="playList" style=""></div>

<div class="container-fluid">
	<div id="content_nav" class="row-fluid">
		<div class="span12">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#">Home</a>
				</li>
				<li>
					<a href="#">Profile</a>
				</li>
				<li class="disabled">
					<a href="#">Messages</a>
				</li>
				<li class="dropdown pull-right">
					 <a href="#" data-toggle="dropdown" class="dropdown-toggle">Dropdown<strong class="caret"></strong></a>
					<ul class="dropdown-menu">
						<li>
							<a href="#">Action</a>
						</li>
						<li>
							<a href="#">Another action</a>
						</li>
						<li>
							<a href="#">Something else here</a>
						</li>
						<li class="divider">
						</li>
						<li>
							<a href="#">Separated link</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<div class="row-fluid">
		<div id="gamefield" class="span8">
            <div class="inout" id="player_in" style="top:20px; right:0;">IN</div>
            <div class="inout" id="player_out" style="bottom:20px; right:0;">OUT</div>
            <div class="player" id="box_a" style="position:absolute; right:1px; top:55px;">James</div>
            <div class="player" id="box_b" style="position:absolute; right:1px; top:85px;">Liam</div>
            <div class="player" id="box_c" style="position:absolute; right:1px; top:115px;">Matthew</div>
            <div class="player" id="box_d" style="position:absolute; right:1px; top:145px;">Kyle</div>
            <div class="player" id="box_e" style="position:absolute; right:1px; top:175px;">Ben</div>
            <div class="player" id="box_f" style=" position:absolute; right:1px; top:205px;">Zane</div>
            <div style="bottom:20px; right:240px; position: absolute;">
                <button class="buttonevent" id="prev_button">Prev</button>
                <button class="buttonevent" id="move_button">Play</button>
                <button class="buttonevent" id="next_button">Next</button>
                <!--<button class="btn-small" id="record_button">Start Rec</button>-->
            </div>
            <div  id="move_text" style="bottom:20px; right:140px; position: absolute; color:#d2322d"></div>
            <div id="playname_text" style="text-align:center; padding:4px;  border:1px solid #000; height:20px; width:170px; top:25px; right:40px; position: absolute; color:#ffffff; background-color:#c9302c; border-radius:3px; font-size:10pt; font-weight: bolder; ">Play Name Here</div>
            <div id="move_choice" style="position:absolute">
                <div class="animate_speed" id="pass_shot">pass/shot</div>
                <div class="animate_speed" id="dribble">dribble</div>
            </div>

			 <!--<img alt="Soccer3v3" src="fields/Soccer3v3.png" class="img-rounded">-->
		</div>
		<div id="legend_content" class="span4">
            <table  border="1" cellpadding="5" cellspacing="0"  width="100%" id="setLog">
                <tr><th>Set Name</th><th>Moves</th></tr>
                <tr><td>Start</td><td>12:(x:0, y:0, s:0)<br/>17:(x:0, y:0, s:0)<br/>24:(x:0, y:0, s:0)<br/>ball:(x:0, y:0, s:0)<br/><hr/></td></tr>
            </table>
			<form>
				<fieldset>
					 <legend>Legend</legend> <label>Label name</label><input type="text"> <span class="help-block">Example block-level help text here.</span> <label class="checkbox"><input type="checkbox"> Check me out</label> <button type="submit" class="btn">Submit</button>
				</fieldset>
			</form>
			<ol>
				<li>
					Lorem ipsum dolor sit amet
				</li>
				<li>
					Consectetur adipiscing elit
				</li>
				<li>
					Integer molestie lorem at massa
				</li>
				<li>
					Facilisis in pretium nisl aliquet
				</li>
				<li>
					Nulla volutpat aliquam velit
				</li>
				<li>
					Faucibus porta lacus fringilla vel
				</li>
				<li>
					Aenean sit amet erat nunc
				</li>
				<li>
					Eget porttitor lorem
				</li>
			</ol>
		</div>
	</div>
</div>

<table style="visibility:hidden" border="1" cellpadding="5" cellspacing="0" id="player_coords"
    <tr>
        <th>Player</th>
        <th>x coord</th>
        <th>y coord</th>
    </tr>
</table>

<div id="save_dialog"></div>
<div id="selectplay_dialog" style="text-align: center;">
    <button class="play_buttons" id="load_game_start">Play Game Start</button><br>&nbsp;<br>
    <button class="play_buttons" id="load_goal_kick">Play OLD Goal Kick</button><br>&nbsp;<br>
    <button class="play_buttons" id="load_new_goal_kick">Play NEW Goal Kick</button><br>&nbsp;<br>
    <button class="play_buttons" id="load_defense">Play Defense</button><br>&nbsp;<br>
    <button class="buttonevent" id="make_new_play">Make New Play</button>
</div>
<div id="info_dialog" style="">
    <ol>
        <li>Click the "Show Plays"</li>
        <li>Select a strategy to watch</li>
        <li>Use the "Prev","Play/Pause","Next" buttons to walk through the play</li>
    </ol>    <center><button class="buttonevent" id="begin_button">Begin</button></center>
</div>
<div id="newplayinfo_dialog" style="">
    <ol>
        <li>Move each player to a new spot and the ball</li>
        <li>Click "Set Move" to record that position</li>
        <li>Repeat previous steps to build on each movement</li>
        <li>Use the "Prev","Play/Pause","Next" buttons to walk through the play as you're making it</li>
    </ol>    <center><button class="buttonevent" id="make_new_play_button">Make New Play</button></center>
</div>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-52677013-1', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>

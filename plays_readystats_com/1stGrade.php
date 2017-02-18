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
    <link href="css/style-1stgrade.css" rel="stylesheet">

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
    <script type="text/javascript" src="js/movement-b.js"></script>
  <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/json2/20130526/json2.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/json3/3.3.2/json3.min.js"></script>

<style>

    #result, table, td, div{
        font-size:.9em;
        font-family: arial;
    }
    table

    .small-button {
        font-size: .8em !important;
    }
    .ui-button-text-only .ui-button-text {
        font-size: .7em !important;
        padding: 0px 0px 0px 0px;
    }
</style>
<script>
    $(document).ready(function () {

        setTimeout("checkURL();",500);

        $("#playname").hide();
        $("#content_nav").hide();
        $("#legend_content").hide();
        $("#playList").hide();
        $("#save_dialog" ).dialog({
            autoOpen: false,
            height: 200,
            width: 150,
            modal: true});

        $("#info_dialog").dialog({
            autoOpen: true,
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

        $("#createplayinfo_dialog").dialog({
            autoOpen: false,
            height: 300,
            width: 450,
            modal: true,
            title:"Name your play",
            position: { my: "center center", at: "center center", of: $("#gamefield") }
        });


        $("#playList").data("isPaused",true);
        $("#playList").data("list", ["start"]);
        $("#move_choice").hide();
        $("#rec_overlay").removeClass("overlay",3000);
        $("#playList").data("current_idx",0);
        $("div.player").draggable();

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

//        moving:1 264 351 1000
//        moving:2 140 249 1000
//        moving:3 444 227 1000
//        moving:4 35 74 1000
//        moving:5 542 42 1000
//        moving:X 88 -4 1000
//        moving:X 478 -39 1000
//        moving:X 416 37 1000
//        moving:X 295 32 1000
//        moving:X 177 -25 1000
//        moving:# 305 330 500        
        
        setPlayerCoordTable();
        SetStartingPositions(
            {box_a:[264, 351, 1000],
            box_b:[140, 249, 1000],
            box_c:[444, 227, 1000],
            box_d:[35, 74, 1000],
            box_e:[542, 42, 1000],
            box_f:[88, -4, 1000],
            box_g:[478,-39, 1000],
            box_h:[416,37, 1000],
            box_i:[295,32, 1000],
            box_j:[177,-25, 1000],
            ball:[305, 330, 500]}
        );

        $("#move_button").button({icons: {primary: "ui-icon-play"},text:true});
        $("#next_button").button({icons: {primary: "ui-icon-seek-next"},text:true});
        $("#prev_button").button({icons: {primary: "ui-icon-seek-prev"},text:true});
        $("#set_button").button({icons: {primary: "ui-icon-plusthick"},text:true}).hide();
        $("#save_button").button({icons: {primary: "ui-icon-disk"},text:true}).hide();
        $("#open_button").button({icons: {primary: "ui-icon-circlesmall-plus"},text:true});
        $("#begin_button").button({icons: {primary: "ui-icon-flag"},text:true});
        $("#make_new_play_button").button({icons: {primary: "ui-icon-flag"},text:true});
        $("#make_new_play").button({icons: {primary: "ui-icon-star"},text:true});
        $("#save_play_attr_button").button({icons: {primary: "ui-icon-disk"},text:true});
        $("#edit_play_attr_button").button({icons: {primary: "ui-icon-pencil"},text:false});



        $(".play_buttons").button({icons: {primary: "ui-icon-clipboard"},text:true});

        $("#prev_button").button({disabled:true});
        $("#gamefield").dblclick(function(){setCurrentPos()});

        $(".play_buttons")
            .mouseup(function(){console.log(this); $(this).removeClass("ui-state-focus").removeClass("ui-state-hover");})
            .click(function (event) {
                event.preventDefault();
                //console.log(this);

                switch ($(this).attr("id")) {
                    default:

                        $("#playList").data("current_idx",0);
                        $("#info_dialog").dialog("close");
                        $("#selectplay_dialog").dialog("close");

                        $("#playname_text").html($(this).text()).show();
                        var obj = getLoadedPlays($(this).attr("id"));
                        if(obj != false)
                        {
                            loadPlays(obj);
                            play();
                        }
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
                        savePlay();
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
                            {box_a:[264, 351, 1000],
                                box_b:[140, 249, 1000],
                                box_c:[444, 227, 1000],
                                box_d:[35, 74, 1000],
                                box_e:[542, 42, 1000],
                                box_f:[88, -4, 1000],
                                box_g:[478,-39, 1000],
                                box_h:[416,37, 1000],
                                box_i:[295,32, 1000],
                                box_j:[177,-25, 1000],
                                ball:[305, 330, 500]}
                        );
                        $("#selectplay_dialog").dialog("close");
                        $("#newplayinfo_dialog").dialog( "option", "position", { my: "center center", at: "center center", of: $("#gamefield") }).dialog("open");
                        $("#set_button").show();

                        $("#playname_text").html("Make Your Own Play");
                        $("#playname").show();

                        break;

                    case "begin_button":
                        $("#info_dialog").dialog("close");
                        $("#open_button").trigger("click");
                        break;

                    case "make_new_play_button":
                        $("#newplayinfo_dialog").dialog("close");
                        $("#createplayinfo_dialog").dialog( "option", "position", { my: "center center", at: "center center", of: $("#gamefield") }).dialog("open");
                        play();
                        $("#playList").data("current_idx",0);
                        break;

                    case "edit_play_attr_button":
                        $("#createplayinfo_dialog").dialog( "option", "position", { my: "center center", at: "center center", of: $("#gamefield") }).dialog("open");
                        break;

                    case "save_play_attr_button":
                        if(String($("#playinput_uniquelink").val()).length > 2)
                        {
                            savePlay();
                        }else{
                            createPlay();
                        }//if-else
                        $("#createplayinfo_dialog").dialog("close");
                        break;

                    default:
                        break;
                } //switch
            }); //click

    }); //ready

function checkURL()
{
    var qs = getQuerystring("q");
    console.log("checkURL - "+qs);
    try{
        $("#"+String(qs)).trigger("click");
    }catch(e){}

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


//===========================================================
    function getAnimatedData()
    {
        var retObj = new Object();
        $("div.player").each(function (index) {
            var id = $(this).attr('id');
            retObj[id] = $(this).data("playSet");
        }); //each

        var jsonStr = JSON.stringify(retObj);

        return jsonStr;

    }// getAnimatedData


    function createPlay()
    {
        if(String($("#playinput_uniquelink").val()).length > 2)
        {   savePlay();
            return;}


        var playname = $("#playinput_name").val();//"Play Namer is This!";
        var descr = $("#playinput_description").val();//"My description is here and this and that....";
        var createdby = $("#playinput_createdby").val();//"Rob Wells";
        var animateddataStr =  "("+getAnimatedData()+")";
        var gameDataToSend = {playname:playname,description:descr, createdby:createdby, animateddata:animateddataStr};
        console.log(gameDataToSend);
        var epochTime = (new Date()).getTime();
        $.ajax({
            type: "POST",
            url: "save/plays_crud.php?d="+epochTime,
            data: { data: JSON3.stringify(gameDataToSend), a: "c", t:"newplay" }
        })
            .done(function( msgstr ) {
                var msg = JSON3.parse(msgstr);
                if(!msg.isError)
                {
                    console.log("msg.uniqueLink = "+msg.uniqueLink);
                    console.log("msg.lastID = "+msg.lastID);
                    $("#playinput_uniquelink").val(msg.uniqueLink);
                    $("#playinput_playid").val(msg.lastID);
                    $("#save_play_attr_button span:last-child").text("Update Play");
                }
                console.log(msg);
            });
    }//createPlay

    function savePlay()
    {
        var playid = $("#playinput_playid").val();//2;
        var playname = $("#playinput_name").val();//"Play Namer is This!";
        var descr = $("#playinput_description").val();//"My description is here and this and that....";
        var createdby = $("#playinput_createdby").val();//"Rob Wells";
        var uniquelink = $("#playinput_uniquelink").val();//"564f682f8bfe8";
        var animateddataStr =  "("+getAnimatedData()+")";
        var gameDataToSend = {uniquelink:uniquelink,playid:playid,fields:["name","description","createdby","animationdata"], values:[playname,descr,createdby,animateddataStr]};

        console.log(gameDataToSend);
        var epochTime = (new Date()).getTime();
        $.ajax({
            type: "POST",
            url: "save/plays_crud.php?d="+epochTime,
            data: { data: JSON3.stringify(gameDataToSend), a: "u", t:"saveplay" }
        })
            .done(function( msgstr ) {
                var msg = JSON3.parse(msgstr);
                //
                //console.log("new data saved");
                if(msg.isError)
                {
                    alert("Error saving. Please change your data and try again.");
                    //$("#playinput_uniquelink").val(msg.uniqueLink);
                    //$("#playinput_playid").val(msg.lastID);
                }

                console.log(msg);

            });
    }//savePlay

</script>
</head>

<body>
<div id="pagetitle">111 - Hawks</div>
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
            <div class="player" id="box_a" style="position:relative; left:310px; top:110px;">1</div>
            <div class="player" id="box_b" style="position:relative; left:313px; top:214px;">2</div>
            <div class="player" id="box_c" style="position:relative; left:167px; top:158px;">3</div>
            <div class="player" id="box_d" style="position:relative; left:167px; top:158px;">4</div>
            <div class="player" id="box_e" style="position:relative; left:384px; top:116px;">5</div>
            <div class="player opponent" id="box_f" style="position:relative; left:406px; top:148px;">X</div>
            <div class="player opponent" id="box_g" style="position:relative; left:379px; top:176px;">X</div>
            <div class="player opponent" id="box_h" style="position:relative; left:379px; top:176px;">X</div>
            <div class="player opponent" id="box_i" style="position:relative; left:379px; top:176px;">X</div>
            <div class="player opponent" id="box_j" style="position:relative; left:379px; top:176px;">X</div>
            <div class="player opponent" id="ball" style="left:327px; top:252px;">#</div>
            <div style="bottom:20px; right:240px; position: absolute;">
                <button class="buttonevent" id="prev_button">Prev</button>
                <button class="buttonevent" id="move_button">Play</button>
                <button class="buttonevent" id="next_button">Next</button>
                <!--<button class="btn-small" id="record_button">Start Rec</button>-->
            </div>
            <div  id="move_text" style="bottom:20px; right:140px; position: absolute; color:#d2322d"></div>
            <div id="playname" style=""><span id="playname_text">Play Name Here</span>&nbsp;<button class='buttonevent' id='edit_play_attr_button'></button></div>
            <div style="position: absolute; left:270px; top:35px;">
                <button class="buttonevent" id="open_button">Show Plays</button>
                <button class="buttonevent" id="set_button">Set Move</button>
                <button class="buttonevent" id="save_button">Save</button>
                <!--<button class="btn-small" id="record_button">Start Rec</button>-->
            </div>
            <div id="move_choice" style="position:absolute; border-radius: 5px; border:2px solid #000000;">
                <div class="animate_speed" id="pass_shot">pass/shot</div>
                <div class="animate_speed" id="dribble">dribble</div>
            </div>

			 <!--<img alt="Soccer3v3" src="fields/Soccer3v3.png" class="img-rounded">-->
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

    <button class="play_buttons" id="wideballscreen2">WIDE Ball Screen #2</button><br>&nbsp;<br>
    <button class="play_buttons" id="inboundunder">Inbound Under</button><br>&nbsp;<br>
    <button class="play_buttons" id="inboundside">Inbound Side</button><br>&nbsp;<br>
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
<div id="createplayinfo_dialog" style="">
    <ol>
        <li>Play Name: <input type="text" name="playinput_name" id="playinput_name" value=""></li>
        <li>Your Name: <input type="text" name="playinput_createdby" id="playinput_createdby"  value=""></textarea></li>
        <li>Description: <textarea cols="10" rows="5" name="playinput_description" id="playinput_description"  value=""></textarea></li>
        <input type="hidden" id="playinput_uniquelink" name="playinput_uniquelink"  value="">
        <input type="hidden" id="playinput_playid" name="playinput_playid" value="">
    </ol>    <center><button class="buttonevent" id="save_play_attr_button">Create Play</button></center>
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

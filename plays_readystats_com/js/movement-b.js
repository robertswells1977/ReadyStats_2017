/**
 * Created by Robert on 6/26/14.
 */
//$("#box_a").css({left:'-200px'});

function setPlayer(x, y, item) {
    var id = $(item).attr("id");
    $("#" + id + "_xcoord").html(x);
    $("#" + id + "_ycoord").html(y);
    $(item).data("current_pos", {
        x: x,
        y: y
    });
    $(item).data("current_speed", 1000);

    if (id == "ball") {
        $("#move_choice").css({
            "left": x + 15,
            "top": y + 10
        });
        $("#move_choice").show();
    } //if ball

} //fn setPlayer

function setBallSpeed(choice) {
    switch (choice) {
        case "pass_shot":
            //console.log("pass/shot chosen");
            $("#ball").data("current_speed", 500);
            break; //pass/shot

        default:
        case "dribble":
            //console.log("dribble chosen");
            $("#ball").data("current_speed", 1000);
            break; //default, dribble
    } //switch
    $("#move_choice").hide();
} //fn setBallSpeed

function setPlayerCoordTable() {
    $("div.player").each(function (index) {
        var name = $(this).html();
        var id = $(this).attr("id");
        name = name == "#" ? "ball" : name;
        $(this).data("current_pos", {
            x: 0,
            y: 0
        });
        $(this).data("current_speed", 1000);
        $(this).data("playSet", {
            start: {
                x: 0,
                y: 0,
                s: 0
            }
        });
        //$("#player_coords").append("<tr><td>" + name + "</td><td><span id='" + id + "_xcoord'></span></td><td><span id='" + id + "_ycoord'></span></td></tr>");
    }); //each fn
} //setPlayerCoordTable

function setCurrentPos() {
    uniquePlaySetName = String((new Date()).getTime());
    var strLog = "<tr><td>"+String(uniquePlaySetName)+"</td><td>";
    //console.log(strLog);
    //nsole.log("outside" + uniquePlaySetName);
    $("div.player").each(function (index) {
        var player_pos = $(this).data("current_pos");
        var player_speed = $(this).data("current_speed");
        var name = $(this).html();
        var playSetObj = $(this).data("playSet");
        playSetObj[uniquePlaySetName] = {
            x: player_pos.x,
            y: player_pos.y,
            s: player_speed
        };
        strLog = strLog + ""+String(name)+":(x:"+String(player_pos.x)+", y:"+String(player_pos.y)+", s:"+String(player_speed)+")<br/>";
        //console.log(strLog);
        //nsole.log("inside" + uniquePlaySetName);
        $(this).data("playSet", playSetObj);
        //console.log(String(name) + " =  x:" + player_pos.x + " y:" + player_pos.y + " s:" + player_speed);
    }); //each fn
    var playListArr = $("#playList").data("list");
    playListArr.push(uniquePlaySetName);
    $("#playList").data("list", playListArr);
    strLog = strLog + "<hr/></td></tr>";
    $("#setLog").append(strLog);
    //console.log(strLog);
    $("#gamefield").fadeOut("fast").fadeIn("fast");

    //console.log(playListArr);
} //fn setCurrentPos



function move(item, x, y, speed) {
    currentx = $(item).css("left");
    currenty = $(item).css("top");
    left_val = parseInt(x, 10);
    top_val  = parseInt(y, 10);
    console.log("moving:"+String($(item).html()),left_val, top_val,speed);
    $(item).animate({
        'left': left_val,
        'top': top_val
    }, {
        duration: speed,
        complete: function () {
            //alert("done");
        }
    });
} //move


function positionPlayer(item, x, y, speed) {
    var left_val = parseInt(x, 10);
    var top_val = parseInt(y, 10);
    $(item).css({"left":left_val,"top":top_val});
} //positionPlayer

function runSet(idx, isRunOneStep) {
    console.log("Start RunStep: idx="+idx+", isRunOneStep="+isRunOneStep);
    var playListArr = $("#playList").data("list");
    var isPaused = $("#playList").data("isPaused");
    if((!isPaused) || isRunOneStep === true)
    {
        var arrayOfPlayers = new Array();
        $("div.player").each(function (index) {
            arrayOfPlayers.push($(this).attr('id'))
        });

        idx = isNaN(idx) ? 0 : idx;
        idx = idx < 0 ? 0 : idx;
        console.log("executing runSet("+idx+")");
        $("#move_text").html("Move: "+idx);

        // button logic
        checkButtonLogic(idx,playListArr.length);

        if(idx < playListArr.length)
        {
            for (var j = 0; j < arrayOfPlayers.length; j++) {
                var playerId = "#" + arrayOfPlayers[j];
                //console.log("i="+i+"  j="+j+"  playerId="+playerId);
                var currPlayIndex = playListArr[idx];
                var playSetObj = $(playerId).data("playSet");
                var coordsObj = playSetObj[currPlayIndex];
                //console.log(coordsObj);

                if(currPlayIndex == "start")
                {
                    positionPlayer(playerId, coordsObj.x, coordsObj.y, coordsObj.s);
                }
                move(playerId,coordsObj.x,coordsObj.y,coordsObj.s);

            } // j loop players
            var delay = 2000; //(idx)*250;
            idx++;
            $("#playList").data("current_idx",idx);
            setTimeout(function(){runSet(idx)},delay);
        }else{

            console.log("end of runSet");
            $("#move_button").button({icons: {primary: "ui-icon-play"},text:true});
            $("#move_button > span.ui-button-text").html("Play");
            $("#playList").data("isPaused",true);

        }//if-else idx > array length
    }//if isPaused
    else
    {
        console.log("Runset: paused.");
    }//if-else isPaused

} //fn runSet


function checkButtonLogic(idx, arrLength)
{
    if(idx <= 0)
    {
        $("#next_button").button({disabled:false});
        $("#prev_button").button({disabled:true});
    }
    else if(idx >= arrLength)
    {
        $("#next_button").button({disabled:true});
        $("#prev_button").button({disabled:false});
    }
    else
    {
        $("#next_button").button({disabled:false});
        $("#prev_button").button({disabled:false});
    }


}//checkButtonLogic

function dumpPlayList() {
    var playListArr = $("#playList").data("list");
    console.log(playListArr);
    $("div.player").each(function (index) {
        var id = $(this).attr('id');
        var name = $(this).html();
        var playSetObj = $(this).data("playSet");
        console.log(id + " : " + name + "=== >>");
        console.log(playSetObj);
    }); //each

} //fn dumpPlayList


function RecordPlay(){
    if($('#record_button').html() == 'Start Rec')
    {
        setInterval("if($('#record_button').html() != 'Start Rec'){setCurrentPos();}", 10000);
        setTimeout("if($('#record_button').html() != 'Start Rec'){RecButtonAnim(9);}", 1000);
        $('#record_button').html('Rec ON (10 sec)');
    }
    else
    {
        $('#record_button').html('Start Rec');
    }
}//Rec

function RecButtonAnim(num){
    if($("#record_button").html() != 'Start Rec'){
        num--;
        if(num < 0){num=10;}
        $('#record_button').html('Rec ON ('+num+' sec)');
        setTimeout("RecButtonAnim("+num+")", 1000);
    }
}//RecButtonAmin

function SetStartingPositions(obj){
    var x= 0;
    var y =0;
    var s =0;
    var arr=[];

    for(var i in obj)
    {
        arr = obj[i];
        x= arr[0];
        y= arr[1];
        s= arr[2];
        console.log("x="+x+" y="+y+" s="+s);
        setPlayer("#"+String(i),x,y);
        $("#"+String(i)).css({left:x, top:y});
        $("#"+String(i)).data("current_pos", {x:x,y:y,s:1000});
        $("#"+String(i)).data("current_speed", 1000);
        $("#"+String(i)).data("playSet", {
            start: {
                x: x,
                y: y,
                s: s
            }
        });

    }//for

    setCurrentPos();

}//SetStartingPositions

/*
*  loadPlays
*  Takes an object of the following format
*  Obj{ PLAYER1:{set plays}
* */
function loadPlays(obj)
{
    var playListArr = [];
    var singlePlaySet = {};
    for(var i in obj)
    {
       $("#"+i).data("playSet",obj[i]);
        singlePlaySet = obj[i];
    }//for each players and playSets

    for(var j in singlePlaySet )
    {
        playListArr.push(j);
    }//for each item in playset

    $("#playList").data("list",playListArr);
}//loadPlays

/*
*
*
* */
function showSaveDialog(){

    var retObj = new Object();

    $("div.player").each(function (index) {
        var id = $(this).attr('id');
        retObj[id] = $(this).data("playSet");
    }); //each

    var jsonStr = JSON.stringify(retObj);
    $("#save_dialog").html('<form><textarea wrap="hard" cols="10" rows="20">'+jsonStr+'</textarea></form>');
    $("#save_dialog").dialog( "open" );

}//showSaveDialog

function getLoadedPlays(namePlay){

    var retObj={};

    switch(namePlay){
        case "playone":
            retObj = ({"box_a":{"start":{"x":264,"y":351,"s":1000},"1480364756215":{"x":264,"y":351,"s":1000},"1480364810595":{"x":62,"y":402,"s":1000},"1480364839940":{"x":319,"y":349,"s":1000},"1480364883940":{"x":424,"y":223,"s":1000},"1480364897854":{"x":396,"y":131,"s":1000},"1480364902431":{"x":396,"y":131,"s":1000},"1480364916649":{"x":396,"y":131,"s":1000}},"box_b":{"start":{"x":140,"y":249,"s":1000},"1480364756215":{"x":140,"y":249,"s":1000},"1480364810595":{"x":36,"y":218,"s":1000},"1480364839940":{"x":36,"y":218,"s":1000},"1480364883940":{"x":57,"y":143,"s":1000},"1480364897854":{"x":226,"y":80,"s":1000},"1480364902431":{"x":226,"y":80,"s":1000},"1480364916649":{"x":226,"y":80,"s":1000}},"box_c":{"start":{"x":444,"y":227,"s":1000},"1480364756215":{"x":444,"y":227,"s":1000},"1480364810595":{"x":290,"y":170,"s":1000},"1480364839940":{"x":284,"y":269,"s":1000},"1480364883940":{"x":284,"y":269,"s":1000},"1480364897854":{"x":284,"y":269,"s":1000},"1480364902431":{"x":284,"y":269,"s":1000},"1480364916649":{"x":208,"y":197,"s":1000}},"box_d":{"start":{"x":35,"y":74,"s":1000},"1480364756215":{"x":35,"y":74,"s":1000},"1480364810595":{"x":209,"y":33,"s":1000},"1480364839940":{"x":209,"y":33,"s":1000},"1480364883940":{"x":100,"y":92,"s":1000},"1480364897854":{"x":100,"y":92,"s":1000},"1480364902431":{"x":100,"y":92,"s":1000},"1480364916649":{"x":190,"y":81,"s":1000}},"box_e":{"start":{"x":542,"y":42,"s":1000},"1480364756215":{"x":542,"y":42,"s":1000},"1480364810595":{"x":372,"y":9,"s":1000},"1480364839940":{"x":372,"y":9,"s":1000},"1480364883940":{"x":382,"y":100,"s":1000},"1480364897854":{"x":382,"y":100,"s":1000},"1480364902431":{"x":382,"y":100,"s":1000},"1480364916649":{"x":308,"y":95,"s":1000}},"box_f":{"start":{"x":88,"y":-4,"s":1000},"1480364756215":{"x":88,"y":-4,"s":1000},"1480364810595":{"x":232,"y":-46,"s":1000},"1480364839940":{"x":232,"y":-46,"s":1000},"1480364883940":{"x":147,"y":46,"s":1000},"1480364897854":{"x":147,"y":46,"s":1000},"1480364902431":{"x":147,"y":46,"s":1000},"1480364916649":{"x":147,"y":46,"s":1000}},"box_g":{"start":{"x":478,"y":-39,"s":1000},"1480364756215":{"x":478,"y":-39,"s":1000},"1480364810595":{"x":352,"y":-71,"s":1000},"1480364839940":{"x":352,"y":-71,"s":1000},"1480364883940":{"x":378,"y":-17,"s":1000},"1480364897854":{"x":349,"y":-47,"s":1000},"1480364902431":{"x":349,"y":-47,"s":1000},"1480364916649":{"x":349,"y":-47,"s":1000}},"box_h":{"start":{"x":416,"y":37,"s":1000},"1480364756215":{"x":416,"y":37,"s":1000},"1480364810595":{"x":296,"y":1,"s":1000},"1480364839940":{"x":296,"y":84,"s":1000},"1480364883940":{"x":364,"y":40,"s":1000},"1480364897854":{"x":364,"y":40,"s":1000},"1480364902431":{"x":364,"y":40,"s":1000},"1480364916649":{"x":364,"y":40,"s":1000}},"box_i":{"start":{"x":295,"y":32,"s":1000},"1480364756215":{"x":295,"y":32,"s":1000},"1480364810595":{"x":121,"y":128,"s":1000},"1480364839940":{"x":244,"y":101,"s":1000},"1480364883940":{"x":244,"y":101,"s":1000},"1480364897854":{"x":244,"y":101,"s":1000},"1480364902431":{"x":244,"y":101,"s":1000},"1480364916649":{"x":232,"y":62,"s":1000}},"box_j":{"start":{"x":177,"y":-25,"s":1000},"1480364756215":{"x":177,"y":-25,"s":1000},"1480364810595":{"x":92,"y":-53,"s":1000},"1480364839940":{"x":92,"y":-53,"s":1000},"1480364883940":{"x":92,"y":-53,"s":1000},"1480364897854":{"x":92,"y":-53,"s":1000},"1480364902431":{"x":92,"y":-53,"s":1000},"1480364916649":{"x":92,"y":-53,"s":1000}},"ball":{"start":{"x":305,"y":330,"s":500},"1480364756215":{"x":305,"y":330,"s":1000},"1480364810595":{"x":107,"y":385,"s":1000},"1480364839940":{"x":361,"y":341,"s":1000},"1480364883940":{"x":459,"y":199,"s":1000},"1480364897854":{"x":429,"y":102,"s":1000},"1480364902431":{"x":261,"y":90,"s":500},"1480364916649":{"x":299,"y":78,"s":500}}});
            break;

        case "defense":
            retObj = ({"box_a":{"start":{"x":264,"y":351,"s":1000},"1480365070769":{"x":264,"y":351,"s":1000},"1480365117240":{"x":440,"y":372,"s":1000},"1480365137155":{"x":336,"y":331,"s":1000},"1480365154836":{"x":388,"y":244,"s":1000},"1480365184887":{"x":227,"y":238,"s":1000},"1480365195834":{"x":167,"y":241,"s":1000},"1480365199797":{"x":167,"y":241,"s":1000},"1480365213007":{"x":367,"y":216,"s":1000},"1480365225520":{"x":443,"y":228,"s":1000},"1480365234183":{"x":443,"y":228,"s":1000},"1480365241176":{"x":443,"y":228,"s":1000},"1480365256219":{"x":445,"y":344,"s":1000}},"box_b":{"start":{"x":140,"y":249,"s":1000},"1480365070769":{"x":140,"y":249,"s":1000},"1480365117240":{"x":266,"y":319,"s":1000},"1480365137155":{"x":139,"y":172,"s":1000},"1480365154836":{"x":139,"y":172,"s":1000},"1480365184887":{"x":89,"y":97,"s":1000},"1480365195834":{"x":89,"y":97,"s":1000},"1480365199797":{"x":121,"y":185,"s":1000},"1480365213007":{"x":121,"y":185,"s":1000},"1480365225520":{"x":121,"y":185,"s":1000},"1480365234183":{"x":121,"y":185,"s":1000},"1480365241176":{"x":121,"y":185,"s":1000},"1480365256219":{"x":97,"y":320,"s":1000}},"box_c":{"start":{"x":444,"y":227,"s":1000},"1480365070769":{"x":444,"y":227,"s":1000},"1480365117240":{"x":288,"y":154,"s":1000},"1480365137155":{"x":428,"y":177,"s":1000},"1480365154836":{"x":471,"y":66,"s":1000},"1480365184887":{"x":404,"y":174,"s":1000},"1480365195834":{"x":404,"y":174,"s":1000},"1480365199797":{"x":404,"y":174,"s":1000},"1480365213007":{"x":388,"y":242,"s":1000},"1480365225520":{"x":388,"y":242,"s":1000},"1480365234183":{"x":326,"y":147,"s":1000},"1480365241176":{"x":326,"y":147,"s":1000},"1480365256219":{"x":312,"y":354,"s":1000}},"box_d":{"start":{"x":35,"y":74,"s":1000},"1480365070769":{"x":35,"y":74,"s":1000},"1480365117240":{"x":223,"y":30,"s":1000},"1480365137155":{"x":223,"y":30,"s":1000},"1480365154836":{"x":220,"y":44,"s":1000},"1480365184887":{"x":220,"y":33,"s":1000},"1480365195834":{"x":220,"y":33,"s":1000},"1480365199797":{"x":220,"y":33,"s":1000},"1480365213007":{"x":220,"y":33,"s":1000},"1480365225520":{"x":219,"y":29,"s":1000},"1480365234183":{"x":219,"y":29,"s":1000},"1480365241176":{"x":219,"y":29,"s":1000},"1480365256219":{"x":243,"y":73,"s":1000}},"box_e":{"start":{"x":542,"y":42,"s":1000},"1480365070769":{"x":542,"y":42,"s":1000},"1480365117240":{"x":364,"y":3,"s":1000},"1480365137155":{"x":364,"y":3,"s":1000},"1480365154836":{"x":363,"y":19,"s":1000},"1480365184887":{"x":363,"y":10,"s":1000},"1480365195834":{"x":363,"y":10,"s":1000},"1480365199797":{"x":363,"y":10,"s":1000},"1480365213007":{"x":363,"y":10,"s":1000},"1480365225520":{"x":364,"y":2,"s":1000},"1480365234183":{"x":364,"y":2,"s":1000},"1480365241176":{"x":364,"y":2,"s":1000},"1480365256219":{"x":352,"y":50,"s":1000}},"box_f":{"start":{"x":88,"y":-4,"s":1000},"1480365070769":{"x":88,"y":-4,"s":1000},"1480365117240":{"x":35,"y":1,"s":1000},"1480365137155":{"x":35,"y":1,"s":1000},"1480365154836":{"x":35,"y":1,"s":1000},"1480365184887":{"x":27,"y":-30,"s":1000},"1480365195834":{"x":27,"y":-30,"s":1000},"1480365199797":{"x":27,"y":-30,"s":1000},"1480365213007":{"x":27,"y":-30,"s":1000},"1480365225520":{"x":27,"y":-30,"s":1000},"1480365234183":{"x":27,"y":-30,"s":1000},"1480365241176":{"x":27,"y":-30,"s":1000},"1480365256219":{"x":35,"y":18,"s":1000}},"box_g":{"start":{"x":478,"y":-39,"s":1000},"1480365070769":{"x":478,"y":-39,"s":1000},"1480365117240":{"x":526,"y":-38,"s":1000},"1480365137155":{"x":526,"y":-38,"s":1000},"1480365154836":{"x":526,"y":-38,"s":1000},"1480365184887":{"x":526,"y":-38,"s":1000},"1480365195834":{"x":526,"y":-38,"s":1000},"1480365199797":{"x":526,"y":-38,"s":1000},"1480365213007":{"x":526,"y":-38,"s":1000},"1480365225520":{"x":526,"y":-38,"s":1000},"1480365234183":{"x":526,"y":-38,"s":1000},"1480365241176":{"x":526,"y":-38,"s":1000},"1480365256219":{"x":525,"y":-10,"s":1000}},"box_h":{"start":{"x":416,"y":37,"s":1000},"1480365070769":{"x":416,"y":37,"s":1000},"1480365117240":{"x":527,"y":65,"s":1000},"1480365137155":{"x":527,"y":65,"s":1000},"1480365154836":{"x":527,"y":65,"s":1000},"1480365184887":{"x":527,"y":65,"s":1000},"1480365195834":{"x":527,"y":65,"s":1000},"1480365199797":{"x":527,"y":65,"s":1000},"1480365213007":{"x":527,"y":65,"s":1000},"1480365225520":{"x":527,"y":65,"s":1000},"1480365234183":{"x":527,"y":65,"s":1000},"1480365241176":{"x":527,"y":65,"s":1000},"1480365256219":{"x":517,"y":93,"s":1000}},"box_i":{"start":{"x":295,"y":32,"s":1000},"1480365070769":{"x":295,"y":32,"s":1000},"1480365117240":{"x":450,"y":204,"s":1000},"1480365137155":{"x":377,"y":142,"s":1000},"1480365154836":{"x":377,"y":142,"s":1000},"1480365184887":{"x":377,"y":142,"s":1000},"1480365195834":{"x":377,"y":142,"s":1000},"1480365199797":{"x":377,"y":142,"s":1000},"1480365213007":{"x":377,"y":142,"s":1000},"1480365225520":{"x":377,"y":142,"s":1000},"1480365234183":{"x":292,"y":-12,"s":1000},"1480365241176":{"x":292,"y":-12,"s":1000},"1480365256219":{"x":281,"y":60,"s":1000}},"box_j":{"start":{"x":177,"y":-25,"s":1000},"1480365070769":{"x":177,"y":-25,"s":1000},"1480365117240":{"x":127,"y":16,"s":1000},"1480365137155":{"x":127,"y":16,"s":1000},"1480365154836":{"x":127,"y":16,"s":1000},"1480365184887":{"x":127,"y":16,"s":1000},"1480365195834":{"x":127,"y":16,"s":1000},"1480365199797":{"x":127,"y":16,"s":1000},"1480365213007":{"x":127,"y":16,"s":1000},"1480365225520":{"x":127,"y":16,"s":1000},"1480365234183":{"x":127,"y":16,"s":1000},"1480365241176":{"x":127,"y":16,"s":1000},"1480365256219":{"x":128,"y":60,"s":1000}},"ball":{"start":{"x":305,"y":330,"s":500},"1480365070769":{"x":305,"y":330,"s":1000},"1480365117240":{"x":478,"y":392,"s":1000},"1480365137155":{"x":514,"y":234,"s":500},"1480365154836":{"x":518,"y":125,"s":500},"1480365184887":{"x":56,"y":124,"s":500},"1480365195834":{"x":128,"y":236,"s":500},"1480365199797":{"x":128,"y":236,"s":500},"1480365213007":{"x":360,"y":332,"s":500},"1480365225520":{"x":505,"y":233,"s":500},"1480365234183":{"x":517,"y":131,"s":500},"1480365241176":{"x":359,"y":199,"s":500},"1480365256219":{"x":350,"y":411,"s":1000}}});
            break;

        case "box":
            retObj=({"box_a":{"start":{"x":264,"y":351,"s":1000},"1480434524109":{"x":264,"y":351,"s":1000},"1480434587694":{"x":215,"y":115,"s":1000},"1480434616576":{"x":215,"y":115,"s":1000},"1480434628091":{"x":263,"y":57,"s":1000},"1480434636108":{"x":562,"y":80,"s":1000},"1480434680818":{"x":562,"y":80,"s":1000},"1480434684803":{"x":562,"y":80,"s":1000},"1480434688531":{"x":562,"y":80,"s":1000},"1480434694852":{"x":562,"y":80,"s":1000},"1480434704789":{"x":562,"y":80,"s":1000}},"box_b":{"start":{"x":140,"y":249,"s":1000},"1480434524109":{"x":140,"y":249,"s":1000},"1480434587694":{"x":369,"y":195,"s":1000},"1480434616576":{"x":369,"y":195,"s":1000},"1480434628091":{"x":259,"y":223,"s":1000},"1480434636108":{"x":23,"y":47,"s":1000},"1480434680818":{"x":23,"y":47,"s":1000},"1480434684803":{"x":23,"y":47,"s":1000},"1480434688531":{"x":23,"y":47,"s":1000},"1480434694852":{"x":23,"y":47,"s":1000},"1480434704789":{"x":23,"y":47,"s":1000}},"box_c":{"start":{"x":444,"y":227,"s":1000},"1480434524109":{"x":444,"y":227,"s":1000},"1480434587694":{"x":146,"y":-23,"s":1000},"1480434616576":{"x":146,"y":-23,"s":1000},"1480434628091":{"x":146,"y":-23,"s":1000},"1480434636108":{"x":146,"y":-23,"s":1000},"1480434680818":{"x":146,"y":-23,"s":1000},"1480434684803":{"x":146,"y":-23,"s":1000},"1480434688531":{"x":146,"y":-23,"s":1000},"1480434694852":{"x":146,"y":-23,"s":1000},"1480434704789":{"x":226,"y":46,"s":1000}},"box_d":{"start":{"x":35,"y":74,"s":1000},"1480434524109":{"x":35,"y":74,"s":1000},"1480434587694":{"x":211,"y":142,"s":1000},"1480434616576":{"x":301,"y":121,"s":1000},"1480434628091":{"x":301,"y":121,"s":1000},"1480434636108":{"x":301,"y":121,"s":1000},"1480434680818":{"x":301,"y":121,"s":1000},"1480434684803":{"x":368,"y":78,"s":1000},"1480434688531":{"x":346,"y":26,"s":1000},"1480434694852":{"x":346,"y":26,"s":1000},"1480434704789":{"x":346,"y":26,"s":1000}},"box_e":{"start":{"x":542,"y":42,"s":1000},"1480434524109":{"x":542,"y":42,"s":1000},"1480434587694":{"x":365,"y":2,"s":1000},"1480434616576":{"x":283,"y":-28,"s":1000},"1480434628091":{"x":283,"y":-28,"s":1000},"1480434636108":{"x":283,"y":-28,"s":1000},"1480434680818":{"x":309,"y":48,"s":1000},"1480434684803":{"x":309,"y":48,"s":1000},"1480434688531":{"x":309,"y":48,"s":1000},"1480434694852":{"x":309,"y":48,"s":1000},"1480434704789":{"x":309,"y":48,"s":1000}},"box_f":{"start":{"x":88,"y":-4,"s":1000},"1480434524109":{"x":88,"y":-4,"s":1000},"1480434587694":{"x":242,"y":-49,"s":1000},"1480434616576":{"x":242,"y":-49,"s":1000},"1480434628091":{"x":242,"y":-49,"s":1000},"1480434636108":{"x":242,"y":-49,"s":1000},"1480434680818":{"x":510,"y":-50,"s":1000},"1480434684803":{"x":510,"y":-50,"s":1000},"1480434688531":{"x":510,"y":-50,"s":1000},"1480434694852":{"x":510,"y":-50,"s":1000},"1480434704789":{"x":510,"y":-50,"s":1000}},"box_g":{"start":{"x":478,"y":-39,"s":1000},"1480434524109":{"x":478,"y":-39,"s":1000},"1480434587694":{"x":339,"y":-76,"s":1000},"1480434616576":{"x":300,"y":-61,"s":1000},"1480434628091":{"x":300,"y":-61,"s":1000},"1480434636108":{"x":300,"y":-61,"s":1000},"1480434680818":{"x":267,"y":-21,"s":1000},"1480434684803":{"x":267,"y":-21,"s":1000},"1480434688531":{"x":267,"y":-21,"s":1000},"1480434694852":{"x":267,"y":-21,"s":1000},"1480434704789":{"x":267,"y":-21,"s":1000}},"box_h":{"start":{"x":416,"y":37,"s":1000},"1480434524109":{"x":416,"y":37,"s":1000},"1480434587694":{"x":155,"y":-123,"s":1000},"1480434616576":{"x":155,"y":-123,"s":1000},"1480434628091":{"x":155,"y":-123,"s":1000},"1480434636108":{"x":155,"y":-123,"s":1000},"1480434680818":{"x":155,"y":-123,"s":1000},"1480434684803":{"x":155,"y":-123,"s":1000},"1480434688531":{"x":155,"y":-123,"s":1000},"1480434694852":{"x":155,"y":-123,"s":1000},"1480434704789":{"x":155,"y":-123,"s":1000}},"box_i":{"start":{"x":295,"y":32,"s":1000},"1480434524109":{"x":295,"y":32,"s":1000},"1480434587694":{"x":341,"y":-27,"s":1000},"1480434616576":{"x":341,"y":-27,"s":1000},"1480434628091":{"x":341,"y":-27,"s":1000},"1480434636108":{"x":341,"y":-27,"s":1000},"1480434680818":{"x":66,"y":-124,"s":1000},"1480434684803":{"x":66,"y":-124,"s":1000},"1480434688531":{"x":66,"y":-124,"s":1000},"1480434694852":{"x":66,"y":-124,"s":1000},"1480434704789":{"x":66,"y":-124,"s":1000}},"box_j":{"start":{"x":177,"y":-25,"s":1000},"1480434524109":{"x":177,"y":-25,"s":1000},"1480434587694":{"x":230,"y":-52,"s":1000},"1480434616576":{"x":284,"y":-72,"s":1000},"1480434628091":{"x":284,"y":-72,"s":1000},"1480434636108":{"x":284,"y":-72,"s":1000},"1480434680818":{"x":284,"y":-72,"s":1000},"1480434684803":{"x":284,"y":-72,"s":1000},"1480434688531":{"x":284,"y":-72,"s":1000},"1480434694852":{"x":284,"y":-72,"s":1000},"1480434704789":{"x":284,"y":-72,"s":1000}},"ball":{"start":{"x":305,"y":330,"s":500},"1480434524109":{"x":305,"y":330,"s":1000},"1480434587694":{"x":181,"y":28,"s":500},"1480434616576":{"x":181,"y":28,"s":500},"1480434628091":{"x":181,"y":28,"s":500},"1480434636108":{"x":181,"y":28,"s":500},"1480434680818":{"x":181,"y":28,"s":500},"1480434684803":{"x":181,"y":28,"s":500},"1480434688531":{"x":181,"y":28,"s":500},"1480434694852":{"x":331,"y":89,"s":500},"1480434704789":{"x":300,"y":73,"s":500}}});
            break;

        case "pressbreaker":
            retObj=({"box_a":{"start":{"x":264,"y":351,"s":1000},"1480365449968":{"x":264,"y":351,"s":1000},"1480365529557":{"x":116,"y":39,"s":1000},"1480365556682":{"x":116,"y":81,"s":1000},"1480365596329":{"x":116,"y":81,"s":1000},"1480365613778":{"x":116,"y":81,"s":1000},"1480365628044":{"x":116,"y":81,"s":1000},"1480365633173":{"x":116,"y":81,"s":1000},"1480365652416":{"x":79,"y":397,"s":1000}},"box_b":{"start":{"x":140,"y":249,"s":1000},"1480365449968":{"x":140,"y":249,"s":1000},"1480365529557":{"x":519,"y":78,"s":1000},"1480365556682":{"x":307,"y":97,"s":1000},"1480365596329":{"x":315,"y":47,"s":1000},"1480365613778":{"x":315,"y":47,"s":1000},"1480365628044":{"x":315,"y":47,"s":1000},"1480365633173":{"x":315,"y":47,"s":1000},"1480365652416":{"x":417,"y":385,"s":1000}},"box_c":{"start":{"x":444,"y":227,"s":1000},"1480365449968":{"x":444,"y":227,"s":1000},"1480365529557":{"x":295,"y":273,"s":1000},"1480365556682":{"x":295,"y":273,"s":1000},"1480365596329":{"x":295,"y":273,"s":1000},"1480365613778":{"x":295,"y":273,"s":1000},"1480365628044":{"x":295,"y":273,"s":1000},"1480365633173":{"x":295,"y":273,"s":1000},"1480365652416":{"x":326,"y":403,"s":1000}},"box_d":{"start":{"x":35,"y":74,"s":1000},"1480365449968":{"x":35,"y":74,"s":1000},"1480365529557":{"x":37,"y":342,"s":1000},"1480365556682":{"x":37,"y":342,"s":1000},"1480365596329":{"x":60,"y":497,"s":1000},"1480365613778":{"x":60,"y":497,"s":1000},"1480365628044":{"x":58,"y":418,"s":1000},"1480365633173":{"x":58,"y":418,"s":1000},"1480365652416":{"x":58,"y":418,"s":1000}},"box_e":{"start":{"x":542,"y":42,"s":1000},"1480365449968":{"x":542,"y":42,"s":1000},"1480365529557":{"x":542,"y":312,"s":1000},"1480365556682":{"x":542,"y":312,"s":1000},"1480365596329":{"x":542,"y":312,"s":1000},"1480365613778":{"x":542,"y":312,"s":1000},"1480365628044":{"x":493,"y":420,"s":1000},"1480365633173":{"x":493,"y":420,"s":1000},"1480365652416":{"x":493,"y":420,"s":1000}},"box_f":{"start":{"x":88,"y":-4,"s":1000},"1480365449968":{"x":88,"y":-4,"s":1000},"1480365529557":{"x":135,"y":-48,"s":1000},"1480365556682":{"x":262,"y":-2,"s":1000},"1480365596329":{"x":140,"y":-15,"s":1000},"1480365613778":{"x":256,"y":-51,"s":1000},"1480365628044":{"x":256,"y":-51,"s":1000},"1480365633173":{"x":256,"y":-51,"s":1000},"1480365652416":{"x":227,"y":295,"s":1000}},"box_g":{"start":{"x":478,"y":-39,"s":1000},"1480365449968":{"x":478,"y":-39,"s":1000},"1480365529557":{"x":471,"y":-75,"s":1000},"1480365556682":{"x":318,"y":-22,"s":1000},"1480365596329":{"x":318,"y":-22,"s":1000},"1480365613778":{"x":324,"y":-63,"s":1000},"1480365628044":{"x":324,"y":-63,"s":1000},"1480365633173":{"x":324,"y":-63,"s":1000},"1480365652416":{"x":472,"y":257,"s":1000}},"box_h":{"start":{"x":416,"y":37,"s":1000},"1480365449968":{"x":416,"y":37,"s":1000},"1480365529557":{"x":497,"y":147,"s":1000},"1480365556682":{"x":497,"y":147,"s":1000},"1480365596329":{"x":497,"y":147,"s":1000},"1480365613778":{"x":497,"y":147,"s":1000},"1480365628044":{"x":347,"y":150,"s":1000},"1480365633173":{"x":347,"y":150,"s":1000},"1480365652416":{"x":371,"y":254,"s":1000}},"box_i":{"start":{"x":295,"y":32,"s":1000},"1480365449968":{"x":295,"y":32,"s":1000},"1480365529557":{"x":289,"y":70,"s":1000},"1480365556682":{"x":289,"y":70,"s":1000},"1480365596329":{"x":289,"y":70,"s":1000},"1480365613778":{"x":153,"y":-121,"s":1000},"1480365628044":{"x":153,"y":-121,"s":1000},"1480365633173":{"x":153,"y":-121,"s":1000},"1480365652416":{"x":166,"y":195,"s":1000}},"box_j":{"start":{"x":177,"y":-25,"s":1000},"1480365449968":{"x":177,"y":-25,"s":1000},"1480365529557":{"x":88,"y":97,"s":1000},"1480365556682":{"x":88,"y":97,"s":1000},"1480365596329":{"x":85,"y":-134,"s":1000},"1480365613778":{"x":85,"y":-134,"s":1000},"1480365628044":{"x":85,"y":-134,"s":1000},"1480365633173":{"x":85,"y":-134,"s":1000},"1480365652416":{"x":127,"y":230,"s":1000}},"ball":{"start":{"x":305,"y":330,"s":500},"1480365449968":{"x":305,"y":330,"s":1000},"1480365529557":{"x":148,"y":34,"s":500},"1480365556682":{"x":288,"y":97,"s":500},"1480365596329":{"x":146,"y":70,"s":500},"1480365613778":{"x":293,"y":71,"s":500},"1480365628044":{"x":293,"y":292,"s":500},"1480365633173":{"x":455,"y":522,"s":500},"1480365652416":{"x":455,"y":522,"s":500}}});
            break;

        case "start":
        default:
            retObj = false;
            break;
    }//switch

    return retObj;

}//tempLoadPlays

function play()
{
    var curr_idx = $("#playList").data("current_idx");
    curr_idx = curr_idx <= 0 ? 0 : curr_idx--;
    console.log("clicked move button");
    var moveButtonText = $("#move_button > span.ui-button-text").html();
    console.log(String(moveButtonText).toLowerCase());
    if(String(moveButtonText).toLowerCase() == "play")
    {
        console.log("clicked 'play'");
        $("#playList").data("isPaused",false);
        var playListArr = $("#playList").data("list");
        curr_idx = playListArr.length <=  curr_idx ? 0 : curr_idx;

        runSet(curr_idx);
        $("#move_button").button({icons: {primary: "ui-icon-pause"},text:true});
        $("#move_button > span.ui-button-text").html("Pause");
    }
    else
    {
        console.log("clicked 'pause'");
        $("#move_button").button({icons: {primary: "ui-icon-play"},text:true});
        $("#move_button > span.ui-button-text").html("Play");
        $("#playList").data("isPaused",true);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 4/17/14
 * Time: 1:32 PM
 */

ini_set("session.cookie_domain", "plays.readystats.com");
session_set_cookie_params(0, '/', 'plays.readystats.com');
session_start();

if(!array_key_exists("key",$_SESSION))
{
    $uniqueSession = uniqid();
    $_SESSION["key"] = $uniqueSession;
}



// http://plays.readystats.com/crud.php
global $ip;
global $link;

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

include 'dbconn.php';


$link = mysqli_connect($host_name, $user_name, $password, $database);
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

switch($_REQUEST["a"])
{
    case "c":
        // add or append data to a file
        //$d = new DateTime('now');
        //$fileDateStr = $d->format('Ymd');

        //$contentdata = $_REQUEST["data"];
        //$contents = "\n$contentdata\n<hr>\n";
        //$file = "data/$fileDateStr.html";
        //$fileContents = file_get_contents($file);
        //file_put_contents($file, $contents . $fileContents);

        $eventData = json_decode($_REQUEST["data"]);

        $playerList = json_encode($eventData->players);

        $gameTitle = $eventData->gametitle;
        $opponent = $eventData->opponent;
        $eventStat = $eventData->event;
        //$date = $d->format('Y-m-d H:i:s');
        $sql = "INSERT INTO `db528349673`.`stats_GameCollection` (`ID`, `GameTitle`, `Opponent`, `PlayerName`, `StatName`, `StatType`, `StatValue`, `Submitter`, `DateCreated`, `DateModified`, `IPAddress`, `SessionID`, `IsDeleted`) VALUES
        (NULL,'$gameTitle','$opponent','$playerList','$eventStat',NULL,NULL,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP, '$ip',  '', '0');";
        //echo $sql;
        if($result = mysqli_query($link, $sql)){
            echo "success";
        }else{
            echo "error";
        }
        break;

    case "r":
    default:
        switch($_REQUEST["t"])
        {
            case "playerlist":
                $teamid = $_REQUEST["id"];

                if($result = mysqli_query($link, "SELECT FirstName, LastName, PlayerID, PlayerNumber, isPlayerIn FROM vw_teamsplayers where teamid=$teamid;") or die(mysqli_error($link))){
                    $resultArr = array();
                    while($row = $result->fetch_array(MYSQLI_ASSOC))
                    {
                        $pid = $row['PlayerID'];
                        $fname = $row['FirstName'];
                        $lname =  $row['LastName'];
                        $pnum =  $row['PlayerNumber'];
                        $pin =  $row['isPlayerIn'];
                        $nameArr = array("FirstName"=>$fname,"LastName"=>$lname,"PlayerID"=>$pid,"PlayerNumber"=>$pnum,"isPlayerIn"=>$pin);
                        array_push($resultArr,$nameArr);
                    }
                    echo json_encode($resultArr);
                }//IF SUCCESS QUERY
                break;

            case "statlist":

                if($result = mysqli_query($link, "SELECT st.StatTypeID, st.Name,st.Group FROM stattypes st;") or die(mysqli_error($link))){
                    $resultArr = array();
                    while($row = $result->fetch_array(MYSQLI_ASSOC))
                    {
                        $stid = $row['StatTypeID'];
                        $stname = $row['Name'];
                        $stgroup =  $row['Group'];
                        $nameArr = array("ID"=>$stid,"Name"=>$stname,"Group"=>$stgroup);
                        array_push($resultArr,$nameArr);
                    }
                    echo json_encode($resultArr);
                }//IF SUCCESS QUERY
                break;

            case "addstat":
                $gameid = $_REQUEST["gid"];
                $playerid = $_REQUEST["pid"];
                $statid = $_REQUEST["sid"];
                $note = $_REQUEST["n"];
                $statkeeper = $_REQUEST["sk"];
                $measurement = $_REQUEST["m"];
                $secondFromStart = $_REQUEST["gc"];
                //echo "INSERT INTO `statitems` (`GameID`,`StatTypeID`,`PlayerID`,`Measurement`,`Note`,`CreatedBy`)VALUES($gameid,$statid,$playerid,$measurement,'$note','$statkeeper');";
                $result = mysqli_query($link, "INSERT INTO `statitems` (`GameID`,`StatTypeID`,`PlayerID`,`Measurement`,`Note`,`CreatedBy`,`SecondsFromStart`)VALUES($gameid,$statid,$playerid,$measurement,'$note','$statkeeper',$secondFromStart);");
                if($result){
                    if($statid==14 || $statid==13)
                    {
                        $value=0;
                        if($statid==13){$value=1;}
                        $result = mysqli_query($link, "UPDATE `players` SET `isPlayerIn`='$value' WHERE `PlayerID`=$playerid;");
                        if($result){
                            echo "{status:'success', message:'success'}";
                        }else{
                            $fail = mysqli_error($link);
                            echo "{status:'fail', message:'$fail'}";
                        } // if/else result
                    }//23 or 14

                }else{
                    $fail = mysqli_error($link);
                    echo "{status:'fail', message:'$fail'}";
                }
                break;

            case 'deletestat':
                $statid = $_REQUEST["sid"];
                $result = mysqli_query($link, "UPDATE `statitems` SET `isDeleted`='1' WHERE `StatItemID`='$statid';");
                if($result){
                    echo "{status:'success', message:'success'}";
                }else{
                    $fail = mysqli_error($link);
                    echo "{status:'fail', message:'$fail'}";
                }
                break;

            case 'statlog':
                $gid = $_REQUEST["gid"];
                //echo "SELECT statitemid, gameid, playerid, firstname, lastname, name, measurement, datecreated FROM vw_gamestats where `GameID`=$gid and `isDeleted`=0;";
                $result = mysqli_query($link, "SELECT statitemid, gameid, playerid, firstname, lastname, name, measurement, datecreated, SecondsFromStart  FROM vw_gamestats where `GameID`=$gid and `isDeleted`=0 order by datecreated desc;");
                if($result){
                    $resultArr = array();
                    while($row = $result->fetch_array(MYSQLI_ASSOC))
                    {
                        array_push($resultArr,$row);
                    }
                    echo json_encode($resultArr);
                }else{
                    $fail = mysqli_error($link);
                    echo "{status:'fail', message:'$fail'}";
                }
                break;

            case 'gamereport':
                $gid = $_REQUEST["gid"];
                $sql = "SELECT
                            playerid,
                            stattypeid,
                            max(name) as 'stat',
                            max(FirstName) as 'firstname',
                            max(LastName) as 'lastname',
                            sum(measurement) as 'value'
                            FROM vw_gamestats
                            where gameid=$gid and isDeleted=0
                            group by playerid,stattypeid";
                $result = mysqli_query($link, $sql);
                if($result){
                    $resultArr = array();
                    while($row = $result->fetch_array(MYSQLI_ASSOC))
                    {
                        array_push($resultArr,$row);
                    }
                }else{
                    $fail = mysqli_error($link);
                    echo "{status:'fail', message:'$fail'}";
                }
                echo json_encode($resultArr);
                break;//gamereport


            default:
                if($result = mysqli_query($link, "SELECT distinct GameTitle, Opponent FROM stats_GameCollection WHERE 1=1 and GameTitle is not NULL and GameTitle !=''") or die(mysqli_error($link))){
                    //$resultArr = [];
                    while($row = $result->fetch_array(MYSQLI_ASSOC))
                    {
                        $gameTitle = $row['GameTitle'];
                        $gameOpp =  $row['Opponent'];
                        $resultArr["$gameTitle"]=$gameOpp;
                    }
                    echo json_encode($resultArr);
                }//IF SUCCESS QUERY
        }
        break;

    case "u":
        // update a file
        echo("update");
        break;

    case "d":
        // delete an entry
        echo("delete");
        break;

}//switch


?>


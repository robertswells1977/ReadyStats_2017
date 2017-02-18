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

$host_name  = "db528349673.db.1and1.com";
$database   = "db528349673";
$user_name  = "dbo528349673";
$password   = "de20d880-e47d-4fa4-9207-df6e7560ce90";


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
            case "gamelist":
            default:
                if($result = mysqli_query($link, "SELECT distinct GameTitle, Opponent FROM stats_GameCollection WHERE 1=1 and GameTitle is not NULL and GameTitle !=''") or die(mysqli_error($link))){
                    $resultArr = [];
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


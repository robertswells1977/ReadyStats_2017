<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 4/17/14
 * Time: 1:32 PM
 */


switch($_REQUEST["a"])
{
    case "c":
        // add or append data to a file
        $d = new DateTime('now');
        $fileDateStr = $d->format('Ymd');

        $contentdata = $_REQUEST["data"];
        $contents = "\n$contentdata\n<hr>\n";
        $file = "data/$fileDateStr.html";

        $fileContents = file_get_contents($file);
        file_put_contents($file, $contents . $fileContents);
        echo("write");

        break;

    case "r":
    default:
    echo("read");
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


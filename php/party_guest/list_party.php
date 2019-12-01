<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Choose Party</title>
    <link href="../../css/index.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<?php
require_once '../database.php';

$valid = true;
if (array_key_exists("id", $_POST)) {
    $id = $_POST["id"];
} elseif (array_key_exists("id", $_GET)) {
    $id = $_GET["id"];
} else {
    print "No guest ID specified";
    $valid = false;
}

if ($valid) {
    if ($ids = mysqli_query($link, "SELECT * FROM `guest` WHERE `Guest_ID` = {$id}")) {
        if (mysqli_num_rows($ids) == 0) {
            print "No guest with Guest_ID = {$id}";
            $valid = false;
        }
    } else {
        print "mysqli_query failed: " . mysqli_error($link);
        $valid = false;
    }
}

if ($valid) {
    $selected_parties = array();
    $query1 = "SELECT party.Party_Num,`Time`,Place,Host_Name FROM `party`, `party_guest` WHERE party.Party_Num=party_guest.Party_Num AND party_guest.Guest_ID={$id}";
    if ($result1 = mysqli_query($link, $query1)) {
        $cnt = mysqli_num_rows($result1);
        while ($row = mysqli_fetch_assoc($result1)) {
            $selected_parties[] = $row['Party_Num'];
        }
    } else {
        print "mysqli_query failed: " . mysqli_error($link);
        $valid = false;
    }
}

if ($valid) {
    if ($result2 = mysqli_query($link, "SELECT * FROM `party` ")) {
        print "<h2>Choose What Parties You Want to Go (Guest_ID = {$id})</h2>";
        $cnt = mysqli_num_rows($result2);
        if ($cnt == 0) {
            print "No party found.";
        } else {
            print "<form action=\"submit.php\" method=\"post\">";
            print "<input type='hidden' id='id' name='id' value='{$id}'/>";
            print "<table><caption>Party Table</caption>\n";
            print "<tr><th>No.</th> <th>Time</th> <th>Place</th> <th>Host</th> <th>Status</th></tr>\n";

            while ($row = mysqli_fetch_assoc($result2)) {
                $party_num = $row['Party_Num'];
                echo "<tr>";
                echo "<td>" . $party_num . "</td>";
                echo "<td>" . $row['Time'] . "</td>";
                echo "<td>" . $row['Place'] . "</td>";
                echo "<td>" . $row['Host_Name'] . "</td>";
                if ($row['Time'] < date('Y/m/d H:i')) {
                    echo "<td>Ended</td>";
                } elseif (in_array($party_num, $selected_parties)) {
                    echo "<td><span style=\"color:blue\">Registered</span></td>";
                } else {
                    echo "<td><input type='checkbox' value='{$party_num}' name='nums[]'/></td>";
                }
                echo "</tr>\n";
            }
            print "</table>\n";
            print "<br>" . $cnt . ($cnt == 1 ? " party" : " parties") . " found.  To sign up for selected parties, click <input type=\"submit\" value=\"this button\"/>.";
            print "</form>\n";
            print "";
        }
    } else {
        print "mysqli_query failed: " . mysqli_error($link);
        $valid = false;
    }
}

mysqli_close($link);
?>
<br><br>[<a href="../../hw/unit4/index.html">Return Unit 4</a>]&nbsp;&nbsp;
<?php
if ($valid) {
    print "[<a href=\"?id={$id}\">Refresh</a>]\n";
}
?>
</body>
</html>
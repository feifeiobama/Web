<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Guest Book</title>
    <link href="../../css/index.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<?php
require_once '../database.php';

$valid = true;

if (array_key_exists("num", $_POST)) {
    $num = $_POST["num"];
    if ($party = mysqli_query($link, "SELECT * FROM `party` WHERE `Party_Num` = {$num}")) {
        if (mysqli_num_rows($party) == 0) {
            print "No party with Party_Num = {$num}";
            $valid = false;
        }
    } else {
        print "mysqli_query failed: ". mysqli_error($link);
        $valid = false;
    }
} else {
    print "No Party_Num specified";
    $valid = false;
}

if ($valid) {
    $query = "SELECT guest.Guest_ID,Guest_Name,Age,Gender,E_mail FROM `guest`, `party_guest` WHERE guest.Guest_ID=party_guest.Guest_ID AND party_guest.Party_Num={$num}";
    if ($result = mysqli_query($link, $query)) {
        print "<h2>Guests for the party with Party_Num = {$num}</h2>";
        $cnt = mysqli_num_rows($result);
        if ($cnt == 0) {
            print "No guest found";
        } else {
            print "<table><caption>Guest Book</caption>\n";
            print "<tr><th>ID</th> <th>Name</th> <th>Age</th> <th>Gender</th> <th>E-mail</th></tr>\n";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['Guest_ID'] . "</td>";
                echo "<td>" . $row['Guest_Name'] . "</td>";
                echo "<td>" . $row['Age'] . "</td>";
                echo "<td>" . $row['Gender'] . "</td>";
                echo "<td>" . $row['E_mail'] . "</td>";
                echo "</tr>\n";
            }
            print "</table>\n";
            print "<br>" . $cnt . " guest(s) in total.\n";
        }
    } else {
        print "mysqli_query failed: ". mysqli_error($link);
    }
}

mysqli_close($link);
?>
<br><br>[<a href="../../hw/unit4/index.html">Return Unit 4</a>]
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Party Table</title>
    <link href="../../css/index.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<?php
require_once '../database.php';

if (array_key_exists('date', $_POST)) {
    $date = htmlspecialchars(trim($_POST['date']));
    $title = "Party held on " . $date;
    $condition = "WHERE `Time` LIKE '%{$date}%'";
} elseif (array_key_exists('host', $_POST)) {
    $host = htmlspecialchars(trim($_POST['host']));
    $title = "Party hosted by " . $host;
    $host_sql = addslashes($host);
    $condition = "WHERE `Host_Name`='{$host_sql}'";
} else {
    $title = "List of Recorded Parties";
    $condition = "";
}

if ($result = mysqli_query($link, "SELECT * FROM `party` " . $condition)) {
    print "<h2>{$title}</h2>";
    $cnt = mysqli_num_rows($result);
    if ($cnt == 0) {
        print "No party found.";
    } else {
        print "<form action=\"delete.php\" method=\"post\">";
        print "<table><caption>Party Table</caption>\n";
        print "<tr><th>No.</th> <th>Time</th> <th>Place</th> <th>Host</th> </tr>\n";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['Party_Num'] . "</td>";
            echo "<td>" . $row['Time'] . "</td>";
            echo "<td>" . $row['Place'] . "</td>";
            echo "<td>" . $row['Host_Name'] . "</td>";
            echo "</tr>\n";
        }
        print "</table>\n";
        print "<br>" . $cnt . ($cnt == 1 ? " party" : " parties") . " found. ";
        print "</form>\n";
        print "";
    }
} else {
    print "mysqli_query failed: " . mysqli_error($link);
}

mysqli_close($link);
?>
<br><br>[<a href="../../hw/unit4/index.html">Return Unit 4</a>]&nbsp;&nbsp;
<?php
if ($condition == "") {
    print "[<a href=\"\">Refresh</a>]\n";
}
?>
</body>
</html>
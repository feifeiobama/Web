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

if ($result = mysqli_query($link, "SELECT * FROM `guest`")) {
    print "<h2>Results of the Basic Information Questionnaire</h2>";
    $cnt = mysqli_num_rows($result);
    if ($cnt == 0) {
        print "No result found.\n";
    } else {
        print "<form action=\"delete.php\" method=\"post\">";
        print "<table><caption>Guest Book</caption>\n";
        print "<tr><th>ID</th> <th>Name</th> <th>Age</th> <th>Gender</th> <th>E-mail</th> <th>Checkbox</th></tr>\n";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['Guest_ID'] . "</td>";
            echo "<td>" . $row['Guest_Name'] . "</td>";
            echo "<td>" . $row['Age'] . "</td>";
            echo "<td>" . $row['Gender'] . "</td>";
            echo "<td>" . $row['E_mail'] . "</td>";
            echo "<td><input type='checkbox' value='{$row['Guest_ID']}' name='ids[]'/></td>";
            echo "</tr>\n";
        }
        print "</table>\n";
        print "<br>" . $cnt . " item(s) in total. To delete selected items, click <input type=\"submit\" value=\"this button\"/>.";
        print "</form>\n";
    }
} else {
    print "mysqli_query failed: ". mysqli_error($link);
}

mysqli_close($link);
?>
<br><br>[<a href="../../hw/unit4/index.html">Return Unit 4</a>]&nbsp;&nbsp;[<a href="list.php">Refresh</a>]
</body>
</html>
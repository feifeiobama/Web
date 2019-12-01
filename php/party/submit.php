<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Party</title>
</head>
<body>
<?php
require_once '../database.php';

$valid = true;
$keys = array('time', 'place', 'host');
foreach ($keys as $key) {
    if (!array_key_exists($key, $_POST)) {
        $valid = false;
    }
}

if ($valid) {
    if ($stmt = mysqli_prepare($link, "INSERT INTO `party` (`Time`, `Place`, `Host_Name`) VALUES (?, ?, ?)")) {
        $time = htmlspecialchars(trim($_POST['time']));
        $place = htmlspecialchars(trim($_POST['place']));
        $host = htmlspecialchars(trim($_POST['host']));

        mysqli_stmt_bind_param($stmt, 'sss', $time, $place, $host);

        if (!mysqli_stmt_execute($stmt)) {
            print "mysqli_stmt_execute failed: " . mysqli_stmt_error($stmt);
        } else {
            print "Information about the new party has been recorded. Party_Num is " . mysqli_insert_id($link) . ".";
        }
        mysqli_stmt_close($stmt);
    } else {
        print "mysqli_prepare failed: " . mysqli_error($link);
    }
} else {
    print "Incomplete submission.\n";
}

mysqli_close($link);
?>
<br><br>[<a href="../../hw/unit4/index.html">Return</a>]
</body>
</html>
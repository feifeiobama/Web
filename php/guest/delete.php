<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Deleted</title>
</head>
<body>
<?php
require_once '../database.php';

if (!array_key_exists('ids', $_POST)) {
    print "No items selected.";
} else {
    $ids = $_POST['ids'];
    if (!is_array($ids)) $ids = array($ids);

    $query = "DELETE FROM `guest` WHERE `Guest_ID` in (";
    $first = true;
    foreach ($ids as $id) {
        if ($first) $first = false;
        else $query .= ', ';
        $query .= $id;
    }
    $query .= ')';

    if (mysqli_query($link, $query)) {
        print count($ids) . " item(s) selected, " . mysqli_affected_rows($link) . " item(s) deleted.";
    } else {
        print "mysqli_query failed: ". mysqli_error($link);
    }
}

mysqli_close($link);
?>
<br><br>[<a href="list.php">Return</a>]
</body>
</html>
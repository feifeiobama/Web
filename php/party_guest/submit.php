<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Party</title>
</head>
<body>
<?php
require_once '../database.php';

if (!array_key_exists('id', $_POST)) {
    print "No Guest_ID specified.";
    print "<br><br>[<a href=\"../../hw/unit4/index.html\">Return Unit 4</a>]";
} else {
    $id = $_POST['id'];
    if (!array_key_exists('nums', $_POST)) {
        print "No parties selected.";
    } else {
        $nums = $_POST['nums'];
        if (!is_array($nums)) $ids = array($nums);

        $query = "INSERT IGNORE INTO `party_guest` (`Guest_ID`, `Party_Num`) VALUES";
        $first = true;
        foreach ($nums as $num) {
            if ($first) $first = false;
            else $query .= ', ';
            $query .= "(${id},${num})";
        }

        if (mysqli_query($link, $query)) {
            $selected = count($nums);
            $affected = mysqli_affected_rows($link);
            print $selected . ($selected == 1 ? " party" : " parties") . " selected, ";
            print $affected . ($affected == 1 ? " party" : " parties") . " reserved.\n";
        } else {
            print "mysqli_query failed: ". mysqli_error($link);
        }
    }
    print "<br><br>[<a href=\"list_party.php?id={$id}\">Return</a>]";
}

mysqli_close($link);
?>
</body>
</html>
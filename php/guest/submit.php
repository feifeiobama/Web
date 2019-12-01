<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thanks</title>
</head>
<body>
<?php
require_once '../database.php';

$valid = true;
$keys = array('name', 'age', 'gender', 'email');
foreach ($keys as $key) {
    if (!array_key_exists($key, $_POST)) {
        $valid = false;
    }
}

if ($valid) {
    if ($stmt = mysqli_prepare($link, "INSERT INTO `guest` (`Guest_Name`, `Age`, `Gender`, `E_mail`) VALUES (?, ?, ?, ?)")) {
        $name = htmlspecialchars(trim($_POST['name']));
        $age = $_POST['age'];
        $gender = htmlspecialchars(trim($_POST['gender']));
        $email = htmlspecialchars(trim($_POST['email']));

        mysqli_stmt_bind_param($stmt, 'siss', $name, $age, $gender, $email);

        if (!mysqli_stmt_execute($stmt)) {
            print "mysqli_stmt_execute failed: " . mysqli_stmt_error($stmt);
        } else {
            print "Thanks for participating in our questionnaire. Your ID is " . mysqli_insert_id($link) . ".";
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
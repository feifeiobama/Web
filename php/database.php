<?php
$link = mysqli_connect('localhost:3306', 'usr_2019_7', '12345678', 'db_2019_7');
if (mysqli_connect_errno()) {
    print "mysqli_connect failed: " . mysqli_connect_error();
    exit;
}
<?php
// $mysqli = new mysqli('db', 'guestuser', 'strongpassword', 'wedding_guestbook');
$mysqli = new mysqli("localhost", "root", "", "wedding_guestbook");
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}
?>

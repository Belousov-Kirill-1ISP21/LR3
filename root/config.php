<?php
session_start();
$conn = mysqli_connect("localhost", "root", "usbw", "korochki_est");
if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}
?>
<?php
$mysql = mysqli_connect("webdev.iyaserver.com", "twilcher", "AcadDev_Wilcher_6801994716", "twilcher_endless");

$id = $_REQUEST['id'];

$sql = "SELECT a.piece_name, a.piece_description, p.fname, p.lname, p.a_description, p.a_portrait 
        FROM artworks a
        LEFT JOIN artists p ON a.artist_id = p.PID 
        WHERE a.piece_id = $id";

$result = mysqli_query($mysql, $sql);
$row = mysqli_fetch_array($result);

$artistName = trim($row['fname'] . ' ' . $row['lname']);
echo $row['piece_name'] . "|||" . $row['piece_description'] . "|||" . $artistName . "|||" . $row['a_description'] . "|||" . $row['a_portrait'];

mysqli_close($mysql);
?>
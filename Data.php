<?php
// รับข้อมูลมาจาก insert_From.php 
// เพื่อจำลองข้อมูลพนักงาน ขึ้นมา และส่งข้อมูลไปหน้า g_index.php

 require ('goconnect.php');    

$fname=$_POST["fname"];
$lname=$_POST["lname"];


$sql = "INSERT INTO  employee (fname,lname) VALUES  ('$fname','$lname')";

$result=mysqli_query($gcon,$sql); //สั่งรันsql

    
if($result){
    header("location:g_index.php");
}else{
    echo mysqli_error($gcon);
}

?>
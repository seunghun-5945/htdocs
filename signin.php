<?php

$servername = "localhost";  
$username = "root";        
$password = "";             
$dbname = "testphp_user";  


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
} else {
    echo "MySQL 데이터베이스에 성공적으로 연결되었습니다!";
}

$conn->close();
?>

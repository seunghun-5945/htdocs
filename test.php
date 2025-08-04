<?php

$servername = "localhost";  
$username = "root";        
$password = "";             
$dbname = "testphp_user";  


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
    echo "연결에 실패하였습니다.";
} else {
    echo "MySQL 데이터베이스에 성공적으로 연결되었습니다! <br>";
}

$sql = "SELECT id, username, email FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " | 이름: " . $row["username"] . " | 이메일: " . $row["email"] . "<br>";
    }
} else {
    echo "조회 결과 없음";
}

$conn->close();
?>

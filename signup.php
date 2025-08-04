<?php

// cors 정책 우회
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	exit(0);
}

$servername = "localhost";  
$username = "root";        
$password = "";             
$dbname = "testphp_user";  

// mysql 통신을 위한 connection 객체 만들기
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "데이터베이스 연결 실패: " . $conn->connect_error
    ]);
    exit();
}

$rawData = file_get_contents("php://input");
error_log("⚠ 받은 원시 데이터: " . $rawData);  // Apache or PHP log에서 확인 가능

$data = json_decode($rawData, true);
if ($data === null) {
    echo json_encode([
        "success" => false,
        "message" => "JSON 데이터가 올바르지 않습니다.",
        "raw" => $rawData  // 에러 응답에도 포함시켜서 확인
    ]);
    exit();
}
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$username || !$email || !$password) {
    echo json_encode([
        "success" => false,
        "message" => "모든 필드를 입력해주세요."
    ]);
    exit();
}

echo json_encode([
    "success" => true,
    "message" => "데이터가 정상적으로 처리되었습니다."
]);

// 비밀번호는 반드시 해시 처리해서 저장해야 안전합니다!
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// SQL 준비문 생성 (SQL Injection 방지)
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

if ($stmt === false) {
    echo json_encode([
        "success" => false,
        "message" => "쿼리 준비 실패: " . $conn->error
    ]);
    exit();
}

$stmt->bind_param("sss", $username, $email, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "회원가입이 성공적으로 완료되었습니다."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "회원가입 실패: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();

// echo 함수는 배열값은 표기하지 못한다고 한다(문자열만 가능함) -> 따라서 var_dump 사용해서 자료형 타입 확인
// var_dump($data);       
// echo $username;        
// echo $email;
// echo $password;


// if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//     echo json_encode([
//         "success" => false,
//         "message" => "유효한 이메일 주소를 입력해주세요."
//     ]);
//     exit();
// }

?>
<?php
// Подключение к базе данных
$db_host = "localhost";
$db_username = "root";
$db_password = "12345";
$db_name = "rental_app";

$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);
if (!$conn) {
  die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

// Получение имени пользователя из POST-запроса
$username = $_POST["username"];

// Подготовка и выполнение запроса к базе данных
$query = "SELECT * FROM users WHERE user_name = '$username'";
$result = mysqli_query($conn, $query);

// Формирование ответа
$response = array();
if (mysqli_num_rows($result) > 0) {
  // Имя пользователя занято
  $response["available"] = false;
} else {
  // Имя пользователя доступно
  $response["available"] = true;
}

// Отправка JSON-ответа
header("Content-Type: application/json");
echo json_encode($response);

// Закрытие соединения с базой данных
mysqli_close($conn);
?>

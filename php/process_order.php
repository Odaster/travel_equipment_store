<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION["username"])) {
  header("Location: login1.php");
  exit();
}

if (isset($_POST['add_to_cart'])) {
  $servername = "localhost";
  $username = "root";
  $password = "12345";
  $dbname = "rental_app";

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Проверка соединения
  if ($conn->connect_error) {
      die("Ошибка подключения: " . $conn->connect_error);
  }
  $username = $_SESSION["username"];
  $user_id =getUserIdByUsername($conn,$username);
  $equipment_id = $_POST['equipment_id'];
  $equipment_price = $_POST['price'];

  // Проверка, существует ли уже запись в корзине для данного товара и пользователя
  $checkQuery = "SELECT * FROM Cart WHERE user_id = '$user_id' AND equipment_id = '$equipment_id'";
  $checkResult = $conn->query($checkQuery);

  if ($checkResult->num_rows > 0) {
      // Если запись уже существует, обновляем количество и дни
      $updateQuery = "UPDATE Cart SET quantity = 1, price = '$equipment_price' WHERE user_id = '$user_id' AND equipment_id = '$equipment_id'";
      if ($conn->query($updateQuery) === TRUE) {
        header("Location: cart.php");
      } else {
          echo "Ошибка при обновлении товара в корзине: " . $conn->error;
      }
  } else {
      // Если записи нет, добавляем новую запись в корзину
      $insertQuery = "INSERT INTO Cart (user_id, equipment_id,price) VALUES ('$user_id', '$equipment_id','$equipment_price')";
      if ($conn->query($insertQuery) === TRUE) {
        header("Location: cart.php");
      } else {
          echo "Ошибка при добавлении товара в корзину: " . $conn->error;
      }
  }
}
// Функция для получения user_id по username
function getUserIdByUsername($conn,$username) {
 

  // Запрос на получение user_id
  $query = "SELECT user_id FROM Users WHERE user_name = '$username'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return $row["user_id"];
  } else {
    return null;
  }
}

// Функция для проверки доступности снаряжения
function checkEquipmentAvailability($conn, $equipmentId) {
  // Запрос на получение доступности снаряжения
  $query = "SELECT availability FROM Equipment WHERE equipment_id = '$equipmentId'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return $row["availability"];
  } else {
    return false;
  }
}
?>

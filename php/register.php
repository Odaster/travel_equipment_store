<?php
session_start();

// Проверка, авторизован ли пользователь
 if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}?>

<?php
// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "12345";
$dbname = "rental_app";
$port = 3307;


$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Дополнительная обработка и проверка данных (например, валидация)

    // Здесь должен быть код для сохранения данных в базу данных
    $sql = "INSERT INTO users (user_name, user_password,registration_date) VALUES ('$username', '$password',now())";

    if ($conn->query($sql) === TRUE) {
      session_start();
      $_SESSION["username"] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Ошибка регистрации: " . $conn->error;
    }
}
$conn->close();
?>
<html>
<head>
  <title>Аренда туристического снаряжения - Регистрация</title>
  <style>
    /* CSS стили */
  </style>
</head>
<body>
  <!-- HTML разметка страницы -->
</body>
</html>

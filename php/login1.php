<?php
session_start();

// Проверка, авторизован ли пользователь
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

?>

<?php
$servername = "localhost";
$username = "root";
$password = "12345";
$dbname = "rental_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Проверка, была ли отправлена форма авторизации
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM Users WHERE user_name = '$username' AND user_password = '$password'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Авторизация успешна
        session_start();
        $_SESSION["username"] = $username;

        // Проверка, является ли пользователь администратором
        if ($username === "admin") {
            header("Location: admin.php");
            exit();
        } else {
            header("Location: dashboard.php");
            exit();
        }
    } else {
        $error_message = "Неверное имя пользователя или пароль.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Аренда туристического снаряжения - Авторизация</title>
  <meta charset="utf-8" />
  <style>
   body {
      background-color: #ffffff;
      color: #000000;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #008000;
      color: #ffffff;
      padding: 20px;
      text-align: center;
    }

    h1 {
      margin: 0;
    }

    .navigation {
      text-align: center;
      margin-top: 20px;
      font-size: 1.2rem;
      text-transform: uppercase;
      font-weight: 700;
    }

    .navigation a {
  margin: 0 10px;
  text-decoration: none;
  color: #ffffff;
  border: 1px solid #ffffff;
  padding: 5px 10px;
  border-radius: 4px;
  transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

.navigation a:hover {
  background-color: #7FFFD4;
  color: #808080;
  border-color: #008000;
}

    .container {
      max-width: 960px;
      margin: 0 auto;
      padding: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 20px;
    }

    input[type="text"],
    input[type="password"],
    input[type="submit"] {
      width: 300px;
      padding: 10px;
      margin-bottom: 10px;
    }

    input[type="submit"] {
      background-color: #00c853;
      color: #ffffff;
      cursor: pointer;
    }
    .center-text {
      text-align: center;
    }
    .error-message {
      color: red;
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <header>
    <h1>Аренда туристического снаряжения</h1>
    <div class="navigation">
      <a href="index.php">Главная</a>
      <a href="contact.php">Контакты</a>
      </div>
  </header>

  <div class="container">
    <h2 class="center-text">Авторизация</h2>
    <form action="login1.php" method="POST">
      <input type="text" name="username" placeholder="Имя пользователя" required>
      <input type="password" name="password" placeholder="Пароль" required>
      <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
      <?php endif; ?>
      <input type="submit" value="Войти">
    </form>

    <p class="center-text">Нет аккаунта? <a href="register2.php">Зарегистрируйтесь!</a></p>
  </div>
</body>
</html>

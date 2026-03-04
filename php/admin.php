<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username']) && $_SESSION['username'] != "admin") {
  header("Location: index.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Аренда туристического снаряжения - Панель администратора</title>
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

    .center-text {
      text-align: center;
    }
  </style>
</head>
<body>
  <header>
    <h1>Аренда туристического снаряжения</h1>
    <div class="navigation">
      <a href="index.php">Главная</a>
      <a href="contact.php">Контакты</a>
      <a href="users.php">Пользователи</a>
      <a href="equipment.php">Снаряжение</a>
      <a href="booking.php">Заказы</a>
    </div>
  </header>

  <div class="container">
    <h2 class="center-text">Панель администратора</h2>
    <!-- Добавьте здесь содержимое панели администратора -->
  </div>
</body>
</html>

<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Аренда туристического снаряжения - Контакты</title>
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

    .login-register {
      text-align: right;
      margin-top: -10px;
    }

    .login-register a {
      margin-left: 10px;
      text-decoration: none;
      color: #ffffff;
    }

    /* Media Query for Mobile Devices */
    @media (max-width: 768px) {
      h1 {
        text-align: center;
      }

      .navigation {
        display: block;
      }

      .login-register {
        text-align: center;
        margin-top: 10px;
      }

      .login-register a {
        display: block;
        margin-top: 10px;
      }

      .navigation-mobile {
        margin-top: 20px;
      }

      .navigation-mobile a {
        display: block;
        margin-bottom: 10px;
        text-decoration: none;
        color: #ffffff;
      }
    }

    .container {
      max-width: 960px;
      margin: 0 auto;
      padding: 20px;
      text-align: center;
    }

    .map-container {
      width: 100%;
      height: 400px;
      margin-bottom: 20px;
    }

    .contact-info {
      font-size: 18px;
    }

    .contact-info span {
      font-weight: bold;
    }

    .contact-info a {
      text-decoration: none;
      color: #000000;
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
    <div class="login-register">
      <?php
        if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
          echo 'Добро пожаловать, ' . $_SESSION['username'] . '!';
          echo ' <a href="dashboard.php">Личный кабинет</a>';
          if ($_SESSION['username'] === "admin"){
            echo '<a href="admin.php"> Админ</a>';
          }
        } else {
          echo '<a href="login1.php">Войти</a>';
          echo '<a href="register2.php">Регистрация</a>';
        }
      ?>
    </div>
  </header>

  <div class="container">
    <h2>Контакты</h2>
    <div class="map-container">
      <!-- Вставьте код для яндекс карты с указанным адресом -->
      <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3Ae214ec6d49016e3248f931c321e914cd7419d787a9487312ab33e2a08cf5bff6&amp;source=constructor" width="100%" height="400" frameborder="0" allowfullscreen="true"></iframe>
    </div>
    <div class="contact-info">
      <p><span>Адрес:</span> Минск, Уручская улица, 21В</p>
      <p><span>Телефоны:</span> <a href="tel:+375299635055">+375299635055</a></p>
      <p><span>Почта:</span> <a href="mailto:odaster.ey@gmail.com">odaster.ey@gmail.com</a></p>
    </div>
  </div>
</body>
</html>

<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Аренда туристического снаряжения - Главная</title>
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

    .equipment-item {
      border: 1px solid #cccccc;
      padding: 10px;
      margin-bottom: 20px;
      display: flex;
    }

    .equipment-image {
      width: 150px;
      height: 150px;
      overflow: hidden;
      border-radius: 8px;
    }

    .equipment-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .equipment-details {
      flex-grow: 1;
      margin-left: 20px;
    }

    .equipment-title {
      font-size: 20px;
      font-weight: bold;
    }

    .equipment-description {
      margin-bottom: 10px;
      text-align: justify;
    }

    .equipment-price {
      margin-bottom: 10px;
    }

    .equipment-availability {
      margin-bottom: 10px;
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
    .order-button {
  background-color: #BC8F8F;
  color: #ffffff;
  padding: 5px 10px;
  border: none;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.order-button.disabled {
  background-color: #cccccc !important;
  color: #777777 !important;
  cursor: not-allowed;
}

.order-button:hover {
  background-color: #4caf50;
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
    .cart-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      font-size: 24px;
      color: #000000;
      text-decoration: none;
      background-color: #FFFFFF;
      border: none;
      border-radius: 50%;
      padding: 10px;
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .cart-button:hover {
      background-color: #7FFFD4;
      color: #000000;
    }
    .add-to-cart-button {
      background-color: #0080FF;
      color: #FFFFFF;
      padding: 5px 10px;
      border: none;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s ease, color 0.3s ease;
      
    }
    .add-to-cart-button.disabled {
  background-color: #cccccc !important;
  color: #777777 !important;
  cursor: not-allowed;
}
    .add-to-cart-button:hover {
      background-color: #0040FF;
    }

    .search-form {
      text-align: center;
      margin-bottom: 20px;
    }

    .search-input {
      padding: 5px;
      width: 400px;
      height: 30px;
      border: 1px solid #cccccc;
      border-radius: 4px;
      font-size: 14px;
    }

    .search-submit {
      background: none;
      border: none;
      cursor: pointer;
      padding: 0;
      margin-left: 5px;
    }

    .search-submit img {
      width: 32px;
      height: 32px;
      vertical-align: middle;
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
           echo '<a href="admin.php"> Админ</a>';}
        } else {
         
          echo '<a href="login1.php">Войти</a>';
          echo '<a href="register2.php">Регистрация</a>';
        }
      ?>
    </div>
  </header>


  <div class="container">
    <h2>Случайные предложения</h2>
    <form method="GET" action="index.php" class="search-form">
      <input type="text" name="search" placeholder="Поиск " class="search-input">
      <button type="submit" class="search-submit"><img src="search.png" alt="Поиск"></button>
    </form>
    </form>
    <?php
      // Подключение к базе данных
      $servername = "localhost";
      $username = "root";
      $password = "12345";
      $dbname = "rental_app";

      $conn = new mysqli($servername, $username, $password, $dbname);

      // Проверка соединения
      if ($conn->connect_error) {
          die("Ошибка подключения: " . $conn->connect_error);
      }

      // Запрос на получение данных из таблицы Equipment
      $sql = "SELECT * FROM Equipment";
      
      // Поиск по названию снаряжения, если указан поисковый запрос
      if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = $_GET['search'];
        $sql .= " WHERE equipment_name LIKE '%$searchTerm%'";
      }

      

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // Вывод данных
        while ($row = $result->fetch_assoc()) {
          echo '<div class="equipment-item">';
          echo '<div class="equipment-image"><img src="' . $row["photo"] . '" alt="Фото снаряжения" ></div>';
          echo '<div class="equipment-details">';
          echo '<div class="equipment-title">' . $row["equipment_name"] . '</div>';
          echo '<div class="equipment-description">' . $row["equipment_description"] . '</div>';
          echo '<div class="equipment-price">Цена: ' . $row["price"] . ' руб. за 1 день аренды </div>';
          echo '<div class="equipment-availability">Доступность: ' . ($row["availability"] ? 'В наличии' : 'Нет в наличии') . '</div>';
          echo '<form action="process_order.php" method="POST">';
          echo '<input type="hidden" name="equipment_id" value="' . $row["equipment_id"] . '">';
          echo '<input type="hidden" name="price" value="' . $row["price"] . '">';
          echo '<input type="submit" name="add_to_cart" value="Добавить в корзину" class="add-to-cart-button '. ($row["availability"] ? '' : 'disabled') . '" ' . ($row["availability"] ? '' : 'disabled') . '>';
          echo '</form>';
          echo '</div>';
          echo '</div>';
        }
      } else {
        echo "Ничего не найдено";
      }

      // Закрытие соединения с базой данных
      $conn->close();
    ?>

    
  </div>
  <a href="cart.php" class="cart-button">&#128722;</a>

</body>
</html>

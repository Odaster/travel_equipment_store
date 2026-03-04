<?php
session_start();

// Проверка, является ли пользователь администратором
if (!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['username'] != "admin") {
  header("Location: index.php");
  exit();
}

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "12345";
$dbname = "rental_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

$sql = "SELECT * FROM Users";
// Получение данных из таблицы "Users" с учетом поиска
$search_query = "";
if (isset($_GET['search'])) {
  $search_query = $_GET['search'];
  $search_query = $conn->real_escape_string($search_query);
  $search_query = trim($search_query);
  $search_query = "%" . $search_query . "%";
  $search_query = strtoupper($search_query);
  $sql = "SELECT * FROM Users WHERE UPPER(user_name) LIKE '$search_query'";
} 
if (isset($_GET['reset-button'])) {
  $sql = "SELECT * FROM Users";
  $search_query = "";
} 

$result = $conn->query($sql);

// Удаление пользователя
if (isset($_POST['delete-user'])) {
  $user_id = $_POST['delete-user'];
  $delete_sql = "DELETE FROM Users WHERE user_id = '$user_id'";
  $delete_result = $conn->query($delete_sql);
  if ($delete_result) {
    // Успешно удалено
    header("Location: users.php");
    exit();
  } else {
    // Ошибка при удалении
    echo "Ошибка при удалении пользователя: " . $conn->error;
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Аренда туристического снаряжения - Пользователи</title>
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

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #000000;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #008000;
      color: #ffffff;
    }

    tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .search-form {
      margin-top: 20px;
      text-align: center;
    }

    .search-input {
      padding: 8px;
      width: 200px;
    }

    .search-button {
      padding: 8px 12px;
    }

    .reset-button {
      padding: 8px 12px;
      margin-left: 10px;
    }

    .delete-button {
      padding: 8px 12px;
      background-color: #ff0000;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .delete-button:hover {
      background-color: #ff3333;
    }
  </style>
</head>
<body>
  <header>
    <h1>Аренда туристического снаряжения</h1>
    <div class="navigation">
      <a href="index.php">Главная</a>
      <a href="contact.php">Контакты</a>
      <a href="equipment.php">Снаряжение</a>
      <a href="admin.php">Панель администратора</a>
    </div>
  </header>

  <div class="container">
    <h2 class="center-text">Пользователи</h2>
    <div class="search-form">
      <form method="GET" action="users.php">
        <input type="text" name="search" class="search-input" placeholder="Поиск по имени пользователя">
        <input type="submit" class="search-button" value="Поиск">
        <a href="users.php" class="reset-button">Сбросить</a>
      </form>
    </div>
    <table>
      <tr>
        <th>ID</th>
        <th>Имя</th>
        <th>Фамилия</th>
        <th>Отчество</th>
        <th>Телефон</th>
        <th>Email</th>
        <th>Адрес</th>
        <th>Дата регистрации</th>
        <th>Действия</th>
      </tr>
      <?php
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>" . $row["user_name"] . "</td>";
            echo "<td>" . $row["user_lastname"] . "</td>";
            echo "<td>" . $row["user_patronymic"] . "</td>";
            echo "<td>" . $row["user_phone"] . "</td>";
            echo "<td>" . $row["user_email"] . "</td>";
            echo "<td>" . $row["user_adres"] . "</td>";
            echo "<td>" . $row["registration_date"] . "</td>";
            echo "<td>";
            echo "<form method='POST' onsubmit='return confirm(\"Вы уверены, что хотите удалить этого пользователя?\")'>";
            echo "<button type='submit' name='delete-user' value='" . $row["user_id"] . "' class='delete-button'>Удалить</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='9'>Нет пользователей</td></tr>";
        }
      ?>
    </table>
  </div>
</body>
</html>

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

// Обработка редактирования снаряжения
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['edit'])) {
    $equipmentId = $_POST['equipment_id'];
    $equipmentName = $_POST['equipment_name'];
    $category = $_POST['category'];
    $description = $_POST['equipment_description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $photo =  $_POST['photo'] ;
    $availability = isset($_POST['availability']) ? 1 : 0;

    $updateSql = "UPDATE Equipment SET equipment_name = '$equipmentName', category = '$category', equipment_description = '$description', price = '$price', quantity = '$quantity', photo = '$photo', availability = '$availability' WHERE equipment_id = $equipmentId";

    if ($conn->query($updateSql) === TRUE) {
      // Успешное редактирование снаряжения
      echo "<script>alert('Снаряжение успешно отредактировано');</script>";
      echo "<script>window.location.href = 'equipment.php';</script>";
      exit();
    } else {
      echo "Ошибка при редактировании снаряжения: " . $conn->error;
    }
  }
}

$sql = "SELECT * FROM Equipment";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Аренда туристического снаряжения - Оборудование</title>
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
      table-layout: fixed;
    }

    th, td {
      border: 1px solid #000000;
      padding: 8px;
      text-align: left;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
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

    .action-button {
      padding: 8px 12px;
      background-color: #dc3545;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      text-decoration: none;
    }

    .action-button:hover {
      background-color: #c82333;
    }
    .equipment-image {
      width: 50px;
      height: 50px;
      overflow: hidden;
      border-radius: 8px;
    }

    .equipment-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  </style>
    <script>
    function openFileExplorer(inputId) {
      
      document.getElementById(inputId).click();
    }
    function previewImage(input, imgId) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      document.getElementById(imgId).src = e.target.result;
      document.getElementById("gg").value = e.target.result;
    };

    reader.readAsDataURL(input.files[0]);
  }
}
  </script>
</head>
<body>
  <header>
    <h1>Аренда туристического снаряжения</h1>
    <div class="navigation">
      <a href="index.php">Главная</a>
      <a href="booking.php">Заказы</a>
      <a href="admin.php">Панель администратора</a>
    </div>
  </header>

  <div class="container">
    <h2 class="center-text">Оборудование</h2>
    <div class="search-form">
      <form method="GET" action="equipment.php">
        <input type="text" name="search" class="search-input" placeholder="Поиск по наименованию" >
        <input type="submit" class="search-button" value="Поиск">
        <input type="submit" name="reset-button" class="reset-button" value="Сбросить">
      </form>
    </div>
    <div class="table-container">
      <table>
        <tr>
          <th>ID</th>
          <th>Наименование</th>
          <th>Категория</th>
          <th>Описание</th>
          <th>Цена</th>
          <th>Количество</th>
          <th>Фото</th>
          <th>Наличие</th>
          <th>Управление</th>
          <th>Управление</th>
        </tr>
        <?php
          // Подключение к базе данных
          $servername = "localhost";
          $username = "root";
          $password = "12345";
          $dbname = "rental_app";

          $conn = new mysqli($servername, $username, $password, $dbname);

          if ($conn->connect_error) {
              die("Ошибка подключения к базе данных: " . $conn->connect_error);
          }

          // Получение данных из таблицы "Equipment" с учетом поиска
          $search_query = "";
          $sql = "SELECT * FROM Equipment";
          if (isset($_GET['search'])) {
            $search_query = $_GET['search'];
            $search_query = $conn->real_escape_string($search_query);
            $search_query = trim($search_query);
            $search_query = "%" . $search_query . "%";
            $search_query = strtoupper($search_query);
            $sql = "SELECT * FROM Equipment WHERE UPPER(equipment_name) LIKE '$search_query'";
          }
          if (isset($_GET['reset-button'])) {
            $sql = "SELECT * FROM Equipment";
            $search_query = "";
          }

          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              $ph = "photoInput_";
              $ph.=$row['equipment_id'];
              echo "<tr>";
              echo "<td class='equipment-image'>
                    <img id='photo_" . $row['equipment_id'] . "' onclick='openFileExplorer( \"photoInput_" . $row['equipment_id'] . "\");' src='" . $row['photo'] . "' alt='Фото снаряжения'>
                    <input type='file' style='display: none;' id='$ph' onchange='previewImage(this, \"photo_" . $row['equipment_id'] . "\");'>
                  </td>";
              echo "<form method='POST' action='equipment.php'>";
              echo "<td>" . $row["equipment_id"] . "</td>";
              echo "<td><input type='text' name='equipment_name' value='" . $row["equipment_name"] . "'></td>";
              echo "<td><input type='text' name='category' value='" . $row["category"] . "'></td>";
              echo "<td><input type='text' name='equipment_description' value='" . $row["equipment_description"] . "'></td>";
              echo "<td><input type='text' name='price' value='" . $row["price"] . "'></td>";
              echo "<td><input type='number' name='quantity' value='" . $row["quantity"] . "'>
              input type='hidden'  id='gg' value='" . $row["photo"] . "' >
              </td>"; 
              
              echo "<td><input type='checkbox' name='availability'" . ($row["availability"] ? 'checked' : '' ) ." '></td>";
              echo "<td>
                    <input type='hidden' name='equipment_id' value='" . $row["equipment_id"] . "'>
                    <input type='submit' name='edit' class='action-button' value='Сохранить'>
                    </td>";
                    echo "<td>
                    <input type='hidden' name='equipment_id' value='" . $row["equipment_id"] . "'>
                    <input type='submit' name='delete' class='action-button' value='Удалить' onclick='return confirm(\"Вы уверены, что хотите удалить это снаряжение?\")'>
                    </td>";
              echo "</form>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='9'>Нет оборудования</td></tr>";
          }

          // Обработка удаления оборудования
          if (isset($_POST['delete'])) {
            $delete_id = $_POST['equipment_id'];
            $delete_sql = "DELETE FROM Equipment WHERE equipment_id = '$delete_id'";
            if ($conn->query($delete_sql) === TRUE) {
              echo "<script>alert('Оборудование успешно удалено');</script>";
              echo "<script>window.location.href = 'equipment.php';</script>";
            } else {
              echo "Ошибка удаления оборудования: " . $conn->error;
            }
          }

          $conn->close();
        ?>
      </table>
    </div>
  </div>

</body>
</html>

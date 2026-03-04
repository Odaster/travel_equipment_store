<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION["username"])) {
    header("Location: login1.php");
    exit();
}

// Обработка кнопки "Выйти"
if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login1.php");
    exit();
}

// Подключение к базе данных
require_once "db.php";
$conn = getConnection();

// Получение user_id по $_SESSION["username"]
$user_id = null;

$username = $_SESSION["username"];
$sql = "SELECT user_id FROM Users WHERE user_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);

if ($stmt->fetch()) {
    $stmt->close();
}

// Поиск заказов по user_id
$sql = "SELECT Equipment.equipment_name, Bookings.start_date, Bookings.end_date
        FROM Bookings
        INNER JOIN Equipment ON Bookings.equipment_id = Equipment.equipment_id
        WHERE Bookings.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result1 = $stmt->get_result();
$stmt->close();

// Получение информации о пользователе из базы данных
$last_name = "";
$first_name = "";
$middle_name = "";
$phone = "";
$email = "";
$adres = "";

$sql = "SELECT user_lastname, user_persname, user_patronymic, user_phone, user_email, user_adres FROM Users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($last_name, $first_name, $middle_name, $phone, $email, $adres);

if ($stmt->fetch()) {
    $stmt->close();
}
// Обработка сохранения данных
$message = "";

if (isset($_POST["save"])) {
    $last_name = $_POST["last_name"];
    $first_name = $_POST["first_name"];
    $middle_name = $_POST["middle_name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $adres = $_POST["adres"];

    // Проверка на уникальность телефона
    $sql = "SELECT user_id FROM Users WHERE (user_phone = ? OR user_email = ?) AND user_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $phone, $email, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Телефон и(или) почта уже заняты";
    } else {
        // Сохранение данных пользователя
        $sql = "UPDATE Users SET user_lastname = ?, user_persname = ?, user_patronymic = ?, user_phone = ?, user_email = ?, user_adres = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $last_name, $first_name, $middle_name, $phone, $email, $adres, $user_id);

        if ($stmt->execute()) {
            $message = "Данные сохранены успешно";
            
        } else {
            $message = "Ошибка при сохранении данных: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Аренда туристического снаряжения - Личный кабинет</title>
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
    input[type="email"],
    input[type="password"],
    input[type="submit"] {
      width: 300px;
      padding: 10px;
      margin-bottom: 10px;
    }

    .dashboard {
      text-align: center;
      margin-top: 20px;
    }

    .dashboard h2 {
      margin-bottom: 20px;
    }

    .logout-button {
      background-color: #e53935;
      color: #ffffff;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      margin-top: 20px;
    }

    .dashboard-panel {
      background-color: #f5f5f5;
      padding: 20px;
      position: fixed;
      top: 0;
      right: -400px;
      height: 100%;
      width: 400px;
      transition: right 0.3s ease;
      border-left: 1px solid #cccccc;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .dashboard-panel.active {
      right: 0;
    }

    .dashboard-toggle {
      position: absolute;
      top: 20px;
      left: -30px;
      font-size: 20px;
      color: #ffffff;
      text-decoration: none;
      background-color: #000000;
      padding-right: 6px;
      padding-left: 6px;
      border-radius: 50%;
      cursor: pointer;
      transition: right 0.3s ease;
    }

    .dashboard-toggle.active {
      left: 400px;
    }

    .dashboard-toggle:hover {
      color: #e53935;
    }

    .dashboard-table {
      margin-top: 20px;
      width: 100%;
      border-collapse: collapse;
    }

    .dashboard-table td,
    .dashboard-table th {
      padding: 10px;
      border: 1px solid #cccccc;
      text-align: left;
    }

    .dashboard-table th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    .dashboard-empty {
      text-align: center;
      margin-top: 40px;
      font-style: italic;
    }

    .save-button {
      background-color: #007bff;
      color: #ffffff;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
    }
    .message {
      margin-top: 10px;
      color: #e53935;
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
    <div class="dashboard">
      <h2>Личный кабинет</h2>
      <form action="dashboard.php" method="POST">
          <input type="text" name="last_name" placeholder="Фамилия" value="<?php echo $last_name; ?>"  pattern="^[A-ZА-ЯЁ][a-zа-яё]{1,28}$" required>
          <input type="text" name="first_name" placeholder="Имя" value="<?php echo $first_name; ?>" pattern="^[A-ZА-ЯЁ][a-zа-яё]{1,28}$" required>
          <input type="text" name="middle_name" placeholder="Отчество" value="<?php echo $middle_name; ?>" pattern="^[A-ZА-ЯЁ][a-zа-яё]{1,28}$" required>
          <input type="text" name="phone" placeholder="Телефон" value="<?php echo $phone; ?>" pattern="^\s*\+{1,1}375((33\d{7})|(29\d{7})|(44\d{7}|)|(25\d{7}))\s*$" required>
          <input type="email" name="email" placeholder="E-mail" value="<?php echo $email; ?>" required>
          <input type="text" name="adres" placeholder="Адрес регистрации:" value="<?php echo $adres; ?>" required>
        <input type="submit" name="save" value="Сохранить данные" class="save-button" >
        
        <div class="message"><?php echo $message; ?></div>
      </form>
      <form action="dashboard.php" method="POST">
      <input type="submit" name="logout" value="Выйти" class="logout-button">
      </form>
    </div>
</div>

<div class="dashboard-panel" id="dashboardPanel">
    <a href="#" class="dashboard-toggle">&times;</a>
    <h2>Данные заказов</h2>
    <?php
    if ($result1->num_rows > 0) {
        echo '<table class="dashboard-table">
                <thead>
                  <tr>
                    <th>Название снаряжения</th>
                    <th>Дата начала аренды</th>
                    <th>Дата окончания аренды</th>
                  </tr>
                </thead>
                <tbody>';

        while ($row = $result1->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row["equipment_name"] . '</td>
                    <td>' . $row["start_date"] . '</td>
                    <td>' . $row["end_date"] . '</td>
                  </tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<div class="dashboard-empty">Нет данных о заказах</div>';
    }
    ?>
</div>

<script>
    // Открытие/закрытие панели заказов
    const dashboardPanel = document.getElementById("dashboardPanel");
    const dashboardToggle = document.querySelector(".dashboard-toggle");

    dashboardToggle.addEventListener("click", () => {
        dashboardPanel.classList.toggle("active");
        dashboardToggle.classList.toggle("active");
    });
</script>
</body>
</html>

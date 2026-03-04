<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION["username"])) {
  header("Location: login1.php");
  exit();
}

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "12345";
$dbname = "rental_app";
$message = "";
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
  die("Ошибка подключения: " . $conn->connect_error);
}

// Получение user_id по username
function getUserIdByUsername($conn, $username) {
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

// Получение user_id текущего пользователя
$username = $_SESSION["username"];
$user_id = getUserIdByUsername($conn, $username);

if (!empty($user_id)) {
  if (isset($_POST["delete_cart"])) {
    $cartId = $_POST["cart_id"];

    // Удаление записи из таблицы Cart
    $deleteQuery = "DELETE FROM Cart WHERE cart_id = '$cartId' AND user_id = '$user_id'";
    $conn->query($deleteQuery);

    // Перенаправление на страницу корзины после удаления
    header("Location: cart.php");
    exit();
  }

  if (isset($_POST["buy"])) {
    if (!empty($_POST["equipment_id"]) && !empty($_POST["quantity"]) && !empty($_POST["start_date"]) && !empty($_POST["end_date"]) && !empty($_POST["price"])) {
      $equipmentIds = $_POST["equipment_id"];
      $quantities = $_POST["quantity"];
      $startDates = $_POST["start_date"];
      $endDates = $_POST["end_date"];
      $prices = $_POST["price"];
  
      // Проверка, что количество товара и даты бронирования не пустые
      if (!empty($equipmentIds) && !empty($quantities) && !empty($startDates) && !empty($endDates)) {
        foreach ($equipmentIds as $key => $equipmentId) {
          $quantity = $quantities[$key];
          $startDate = $startDates[$key];
          $endDate = $endDates[$key];
          $bookingDate = date("Y-m-d H:i:s");
  
          $insertQuery = "INSERT INTO Bookings (user_id, equipment_id, quantity,start_date, end_date, booking_date)
          VALUES ('$user_id', '$equipmentId','$quantity', '$startDate', '$endDate', '$bookingDate')";
          if ($conn->query($insertQuery) === TRUE) {
            // Обновление записи в таблице Equipment
            // $updateQuery = "UPDATE Equipment SET quantity = quantity - '$quantity'
            //                 WHERE equipment_id = '$equipmentId'";
            // $conn->query($updateQuery);
          } else {
            echo "Ошибка при оформлении заказа: " . $conn->error;
          }
        }
  
        // Удаление записей из таблицы Cart
        $deleteQuery = "DELETE FROM Cart WHERE user_id = '$user_id'";
        $conn->query($deleteQuery);
  
        // Перенаправление на страницу dashboard после оформления заказа
        header("Location: dashboard.php");
        exit();
      } else {
        $message = "Ошибка заказа: некоторые поля не заполнены";
      }
    } else {
      $message = "Добавьте что-нибудь в корзину!";
    }
  }
  
}

// Получение добавленных товаров из таблицы Cart для текущего пользователя
$cartQuery = "SELECT Cart.cart_id, Equipment.equipment_id, Equipment.equipment_name, Cart.price, Equipment.quantity, Cart.start_date, Cart.end_date
                FROM Cart
                INNER JOIN Equipment ON Cart.equipment_id = Equipment.equipment_id
                WHERE Cart.user_id = '$user_id'";
$cartResult = $conn->query($cartQuery);

// Закрытие соединения с базой данных
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Аренда туристического снаряжения - Корзина</title>
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

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table td, table th {
      border: 1px solid black;
      padding: 8px;
    }

    table th {
      text-align: left;
    }

    input[type="number"] {
      width: 50px;
    }

    .message {
      margin-top: 10px;
      color: #e53935;
    }

    .total-price {
      margin-top: 20px;
      font-size: 1.2rem;
      font-weight: bold;
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
    <h2 class="center-text">Корзина</h2>
  </div>

  <form method="POST" action="cart.php">
    <table>
      <thead>
        <tr>
          <th>Название</th>
          <th>Цена</th>
          <th>Количество</th>
          <th>Дата начала</th>
          <th>Дата окончания</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <?php
          while ($row = $cartResult->fetch_assoc()) {
            $cartId = $row["cart_id"];
            $equipmentId = $row["equipment_id"];
            $equipmentName = $row["equipment_name"];
            $price = $row["price"];
            $quantity = $row["quantity"];
            $startDate = $row["start_date"];
            $endDate = $row["end_date"];

            echo "<tr>";
            echo "<td>$equipmentName</td>";
            echo "<td><input name='price[]' id='price_$equipmentId' readonly value = '$price'></td>";
            if($quantity > 0 && $quantity >= 5 ){
              echo "<td><input type='number' name='quantity[]' value='1' min='1' max='5' required onchange='updatePrice($equipmentId, $price,this)'></td>";
            }
            elseif ($quantity > 0 && $quantity < 5) {
              echo "<td><input type='number' name='quantity[]' value='1' min='1' max='$quantity' required onchange='updatePrice($equipmentId, $price,this)'></td>";
            }
            echo "<td><input type='date' name='start_date[]' value='$startDate' min='" . date('Y-m-d', strtotime('+1 day')) . "' max='" . date('Y-m-d', strtotime('+1 month')) . "' required></td>";
            echo "<td><input type='date' name='end_date[]' value='$endDate' min='" . date('Y-m-d', strtotime('+2 day')) . "' max='" . date('Y-m-d', strtotime('+1 month 1 day')) . "' required></td>";
            echo "<form method='POST' action='cart.php'>";
            echo "<td>                   
            <input type='hidden' name='cart_id' value='" .$row["cart_id"]."'>
            <input type='submit' name='delete_cart' class='action-button' value='Удалить' ></td>";
            echo "</form>";
            echo "<input type='hidden' name='equipment_id[]' value='$equipmentId'>";
            echo "</tr>";
          }
        ?>
      </tbody>
    </table>

    <br>

    <input type="submit" name="buy" value="Заказать">
    <div class="message"><?php echo $message; ?></div>
  </form>

  <div class="total-price">Общая стоимость: <span id="total_price">0.00</span></div>

  <script>
    function updatePrice(equipmentId, initialPrice, input) {
      const quantity = parseInt(input.value);
      if(quantity != null && quantity>= 1 && quantity<=5) {
        const priceElement = document.getElementById(`price_${equipmentId}`);
        const equipmentTotalPrice = initialPrice * quantity;

        animateNumber(priceElement, initialPrice, equipmentTotalPrice);

        // Обновление общей стоимости
        updateTotalPrice();
      } 
    }

    function animateNumber(element, from, to) {
      let current = from;
      const increment = Math.sign(to - from);
      const duration = 100; // Продолжительность анимации в миллисекундах
      const stepTime = Math.abs(Math.floor(duration / (to - from)));

      const timer = setInterval(() => {
        current += increment;
        element.value = current;

        if (current === to) {
          clearInterval(timer);
        }
      }, stepTime);
    }

    function updateTotalPrice() {
      const priceElements = document.querySelectorAll(`table input[name='price[]']`);
      const quantityInputs = document.querySelectorAll(`table input[name='quantity[]']`);
      let totalPrice = 0;

      quantityInputs.forEach((input, index) => {
        const equipmentId = input.closest('tr').querySelector(`input[name='equipment_id[]']`).value;
        const priceElement = priceElements[index];
        const initialPrice = parseFloat(priceElement.value);
        const quantity = parseInt(input.value);
        const equipmentTotalPrice = initialPrice * quantity;
        totalPrice += equipmentTotalPrice;

        animateNumber(priceElement, initialPrice, equipmentTotalPrice);
      });

      document.getElementById('total_price').innerText = `${totalPrice.toFixed(2)} руб.`;
    }
    const startDateInputs = document.querySelectorAll(`table input[name='start_date[]']`);
  startDateInputs.forEach((input) => {
    input.addEventListener('change', () => {
      const startDate = new Date(input.value);
      const endDateInputs = input.closest('tr').querySelectorAll(`input[name='end_date[]']`);

      endDateInputs.forEach((endDateInput) => {
        const minEndDate = new Date(startDate.getTime() + (1 * 24 * 60 * 60 * 1000));
        endDateInput.min = formatDate(minEndDate);
        endDateInput.max = formatDate(new Date(startDate.getTime() + (14 * 24 * 60 * 60 * 1000)));
      });
    });
  });

  // Функция форматирования даты в формате "YYYY-MM-DD"
  function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }
  </script>
</body>
</html>

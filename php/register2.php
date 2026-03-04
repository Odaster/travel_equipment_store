<?php
session_start();

// Проверка, авторизован ли пользователь
 if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Аренда туристического снаряжения - Регистрация</title>
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

    .error-message {
      color: red;
      margin-top: 3px;
      margin-bottom:3px;
    }

    .success-icon {
      color: #00c853;
      margin-left: 10px;
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
    </div>
  </header>

  <div class="container">
    <h2 class="center-text">Регистрация</h2>
    <form action="register.php" method="POST" onsubmit="return validateForm()">
      <input type="text" name="username" id="username" placeholder="Имя пользователя" required oninput="checkUsernameAvailability()">
      <span  id="availability_message"></span>
      <span id = "username_error" class="error-message"></span>
      <input type="password" name="password" id="password" placeholder="Пароль" required oninput="checkPassword()">
      <input type="password" name="confirm_password" id="confirm_password" placeholder="Подтвердите пароль" required disabled>
      <span id="password_error" class="error-message"></span>
      <input type="submit" value="Зарегистрироваться">
    </form>

    <p class="center-text">Уже есть аккаунт? <a href="login1.php">Войдите!</a></p>
  </div>

  <script>
    var g = false;
    function validateForm() {
      var u = false;
      var p = false;
      var username = document.getElementById("username").value;
      var password = document.getElementById("password").value;
      var confirm_password = document.getElementById("confirm_password").value;
      var conf_pass = document.getElementById("confirm_password");
      var username_error = document.getElementById("username_error");
      var password_error = document.getElementById("password_error");
      
      // Проверка имени пользователя
      var usernamePattern = /^[a-z0-9_]{4,12}$/;
      if (!usernamePattern.test(username)) {
        username_error.textContent = "Только буквы нижнего регистра,цифры и '_', длина от 4 до 12";
        u = false;
      }
      else{ u = true; 
        username_error.textContent = "";}
      if(conf_pass.disabled == true){ 
        password_error.textContent = "Поле пароль: латинские буквы,цифры и '_', длина от 8 до 16";
        return false;
      }
      else{
        if (password !== confirm_password ) {
          password_error.textContent = "Пароли не совпадают";
          p = false;
        }
      
        else{ if(conf_pass.disabled == false){p = true; 
          password_error.textContent = "";}}
        if(u == true && p == true&& g==true){
          username_error.textContent = "";
          password_error.textContent = "";
          return true;
                   
        }
        else{ return false;
            }
          }
    }
    function checkUsernameAvailability() {
      var username = document.getElementById("username").value;
      var availabilityMessage = document.getElementById("availability_message");

      // Проверка доступности имени пользователя
      var usernamePattern = /^[a-z0-9_]{4,12}$/;
      if (usernamePattern.test(username)) {
        // Отправляем запрос на сервер для проверки доступности имени пользователя
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
          if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
              var response = JSON.parse(xhr.responseText);
              if (response.available) {
                availabilityMessage.textContent = "";
                availabilityMessage.innerHTML = "<span class='success-icon'>&#10004;</span> Имя доступно";
                availabilityMessage.style.color = "#00c853";
                g = true;
              } else {
                availabilityMessage.innerHTML = "Имя занято";
                availabilityMessage.style.color = "red";
                g = false;
              }
            }
          }
        };
        xhr.open("POST", "check_username.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("username=" + username);
      } else {
        availabilityMessage.textContent = "";
      }
    }
    function checkPassword() {
      var password = document.getElementById("password").value;
      var confirm_password = document.getElementById("confirm_password");

      // Проверка пароля
      var passwordPattern = /^[a-zA-Z0-9_]{8,16}$/;
      if (passwordPattern.test(password)) {
        confirm_password.disabled = false;
      } else {
        confirm_password.disabled = true;
        confirm_password.value = "";
      }
    }
  </script>
</body>
</html>

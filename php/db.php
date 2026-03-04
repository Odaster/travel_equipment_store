<?php
function getConnection() {
    $servername = "localhost"; // Имя сервера базы данных
    $username = "root"; // Имя пользователя базы данных
    $password = "12345"; // Пароль пользователя базы данных
    $dbname = "rental_app"; // Имя базы данных

    // Создание подключения
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Проверка соединения
    if ($conn->connect_error) {
        die("Ошибка подключения к базе данных: " . $conn->connect_error);
    }

    return $conn;
}

// Получение объекта соединения
$conn = getConnection();
?>

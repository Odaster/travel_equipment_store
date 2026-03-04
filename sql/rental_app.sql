-- Создание базы данных
drop database if exists rental_app;
CREATE DATABASE if not exists rental_app;

USE rental_app;

-- Создание таблицы "Users"
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(25) UNIQUE NOT NULL,
    user_password VARCHAR(25) NOT NULL,
    user_lastname VARCHAR(30) null,
    user_persname VARCHAR(30) null ,
    user_patronymic VARCHAR(30) null,
    user_phone VARCHAR(30) unique null,
    user_email VARCHAR(30) unique null,
    user_adres TEXT null,
    registration_date DATETIME 
);

-- Создание таблицы "Equipment"
CREATE TABLE Equipment (
    equipment_id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_name VARCHAR(50) UNIQUE	NOT NULL,
    category VARCHAR(20) NOT NULL,
    equipment_description TEXT,
    price int NOT NULL,
	quantity INT NOT NULL,
    photo text,
    availability BOOLEAN
);

-- Создание таблицы "Bookings"
CREATE TABLE Bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    equipment_id INT,
    quantity int,
    start_date DATE,
    end_date DATE,
    booking_date DATETIME,
    availability BOOLEAN default false,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) on update cascade on delete cascade,
    FOREIGN KEY (equipment_id) REFERENCES Equipment(equipment_id) on update cascade on delete cascade
);
CREATE TABLE Cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    equipment_id INT,
    quantity INT default 1,
    start_date DATE null,
    end_date DATE null,
    price int NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (equipment_id) REFERENCES Equipment(equipment_id) ON UPDATE CASCADE ON DELETE CASCADE
    );
DELIMITER //

CREATE TRIGGER update_equipment_after_order
AFTER INSERT ON Bookings
FOR EACH ROW
BEGIN
  -- Обновление количества снаряжения
  UPDATE Equipment
  SET quantity = quantity - (SELECT quantity FROM Bookings WHERE booking_id = NEW.booking_id)
  WHERE equipment_id = NEW.equipment_id;

  -- Изменение поля availability, если количество снаряжения стало равным нулю
  IF (SELECT quantity FROM Equipment WHERE equipment_id = NEW.equipment_id) <= 0 THEN
    UPDATE Equipment
    SET availability = FALSE
    WHERE equipment_id = NEW.equipment_id;
  END IF;
END//

DELIMITER ;


insert into Users(user_id,user_name,user_password,registration_date) values(1,"admin","11111111",now()),
(2,"odaster","11111111",now());
select * from Users;
insert into Equipment values(1,"Лодка Aquamarine","лодка","Стильная и прочная локдка пвх",12,9,"6.jpg",true),
(2,"Палатка roselle70","палатка","Палатка для настоящих фанатов туризма!",15,10,"2.jpg",true),
(3,"Спиннинг shimano catana","спиннинг","Лучший спинниг для микро-джига",12,2,"3.jpg",true),
(4,"Фонарь","фонарь","Яркий светодиодный фонарь на солнечных батареях",12,1,"7.jpeg",true),
(5,"Удочка cartanella","удочка","Увесистая углеводородная удочка",12,5,"5.jpg",true),
(6,"Спальный мешок","спальный мешок","Отличный спальник. Не жаркий",2,2,"1.jpg",true);
select * from Equipment;
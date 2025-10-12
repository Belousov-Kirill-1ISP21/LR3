<?php include '../config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Регистрация</h2>
        
        <?php
        if ($_POST) {
            $login = $_POST['login'];
            $password = md5($_POST['password']);
            $full_name = $_POST['full_name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            
            $check = mysqli_query($conn, "SELECT id FROM users WHERE login = '$login'");
            if (mysqli_num_rows($check) > 0) {
                echo "<p class='error'>Логин уже занят</p>";
            } else {
                $sql = "INSERT INTO users (login, password, full_name, phone, email) 
                        VALUES ('$login', '$password', '$full_name', '$phone', '$email')";
                
                if (mysqli_query($conn, $sql)) {
                    echo "<p class='success'>Регистрация успешна!</p>";
                } else {
                    echo "<p class='error'>Ошибка: " . mysqli_error($conn) . "</p>";
                }
            }
        }
        ?>
        
        <form method="POST">
            <input type="text" name="login" placeholder="Логин" required><br><br>
            <input type="password" name="password" placeholder="Пароль" required><br><br>
            <input type="text" name="full_name" placeholder="ФИО" required><br><br>
            <input type="text" name="phone" placeholder="Телефон" required><br><br>
            <input type="email" name="email" placeholder="Email" required><br><br>
            <button type="submit">Зарегистрироваться</button>
        </form>
        
        <p><a href="login.php">Уже есть аккаунт? Войти</a></p>
        <p><a href="../index.php">На главную</a></p>
    </div>
</body>
</html>
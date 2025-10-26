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
            $role_id = 1;
            
            $sql = "INSERT INTO users (login, password, full_name, phone, email, role_id) 
                    VALUES ('$login', '$password', '$full_name', '$phone', '$email', $role_id)";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p class='success'>Регистрация успешна!</p>";
            } else {
                $error_message = mysqli_error($conn);
                
                if (strpos($error_message, 'login') !== false) {
                    echo "<p class='error'>Логин уже занят</p>";
                } elseif (strpos($error_message, 'email') !== false) {
                    echo "<p class='error'>Email уже занят</p>";
                } elseif (strpos($error_message, 'phone') !== false) {
                    echo "<p class='error'>Телефон уже занят</p>";
                } else {
                    echo "<p class='error'>Ошибка регистрации: " . $error_message . "</p>";
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
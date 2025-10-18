<?php include '../config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Вход</h2>
        
        <?php
        if ($_POST) {
            $login = $_POST['login'];
            $password = md5($_POST['password']);
            
            $result = mysqli_query($conn, 
                "SELECT id, password, status_id FROM users WHERE login = '$login'");
            
            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);
                if ($user['password'] === $password) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['admin'] = ($user['status_id'] == 0); 
                    header("Location: ../index.php");
                    exit;
                } else {
                    echo "<p class='error'>Неверный пароль</p>";
                }
            } else {
                echo "<p class='error'>Пользователь не найден</p>";
            }
        }
        ?>
        
        <form method="POST">
            <input type="text" name="login" placeholder="Логин" required><br><br>
            <input type="password" name="password" placeholder="Пароль" required><br><br>
            <button type="submit">Войти</button>
        </form>
        
        <p><a href="register.php">Нет аккаунта? Зарегистрироваться</a></p>
        <p><a href="../index.php">На главную</a></p>
    </div>
</body>
</html>
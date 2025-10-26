<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корочки.есть</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Портал "Корочки.есть"</h1>
        
        <?php 
        if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin'])): 
        ?>
            <p><a href="pages/login.php">Войти</a></p>
            <p><a href="pages/register.php">Зарегистрироваться</a></p>
            <p><a href="pages/courses.php">Все курсы</a></p>
        <?php else: ?>
            <?php if (isset($_SESSION['admin']) && $_SESSION['admin']): ?>
                <p><a href="pages/admin.php">Панель администратора</a></p>
            <?php else: ?>
                <p><a href="pages/new_application.php">Оставить заявку</a></p>
                <p><a href="pages/my_applications.php">Мои заявки</a></p>
            <?php endif; ?>
            <p><a href="pages/courses.php">Все курсы</a></p>
            <p><a href="?logout">Выйти</a></p>
        <?php endif; ?>

        <?php
        if (isset($_GET['logout'])) {
            session_destroy();
            header("Location: index.php");
            exit;
        }
        ?>
    </div>
</body>
</html>
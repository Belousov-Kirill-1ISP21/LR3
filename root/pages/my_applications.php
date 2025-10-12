<?php include '../config.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['admin']) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заявки</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Мои заявки</h2>
        
        <?php
        // Добавление отзыва
        if ($_POST && isset($_POST['rating'])) {
            $user_id = $_SESSION['user_id'];
            $rating = $_POST['rating'];
            $comment = $_POST['comment'];
            
            $sql = "INSERT INTO reviews (user_id, rating, comment) 
                    VALUES ('$user_id', '$rating', '$comment')";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p class='success'>Отзыв добавлен!</p>";
            }
        }
        ?>
        
        <h3>Мои заявки на курсы</h3>
        <?php
        $user_id = $_SESSION['user_id'];
        $result = mysqli_query($conn, "SELECT * FROM applications WHERE user_id = $user_id ORDER BY created_at DESC");
        
        if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Курс</th>
                    <th>Дата начала</th>
                    <th>Способ оплаты</th>
                    <th>Статус</th>
                    <th>Дата подачи</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['course_name']; ?></td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['payment_method'] == 'cash' ? 'Наличные' : 'Перевод'; ?></td>
                    <td>
                        <?php 
                        $statuses = [
                            'new' => 'Новая', 
                            'in_progress' => 'Идет обучение', 
                            'completed' => 'Обучение завершено'
                        ];
                        echo $statuses[$row['status']];
                        ?>
                    </td>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>У вас нет заявок</p>
        <?php endif; ?>
        
        <!-- Форма отзыва - ВСЕГДА ВИДНА -->
        <h3>Оставить отзыв о наших услугах</h3>
        <form method="POST">
            <select name="rating" required>
                <option value="5">5 - Отлично</option>
                <option value="4">4 - Хорошо</option>
                <option value="3">3 - Нормально</option>
                <option value="2">2 - Плохо</option>
                <option value="1">1 - Ужасно</option>
            </select><br><br>
            <textarea name="comment" placeholder="Ваш отзыв о наших услугах" rows="4" cols="50"></textarea><br><br>
            <button type="submit">Отправить отзыв</button>
        </form>
        
        <p><a href="new_application.php">Оставить новую заявку</a></p>
        <p><a href="../index.php">На главную</a></p>
    </div>
</body>
</html>
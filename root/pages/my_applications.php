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
        if ($_POST && isset($_POST['rating'])) {
            $user_id = $_SESSION['user_id'];
            $course_id = $_POST['course_id'];
            $rating = $_POST['rating'];
            $comment = $_POST['comment'];
            
            $sql = "INSERT INTO reviews (user_id, course_id, rating, comment) 
                    VALUES ('$user_id', '$course_id', '$rating', '$comment')";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p class='success'>Отзыв добавлен!</p>";
            } else {
                echo "<p class='error'>Ошибка при добавлении отзыва</p>";
            }
        }
        ?>
        
        <h3>Мои заявки на курсы</h3>
        <?php
        $user_id = $_SESSION['user_id'];
        $result = mysqli_query($conn, "
            SELECT a.*, c.name as course_name, c.price, c.teacher_name, s.name as status_name
            FROM applications a 
            JOIN courses c ON a.course_id = c.id 
            JOIN application_statuses s ON a.status_id = s.id
            WHERE a.user_id = $user_id 
            ORDER BY a.id DESC
        ");
        
        if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Курс</th>
                    <th>Преподаватель</th>
                    <th>Цена</th>
                    <th>Дата начала</th>
                    <th>Статус</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['course_name']; ?></td>
                    <td><?php echo $row['teacher_name']; ?></td>
                    <td><?php echo $row['price']; ?> руб.</td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['status_name']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>У вас нет заявок</p>
        <?php endif; ?>
        
        <h3>Оставить отзыв о курсе</h3>
        <?php
        $courses = mysqli_query($conn, "SELECT * FROM courses");
        ?>
        <form method="POST">
            <select name="course_id" required>
                <option value="">Выберите курс</option>
                <?php
                while($course = mysqli_fetch_assoc($courses)) {
                    echo "<option value='{$course['id']}'>{$course['name']}</option>";
                }
                ?>
            </select><br><br>
            <select name="rating" required>
                <option value="5">5 - Отлично</option>
                <option value="4">4 - Хорошо</option>
                <option value="3">3 - Нормально</option>
                <option value="2">2 - Плохо</option>
                <option value="1">1 - Ужасно</option>
            </select><br><br>
            <textarea name="comment" placeholder="Ваш отзыв о курсе" rows="4" cols="50"></textarea><br><br>
            <button type="submit">Отправить отзыв</button>
        </form>
        
        <p><a href="new_application.php">Оставить новую заявку</a></p>
        <p><a href="courses.php">Все курсы</a></p>
        <p><a href="../index.php">На главную</a></p>
    </div>
</body>
</html>
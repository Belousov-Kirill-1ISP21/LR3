<?php include '../config.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['admin']) {
    header("Location: ../index.php");
    exit;
}

if ($_POST) {
    if (isset($_POST['rating'])) {
        $user_id = $_SESSION['user_id'];
        $course_id = $_POST['course_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        
        $sql = "INSERT INTO reviews (user_id, course_id, rating, comment) 
                VALUES ('$user_id', '$course_id', '$rating', '$comment')";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Отзыв добавлен!";
        } else {
            $error = "Ошибка при добавлении отзыва";
        }
    }
}

$show_review_form = isset($_GET['review']) ? $_GET['review'] : null;
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
        
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        
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
                    <th>Отзыв</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['course_name']; ?></td>
                    <td><?php echo $row['teacher_name']; ?></td>
                    <td><?php echo $row['price']; ?> руб.</td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['status_name']; ?></td>
                    <td>
                        <?php if ($row['status_id'] == 3): ?>
                            <a href="?review=<?php echo $row['course_id']; ?>#review-form">
                                <button>Оставить отзыв</button>
                            </a>
                        <?php else: ?>
                            Завершите курс, чтобы оставить отзыв.
                        <?php endif; ?>
                    </td>
                </tr>
                
                <?php if ($show_review_form == $row['course_id'] && $row['status_id'] == 3): ?>
                <tr>
                    <td colspan="6">
                        <div id="review-form" style="background: #f9f9f9; padding: 15px; margin: 10px 0;">
                            <h4>Оставить отзыв о курсе "<?php echo $row['course_name']; ?>"</h4>
                            <form method="POST">
                                <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">
                                <select name="rating" required>
                                    <option value="5">5 - Отлично</option>
                                    <option value="4">4 - Хорошо</option>
                                    <option value="3">3 - Нормально</option>
                                    <option value="2">2 - Плохо</option>
                                    <option value="1">1 - Ужасно</option>
                                </select><br><br>
                                <textarea name="comment" placeholder="Ваш отзыв о курсе" rows="4" cols="50" required></textarea><br><br>
                                <button type="submit">Отправить отзыв</button>
                                <a href="my_applications.php"><button type="button">Отмена</button></a>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
                
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>У вас нет заявок</p>
        <?php endif; ?>
        
        <p><a href="new_application.php">Оставить новую заявку</a></p>
        <p><a href="courses.php">Все курсы</a></p>
        <p><a href="../index.php">На главную</a></p>
    </div>
</body>
</html>
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
    <title>Новая заявка</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Новая заявка на курс</h2>
        
        <?php
        if ($_POST) {
            $user_id = $_SESSION['user_id'];
            $course_id = $_POST['course_id'];
            $start_date = $_POST['start_date'];
            $payment_method = $_POST['payment_method'];
            $status_id = 1;
            
            $sql = "INSERT INTO applications (user_id, course_id, start_date, payment_method, status_id) 
                    VALUES ('$user_id', '$course_id', '$start_date', '$payment_method', '$status_id')";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p class='success'>Заявка отправлена!</p>";
            } else {
                echo "<p class='error'>Ошибка: " . mysqli_error($conn) . "</p>";
            }
        }
        
        $courses = mysqli_query($conn, "SELECT * FROM courses");
        ?>
        
        <form method="POST">
            <select name="course_id" required>
                <option value="">Выберите курс</option>
                <?php
                while($course = mysqli_fetch_assoc($courses)) {
                    echo "<option value='{$course['id']}'>{$course['name']} - {$course['price']} руб.</option>";
                }
                ?>
            </select><br><br>
            
            <input type="date" name="start_date" required><br><br>
            <select name="payment_method" required>
                <option value="cash">Наличные</option>
                <option value="transfer">Перевод по номеру телефона</option>
            </select><br><br>
            <button type="submit">Отправить заявку</button>
        </form>
        
        <p><a href="my_applications.php">Мои заявки</a></p>
        <p><a href="courses.php">Все курсы</a></p>
        <p><a href="../index.php">На главную</a></p>
    </div>
</body>
</html>
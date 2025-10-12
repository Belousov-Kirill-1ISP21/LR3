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
            $course_name = $_POST['course_name'];
            $start_date = $_POST['start_date'];
            $payment_method = $_POST['payment_method'];
            
            $sql = "INSERT INTO applications (user_id, course_name, start_date, payment_method) 
                    VALUES ('$user_id', '$course_name', '$start_date', '$payment_method')";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p class='success'>Заявка отправлена!</p>";
            } else {
                echo "<p class='error'>Ошибка: " . mysqli_error($conn) . "</p>";
            }
        }
        ?>
        
        <form method="POST">
            <input type="text" name="course_name" placeholder="Название курса" required><br><br>
            <input type="date" name="start_date" required><br><br>
            <select name="payment_method" required>
                <option value="cash">Наличные</option>
                <option value="transfer">Перевод по номеру телефона</option>
            </select><br><br>
            <button type="submit">Отправить заявку</button>
        </form>
        
        <p><a href="my_applications.php">Мои заявки</a></p>
        <p><a href="../index.php">На главную</a></p>
    </div>
</body>
</html>
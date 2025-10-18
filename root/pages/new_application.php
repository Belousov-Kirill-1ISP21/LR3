<?php include '../config.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['admin']) {
    header("Location: ../index.php");
    exit;
}


$courses_result = mysqli_query($conn, "
    SELECT c.*, t.full_name as teacher_name 
    FROM courses c 
    LEFT JOIN teachers t ON c.teacher_id = t.id
");


$courses_info = [];
while($course = mysqli_fetch_assoc($courses_result)) {
    $courses_info[$course['id']] = [
        'description' => $course['description'],
        'teacher' => $course['teacher_name'],
        'duration' => $course['duration_hours'],
        'price' => $course['price']
    ];
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
            
            $sql = "INSERT INTO applications (user_id, course_id, start_date, payment_method) 
                    VALUES ('$user_id', '$course_id', '$start_date', '$payment_method')";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p class='success'>Заявка отправлена!</p>";
            } else {
                echo "<p class='error'>Ошибка: " . mysqli_error($conn) . "</p>";
            }
        }
        ?>
        
        <form method="POST">
            <select name="course_id" required onchange="showCourseInfo(this.value)">
                <option value="">Выберите курс</option>
                <?php
                mysqli_data_seek($courses_result, 0);
                while($course = mysqli_fetch_assoc($courses_result)) {
                    echo "<option value='{$course['id']}'>{$course['name']} - {$course['price']} руб.</option>";
                }
                ?>
            </select><br><br>
            
            <div id="courseInfo">
                <h4>Информация о курсе:</h4>
                <p><strong>Описание:</strong> <span id="courseDescription"></span></p>
                <p><strong>Преподаватель:</strong> <span id="courseTeacher"></span></p>
                <p><strong>Продолжительность:</strong> <span id="courseDuration"></span> часов</p>
                <p><strong>Стоимость:</strong> <span id="coursePrice"></span> руб.</p>
            </div>
            
            <input type="date" name="start_date" required><br><br>
            <select name="payment_method" required>
                <option value="cash">Наличные</option>
                <option value="transfer">Перевод по номеру телефона</option>
            </select><br><br>
            <button type="submit">Отправить заявку</button>
        </form>

        <script>
        const coursesInfo = <?php echo json_encode($courses_info); ?>;
        
        function showCourseInfo(courseId) {
            const courseInfo = document.getElementById('courseInfo');
            const description = document.getElementById('courseDescription');
            const teacher = document.getElementById('courseTeacher');
            const duration = document.getElementById('courseDuration');
            const price = document.getElementById('coursePrice');
            
            if (!courseId || !coursesInfo[courseId]) {
                courseInfo.style.display = 'none';
                return;
            }
            
            const course = coursesInfo[courseId];
            description.textContent = course.description || 'Описание отсутствует';
            teacher.textContent = course.teacher || 'Не назначен';
            duration.textContent = course.duration || '0';
            price.textContent = course.price || '0';
            
            courseInfo.style.display = 'block';
        }
        </script>
        
        <p><a href="my_applications.php">Мои заявки</a></p>
        <p><a href="../index.php">На главную</a></p>
    </div>
</body>
</html>
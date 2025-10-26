<?php include '../config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все курсы</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Все курсы</h2>
        
        <?php
        $courses = mysqli_query($conn, "SELECT * FROM courses");
        while($course = mysqli_fetch_assoc($courses)): 
        ?>
        <div class="course-block">
            <h3><?php echo $course['name']; ?></h3>
            <p><strong>Преподаватель:</strong> <?php echo $course['teacher_name']; ?></p>
            <p><strong>Описание:</strong> <?php echo $course['description']; ?></p>
            <p><strong>Продолжительность:</strong> <?php echo $course['duration_hours']; ?> часов</p>
            <p><strong>Цена:</strong> <?php echo $course['price']; ?> руб.</p>
            
            <div class="course-reviews">
                <h4>Отзывы о курсе:</h4>
                <?php
                $course_id = $course['id'];
                $reviews = mysqli_query($conn, "
                    SELECT r.*, u.full_name 
                    FROM reviews r 
                    JOIN users u ON r.user_id = u.id 
                    WHERE r.course_id = $course_id 
                    ORDER BY r.created_at DESC
                ");
                
                if (mysqli_num_rows($reviews) > 0): 
                    while($review = mysqli_fetch_assoc($reviews)): 
                ?>
                    <div class="review-item">
                        <p><strong><?php echo $review['full_name']; ?></strong> 
                        (Оценка: <?php echo $review['rating']; ?>/5)</p>
                        <p><?php echo $review['comment']; ?></p>
                        <small><?php echo $review['created_at']; ?></small>
                    </div>
                <?php 
                    endwhile;
                else: 
                ?>
                    <p>Пока нет отзывов</p>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
        
        <p><a href="../index.php">На главную</a></p>
    </div>
</body>
</html>
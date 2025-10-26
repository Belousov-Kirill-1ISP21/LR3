<?php include '../config.php'; 
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Панель администратора</h2>
        
        <?php
        if ($_POST && isset($_POST['application_id'])) {
            $application_id = $_POST['application_id'];
            $status_id = $_POST['status_id'];
            
            $sql = "UPDATE applications SET status_id = '$status_id' WHERE id = $application_id";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p class='success'>Статус обновлен!</p>";
            } else {
                echo "<p class='error'>Ошибка: " . mysqli_error($conn) . "</p>";
            }
        }
        ?>
        
        <h3>Все заявки</h3>
        <?php
        $result = mysqli_query($conn, "
            SELECT a.*, u.full_name, u.login, c.name as course_name, c.teacher_name, s.name as status_name
            FROM applications a 
            JOIN users u ON a.user_id = u.id 
            JOIN courses c ON a.course_id = c.id
            JOIN application_statuses s ON a.status_id = s.id
            ORDER BY a.id DESC
        ");
        
        if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Пользователь</th>
                    <th>Курс</th>
                    <th>Преподаватель</th>
                    <th>Дата начала</th>
                    <th>Статус</th>
                    <th>Изменить статус</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['full_name'] . ' (' . $row['login'] . ')'; ?></td>
                    <td><?php echo $row['course_name']; ?></td>
                    <td><?php echo $row['teacher_name']; ?></td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['status_name']; ?></td>
                    <td>
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                            <select name="status_id">
                                <option value="1" <?php echo $row['status_id'] == 1 ? 'selected' : ''; ?>>Новая</option>
                                <option value="2" <?php echo $row['status_id'] == 2 ? 'selected' : ''; ?>>Обучается</option>
                                <option value="3" <?php echo $row['status_id'] == 3 ? 'selected' : ''; ?>>Завершено</option>
                            </select>
                            <button type="submit">Обновить</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Нет заявок</p>
        <?php endif; ?>
        
        <p><a href="../index.php">На главную</a></p>
    </div>
</body>
</html>
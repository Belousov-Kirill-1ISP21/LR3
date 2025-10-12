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
        if ($_POST) {
            $application_id = $_POST['application_id'];
            $status = $_POST['status'];
            
            $sql = "UPDATE applications SET status = '$status' WHERE id = $application_id";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p class='success'>Статус обновлен!</p>";
            }
        }
        ?>
        
        <h3>Все заявки</h3>
        <?php
        $result = mysqli_query($conn, "
            SELECT a.*, u.full_name, u.login 
            FROM applications a 
            JOIN users u ON a.user_id = u.id 
            ORDER BY a.created_at DESC
        ");
        
        if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Пользователь</th>
                    <th>Курс</th>
                    <th>Дата начала</th>
                    <th>Способ оплаты</th>
                    <th>Статус</th>
                    <th>Дата подачи</th>
                    <th>Изменить статус</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['full_name'] . ' (' . $row['login'] . ')'; ?></td>
                    <td><?php echo $row['course_name']; ?></td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['payment_method'] == 'cash' ? 'Наличные' : 'Перевод'; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                            <select name="status">
                                <option value="new" <?php echo $row['status'] == 'new' ? 'selected' : ''; ?>>Новая</option>
                                <option value="in_progress" <?php echo $row['status'] == 'in_progress' ? 'selected' : ''; ?>>Идет обучение</option>
                                <option value="completed" <?php echo $row['status'] == 'completed' ? 'selected' : ''; ?>>Завершено</option>
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
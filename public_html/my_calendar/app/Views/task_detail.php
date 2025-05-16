<?php // app/Views/task_detail.php ?>
<h2>Редактировать задачу: "<?= htmlspecialchars($task['title']) ?>"</h2>

<form action="index.php?action=update&id=<?= $task['id'] ?>" method="post">
    <div>
        <label for="title">Тема*:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
    </div>
    <div>
        <label for="type">Тип*:</label>
        <select id="type" name="type" required>
            <option value="meeting" <?= $task['type'] == 'meeting' ? 'selected' : '' ?>>Встреча</option>
            <option value="call" <?= $task['type'] == 'call' ? 'selected' : '' ?>>Звонок</option>
            <option value="conference" <?= $task['type'] == 'conference' ? 'selected' : '' ?>>Совещание</option>
            <option value="task_item" <?= $task['type'] == 'task_item' ? 'selected' : '' ?>>Дело</option>
        </select>
    </div>
    <div>
        <label for="location">Место:</label>
        <input type="text" id="location" name="location" value="<?= htmlspecialchars($task['location'] ?? '') ?>">
    </div>
    <div>
        <label for="event_datetime">Дата и время*:</label>
        <input type="datetime-local" id="event_datetime" name="event_datetime" value="<?= htmlspecialchars($task['html_datetime']) ?>" required>
    </div>
    <div>
        <label for="duration_minutes">Длительность (минут)*:</label>
        <input type="number" id="duration_minutes" name="duration_minutes" min="1" value="<?= htmlspecialchars($task['duration_minutes']) ?>" required>
    </div>
    <div>
        <label for="comment">Комментарий:</label>
        <textarea id="comment" name="comment"><?= htmlspecialchars($task['comment'] ?? '') ?></textarea>
    </div>
    <div>
        <label for="status">Статус:</label>
        <select id="status" name="status">
            <option value="pending" <?= $task['status'] == 'pending' ? 'selected' : '' ?>>Ожидание</option>
            <option value="completed" <?= $task['status'] == 'completed' ? 'selected' : '' ?>>Выполнено</option>
        </select>
    </div>
    <button type="submit">Сохранить изменения</button>
    <a href="index.php" class="button">Назад к списку</a>
</form>
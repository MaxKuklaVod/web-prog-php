<?php // app/Views/home.php ?>
<section class="task-form-section">
    <h2>Добавить новую задачу</h2>
    <form action="index.php" method="post">
        <input type="hidden" name="action" value="create_task">
        <div>
            <label for="title">Тема*:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div>
            <label for="type">Тип*:</label>
            <select id="type" name="type" required>
                <option value="meeting">Встреча</option>
                <option value="call">Звонок</option>
                <option value="conference">Совещание</option>
                <option value="task_item">Дело</option>
            </select>
        </div>
        <div>
            <label for="location">Место:</label>
            <input type="text" id="location" name="location">
        </div>
        <div>
            <label for="event_datetime">Дата и время*:</label>
            <input type="datetime-local" id="event_datetime" name="event_datetime" required>
        </div>
        <div>
            <label for="duration_minutes">Длительность (минут)*:</label>
            <input type="number" id="duration_minutes" name="duration_minutes" min="1" required>
        </div>
        <div>
            <label for="comment">Комментарий:</label>
            <textarea id="comment" name="comment"></textarea>
        </div>
        <button type="submit">Добавить задачу</button>
    </form>
</section>

<section class="task-list-section">
    <h2>
        <?php
        $filter_titles = [
            'current' => 'Текущие задачи',
            'overdue' => 'Просроченные задачи',
            'completed' => 'Выполненные задачи',
            'date' => 'Задачи на ' . ($date_filter_value ? htmlspecialchars(date("d.m.Y", strtotime($date_filter_value))) : 'выбранную дату')
        ];
        echo $filter_titles[$current_filter] ?? 'Список задач';
        ?>
    </h2>
    <?php if (empty($tasks)): ?>
        <p>Задач нет.</p>
    <?php else: ?>
        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
                <?php include __DIR__ . '/partials/_task_item.php'; // Используем частичный шаблон ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
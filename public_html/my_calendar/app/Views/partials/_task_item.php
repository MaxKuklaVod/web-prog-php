<?php // app/Views/partials/_task_item.php 
    $task_types_rus = [
        'meeting' => 'Встреча',
        'call' => 'Звонок',
        'conference' => 'Совещание',
        'task_item' => 'Дело'
    ];
?>
<li class="task-item <?php echo $task['status'] === 'completed' ? 'completed' : ''; echo ($task['status'] === 'pending' && new DateTime($task['event_datetime']) < new DateTime()) ? ' overdue-item' : ''; ?>">
    <h3>
        <a href="index.php?action=show&id=<?= $task['id'] ?>">
            <?= htmlspecialchars($task['title']) ?>
        </a>
    </h3>
    <p><strong>Тип:</strong> <?= htmlspecialchars($task_types_rus[$task['type']] ?? $task['type']) ?></p>
    <p><strong>Дата и время:</strong> <?= htmlspecialchars(date("d.m.Y H:i", strtotime($task['event_datetime']))) ?></p>
    <p><strong>Длительность:</strong> <?= htmlspecialchars($task['duration_minutes']) ?> мин.</p>
    <?php if ($task['location']): ?>
        <p><strong>Место:</strong> <?= htmlspecialchars($task['location']) ?></p>
    <?php endif; ?>
    <?php if ($task['comment']): ?>
        <p><strong>Комментарий:</strong> <?= nl2br(htmlspecialchars($task['comment'])) ?></p>
    <?php endif; ?>
    <p><strong>Статус:</strong> <?= $task['status'] === 'completed' ? 'Выполнено' : 'Ожидание' ?></p>
    
    <div class="task-actions">
        <a href="index.php?action=show&id=<?= $task['id'] ?>" class="button edit">Редактировать</a>
        <?php if ($task['status'] === 'pending'): ?>
            <a href="index.php?action=complete&id=<?= $task['id'] ?>" class="button complete" onclick="return confirm('Отметить задачу как выполненную?');">Выполнено</a>
        <?php endif; ?>
    </div>
</li>
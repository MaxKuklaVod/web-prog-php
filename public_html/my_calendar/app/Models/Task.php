<?php
// app/Models/Task.php

class Task {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function getAll($filter = 'current', $date = null) {
        $sql = "SELECT *, DATE_FORMAT(event_datetime, '%Y-%m-%dT%H:%i') as html_datetime FROM tasks";
        $params = [];
        $conditions = [];

        $now = date('Y-m-d H:i:s');

        switch ($filter) {
            case 'current': // Текущие = ожидающие, не просроченные
                $conditions[] = "status = 'pending'";
                $conditions[] = "event_datetime >= :now";
                $params[':now'] = $now;
                break;
            case 'overdue':
                $conditions[] = "status = 'pending'";
                $conditions[] = "event_datetime < :now";
                $params[':now'] = $now;
                break;
            case 'completed':
                $conditions[] = "status = 'completed'";
                break;
            case 'date':
                if ($date) {
                    $conditions[] = "DATE(event_datetime) = :event_date";
                    $params[':event_date'] = $date;
                }
                break;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        $sql .= " ORDER BY event_datetime ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        // Добавляем html_datetime для удобства заполнения формы <input type="datetime-local">
        $stmt = $this->pdo->prepare("SELECT *, DATE_FORMAT(event_datetime, '%Y-%m-%dT%H:%i') as html_datetime FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO tasks (title, type, location, event_datetime, duration_minutes, comment) 
                VALUES (:title, :type, :location, :event_datetime, :duration_minutes, :comment)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':type' => $data['type'],
            ':location' => $data['location'],
            ':event_datetime' => $data['event_datetime'],
            ':duration_minutes' => $data['duration_minutes'],
            ':comment' => $data['comment']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE tasks SET title = :title, type = :type, location = :location, 
                event_datetime = :event_datetime, duration_minutes = :duration_minutes, 
                comment = :comment, status = :status
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':type' => $data['type'],
            ':location' => $data['location'],
            ':event_datetime' => $data['event_datetime'],
            ':duration_minutes' => $data['duration_minutes'],
            ':comment' => $data['comment'],
            ':status' => $data['status'] ?? 'pending' // Если статус не передан, оставляем pending
        ]);
    }
    
    public function markCompleted($id) {
        $sql = "UPDATE tasks SET status = 'completed' WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

}
?>
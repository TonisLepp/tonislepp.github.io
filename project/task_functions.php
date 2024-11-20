<?php

include_once 'Task.php';
include_once 'connection.php';

//const DATA_FILE = 'tasks.txt';

function getAllTasks() : array {
//    $lines = file(DATA_FILE);
//    $tasks = [];
//    foreach ($lines as $line) {
//        [$id, $description, $difficulty] = explode(';', trim($line));
//        $tasks[] = new Task(urldecode($id), urldecode($description), urldecode($difficulty));
//    }
//
//    return $tasks;


    $conn = getConnection();

    $stmt = $conn->query('SELECT id, description, difficulty, employee_id FROM tasks');

    $tasks = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tasks[] = new Task(
            urldecode($row['id']),
            urldecode($row['description']),
            urldecode($row['difficulty'] ?? ''),
            urldecode($row['employee_id'] ?? '')
        );
    }
    return $tasks;
}

function saveTask(Task $task): void {
//    if ($task->id) {
//        deleteTaskById($task->id);
//    }
//
//    $task->id  = $task->id ?? getNewId();
//
//    file_put_contents(DATA_FILE ,
//        taskToTextLine($task), FILE_APPEND);
//    return $task->id;

    $conn = getConnection();

    $task->id  = $task->id ?? getNewId();

    $stmt = $conn->prepare(
        "INSERT INTO tasks (id, description, difficulty, employee_id)
             VALUES (:id, :description, :difficulty, :employee_id)
             ON DUPLICATE KEY UPDATE
                 description = VALUES(description),
                 difficulty = VALUES(difficulty),
                 employee_id = VALUES(employee_id)"
    );

    $stmt->bindParam(':id', $task->id);
    $stmt->bindParam(':description', $task->description);
    $stmt->bindParam(':difficulty', $task->difficulty);
    $stmt->bindParam(':employee_id', $task->employee_id);

    $stmt->execute();
}

function taskToTextLine(Task $task) : string {
    return urlencode($task->id) . ';'
        . urlencode($task->description) . ';'
        . urlencode($task->difficulty) . PHP_EOL;

}

function deleteTaskById(string $id): void {
    $conn = getConnection();

    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

function getNewTaskId(): string {
    $conn = getConnection();

    $conn->beginTransaction();

    $stmt = $conn->query("SELECT next_id FROM next_task_id FOR UPDATE");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentId = $row['next_id'];

    $newId = $currentId + 1;

    $updateStmt = $conn->prepare("UPDATE next_task_id SET next_id = :newId");
    $updateStmt->bindParam(':newId', $newId);
    $updateStmt->execute();

    $conn->commit();

    return (string)$currentId;
}

function tasksToString(array $tasks): string {
    $result = '';
    foreach ($tasks as $task) {
        $result .= $task . PHP_EOL;
    }
    return $result;
}
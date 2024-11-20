<?php

include_once 'Employee.php';
include_once 'connection.php';


function getAllEmployees() : array {

    $conn = getConnection();

    $stmt = $conn->query('SELECT id, first_name, last_name, role, pfp FROM employees');

    $employees = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $employees[] = new Employee(
            urldecode($row['id']),
            urldecode($row['first_name']),
            urldecode($row['last_name']),
            urldecode($row['role']),
            urldecode($row['pfp'])
        );
    }
    return $employees;
}

function saveEmployees(Employee $employee): void {

    $conn = getConnection();

    $employee->id  = $employee->id ?? getNewId();

    $stmt = $conn->prepare(
        "INSERT INTO employees (id, first_name, last_name, role, pfp)
             VALUES (:id, :firstName, :lastName, :role, :pfp)
             ON DUPLICATE KEY UPDATE
                 first_name = VALUES(first_name),
                 last_name = VALUES(last_name),
                 role = VALUES(role),
                 pfp = VALUES(pfp)"
    );

    $stmt->bindParam(':id', $employee->id);
    $stmt->bindParam(':firstName', $employee->firstName);
    $stmt->bindParam(':lastName', $employee->lastName);
    $stmt->bindParam(':role', $employee->role);
    $stmt->bindParam(':pfp', $employee->pfp);

    $stmt->execute();
}

function employeeToTextLine(Employee $employee) : string {
    return urlencode($employee->id) . ';'
        . urlencode($employee->firstName) . ';'
        . urlencode($employee->lastName) . ';'
        . urlencode($employee->role) . ';'
        . urlencode($employee->pfp) . PHP_EOL;

}

function deleteEmployeeById(string $id): void {

    $conn = getConnection();

    $conn->beginTransaction();

    $stmt = $conn->prepare("SELECT pfp FROM employees WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (!empty($row['pfp']) && file_exists($row['pfp'])) {
            unlink($row['pfp']);
        }

        $deleteStmt = $conn->prepare("DELETE FROM employees WHERE id = :id");
        $deleteStmt->bindParam(':id', $id);
        $deleteStmt->execute();
    }
    $conn->commit();
}

function getNewEmpId(): string {

    $conn = getConnection();

    $conn->beginTransaction();

    $stmt = $conn->query("SELECT next FROM next_id FOR UPDATE");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentId = $row['next'];

    $newId = $currentId + 1;

    $updateStmt = $conn->prepare("UPDATE next_id SET next = :newId");
    $updateStmt->bindParam(':newId', $newId);
    $updateStmt->execute();

    $conn->commit();

    return (string)$currentId;
}

function employeesToString(array $employees): string {
    $result = '';
    foreach ($employees as $employee) {
        $result .= $employee . PHP_EOL;
    }
    return $result;
}
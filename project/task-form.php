<?php
$error = "";
$description = "";
$estimate = $_POST["estimate"] ?? null;
$id = $_GET["id"] ?? null;
$selectedEmployeeId = $_POST['employeeId'] ?? null;

require_once 'task_functions.php';
require_once 'employee_functions.php';

$employees = getAllEmployees();

foreach (getAllTasks() as $task) {
    if ($id === $task->id){
        $description = $task->description;
        $estimate  = $task->difficulty ?? null;
        $selectedEmployeeId = $task->employee_id;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $delete = $_POST["deleteButton"] ?? null;
    $id = $_POST["id"] ?? null;
    if (!is_null($delete)) {
        deleteTaskById($id);
        header("Location: task-list.php?msg=Success");
        exit();
    }
    if (is_null($id) or $id == "") {
        $id = getNewTaskId();
    }
    $description = $_POST["description"];
    if (strlen($description) < 5 || strlen($description) > 40) {
        $error = "Description must be between 5 and 40 characters!";
    } else {
        if (!is_int($estimate)) {
            $estimate = null;
        } else {
            $estimate = $_POST["estimate"] ?? null;
        }
        if (!is_numeric($selectedEmployeeId)) {
            $selectedEmployeeId = null;
        }
        $task = new Task($id, $_POST['description'], $estimate, $selectedEmployeeId);
        saveTask($task);
        header("Location: task-list.php?msg=Success");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body id="task-form-page">
<nav>
    <?php include_once 'navigation.html'?>
</nav>

<div id="dashboard">
    <div class="panel">
        <div class="panel_header">Add task</div>
        <div class="panel_item">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input name="id" hidden="hidden" value="<?php echo $id ?>">
                <div class="name_label">Description:</div>
                <div class="name_input"><input name="description" value="<?php echo htmlspecialchars($description); ?>"></div>

                <div class="name_label">Estimate:</div>
                <div class="name_input">
                    <input type="radio" name="estimate" value="1" <?php if ($estimate === '1') {echo 'checked';} ?>> 1
                    <input type="radio" name="estimate" value="2" <?php if ($estimate === '2') {echo 'checked';} ?>> 2
                    <input type="radio" name="estimate" value="3" <?php if ($estimate === '3') {echo 'checked';} ?>> 3
                    <input type="radio" name="estimate" value="4" <?php if ($estimate === '4') {echo 'checked';} ?>> 4
                    <input type="radio" name="estimate" value="5" <?php if ($estimate === '5') {echo 'checked';} ?>> 5
                </div>
                <div class="name_label">Assigned employee:</div>
                <div>
                    <select name="employeeId">
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?php echo htmlspecialchars($employee->id); ?>"
                                <?php if (strval($selectedEmployeeId) == strval($employee->id)) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($employee->firstName . ' ' . $employee->lastName); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="name_label"></div>
                <div class="name_input"><button type="submit" name="submitButton" value="b1">Save</button></div>
                <?php $id = $_GET["id"] ?? null;
                if (!is_null($id)) {
                    echo '<div class="delete"><button type="submit" name="deleteButton" value="1">Delete</button></div>';
                }
                ?>
            </form>
        </div>
        <div class="error-popup <?php echo !empty($error) ? 'show' : ''; ?>" id="error-block">
            <p><?php echo $error; ?></p>
            <button onclick="document.querySelector('.error-popup').style.display='none'">Close</button>
        </div>
    </div>
</div>

<footer>
    icd0007 Sample application
</footer>
</body>
</html>
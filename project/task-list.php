<?php

//var_dump($_GET);


require_once 'task_functions.php';

$msg = $_GET['msg'] ?? null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task list</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body id="task-list-page">
<nav>
    <?php include_once 'navigation.html'?>
</nav>

<div id="dashboard">
    <div class="panel">
        <div class="panel_header">Tasks</div>
        <div class="panel_item">
            <?php
            foreach (getAllTasks() as $singleTask) {
            $taskText = strval($singleTask->description);
            $id = $singleTask->id;
            $taskElement = '<div class="task">' . '<div data-task-id="' . $id .  '" class="text">' . $taskText . '</div>' . '<div class="editButton">' .
                '<span class="editLink"><a id="task-edit-link-' . $id .
                '" href="task-form.php?id=' . $id . '">Edit</a></span>' . '</div>' . '</div>' . PHP_EOL;
            echo $taskElement;
            }
            ?>
        </div>
        <div class="success-popup <?php echo !empty($msg) ? 'show' : ''; ?>" id="message-block">
            <p><?php echo $msg; ?></p>
            <button onclick="document.querySelector('.success-popup').style.display='none'">Close</button>
        </div>
    </div>
</div>

<footer>
    icd0007 Sample application
</footer>
</body>
</html>
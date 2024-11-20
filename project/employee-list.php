<?php

require_once 'employee_functions.php';

$msg = $_GET['msg'] ?? null;

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee list</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body id="employee-list-page">
<nav>
    <?php include_once 'navigation.html'?>
</nav>

<div id="dashboard">
  <div class="panel">
    <div class="panel_header">Employees</div>
    <div class="panel_item">
          <?php
          foreach (getAllEmployees() as $singleEmployee) {
              echo '<div class="employee">';
              $firstName = $singleEmployee->firstName;
              $lastName = $singleEmployee->lastName;
              $name = $firstName . ' ' . $lastName;
              $id = $singleEmployee->id;
              $profilePic = $singleEmployee->pfp;
              if ($singleEmployee->role === '') {
                  $role = 'No role added';
              } else {
                  $role = $singleEmployee->role;
              }
              $employeeElement =  '<img src="' . $profilePic . '" alt="profile picture" data-employee-id="' . $id
                  .  '">' . '<div data-employee-id="' . $id .  '" class="name">' . $name
                  . '</div>' . '<br><span class="position">' . $role . '</span>' . '<div class="editButton">' .
                  '<span class="editLink"><a id="employee-edit-link-' . $id .  '" href="employee-form.php?id=' . $id . '">Edit</a></span>' . '</div>' . PHP_EOL;
              echo $employeeElement;
              echo '</div>';
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
<?php
$error = "";
$firstName = "";
$lastName = "";
$role = "";
$id = $_GET["id"] ?? null;

require_once 'employee_functions.php';

foreach (getAllEmployees() as $emp) {
    if ($id === $emp->id) {
        $firstName = $emp->firstName ?? null;
        $lastName = $emp->lastName ?? null;
        $role = $emp->role ?? null;
        $pfp = $emp->pfp ?? null;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $delete = $_POST["deleteButton"] ?? null;
    $id = $_POST["id"] ?? null;
    if (!is_null($delete)) {
        deleteEmployeeById($id);
        header("Location: employee-list.php?msg=Success");
        exit();
    }
    if (is_null($id) or $id == "") {
        $id = getNewEmpId();
    }
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $role = $_POST["role"];
    $newFileName = '';
    if (strlen($firstName) < 1 || strlen($firstName) > 21) {
        $error = "First name must be between 1 and 21 characters!";
    } elseif (strlen($lastName) < 2 || strlen($lastName) > 22) {
        $error = "Last name must be between 2 and 22 characters!";
    } else {
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
            $target_dir = "pfps/";
            $imageFileType = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));
            $newFileName = $target_dir . "employee" . $id . "." . $imageFileType; // Rename file

            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $newFileName)) {
                echo "The file " . htmlspecialchars(basename($newFileName)) . " has been uploaded.";
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
        $employee = new employee($id, $_POST['firstName'], $_POST['lastName'], $_POST['role'], $newFileName);
        saveEmployees($employee);
        header("Location: employee-list.php?msg=Success");
        exit();
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body id="employee-form-page">
<nav>
<?php include_once 'navigation.html'?>
</nav>

<div id="dashboard">
  <div class="panel">
    <div class="panel_header">Employee form</div>
    <div class="panel_item">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input name="id" hidden="hidden" value="<?php echo $id ?>">
        <div class="name_label">First name:</div>
        <div class="name_input"><input name="firstName" value="<?php echo htmlspecialchars($firstName); ?>"></div>

        <div class="name_label">Last name:</div>
        <div class="name_input"><input name="lastName" value="<?php echo htmlspecialchars($lastName); ?>"></div>

        <div class="name_label">Position:</div>
        <div class="name_input"><input name="role" value="<?php echo htmlspecialchars($role) ?? ''; ?>"></div>

        <div class="name_label">Profile Picture:</div>
        <div class="name_input"><input type="file" name="picture" id="picture"></div>

        <div class="name_label"></div>
        <div class="name_input"><button type="submit" name="submitButton" value="0">Save</button></div>
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
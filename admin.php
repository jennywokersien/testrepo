<?php
$dsn = 'mysql:host=localhost;dbname=ejdesign';
$username = 'ej_user';   //'ej_user';
$password = 'Pa$$w0rd';

try {
    $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    $error_message = $e->getMessage();
    /* include('database_error.php'); */
    echo "DB Error: " . $error_message;
    exit();
}
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list_visits';
    }
}

if ($action == 'list_visits') {
    $employee_id = filter_input(INPUT_GET, 'employee_id', FILTER_VALIDATE_INT);
    if ($employee_id == NULL || $employee_id == FALSE) {
        $employee_id = filter_input(INPUT_POST, 'employee_id', FILTER_VALIDATE_INT);
        if ($employee_id == NULL || $employee_id == FALSE) {
                    $employee_id = 1;
        }
    }
    try {
        $queryEmployee = 'SELECT * FROM employee';
        $statement1 = $db->prepare($queryEmployee);
        $statement1->execute();
        $employees = $statement1;
        
        $queryVisit = 'SELECT visit_id, visit.first_name, visit.last_name, '
                . 'visit.email_address, visit_reason, visit_msg, visit_date, '
                . 'visit.employee_id '
                . 'FROM visit '
                . 'JOIN employee on visit.employee_id = employee.employee_id '
                . 'WHERE employee.employee_id = :employee_id  '
                . 'ORDER BY visit_date';
        $statement2 = $db->prepare($queryVisit);
        $statement2->bindValue(":employee_id", $employee_id);
        $statement2->execute();
        $visits = $statement2;

    } catch (PDOException $ex) {
        echo 'Error: ' . $ex->getMessage();
    }
} else if ($action == 'delete_visit') {
    $visit_id = filter_input(INPUT_POST, 'visit_id', FILTER_VALIDATE_INT);
    $employee_id = filter_input(INPUT_POST, 'employee_id', FILTER_VALIDATE_INT);
    //echo ('Starting delete logic for vist: ' . $visit_id );
    $queryDelete = 'DELETE FROM visit WHERE visit_id = :visit_id';
    $statement3 = $db->prepare($queryDelete);
    $statement3->bindValue(":visit_id", $visit_id);
    $statement3->execute();
    $statement3->closeCursor();
    header("Location: admin.php?employee_id=$employee_id");
    
}

/* echo "Fields: " . $fname . $lname . $reason . $comments; */
?>

<!DOCTYPE html>
<html lang="">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eva Jones Design</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/styles1.css">
        <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@1,900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">

    </head>

    <body>
        <div class=""><a class="" href="index.html"><img id="logo"  src="images/logo.png" alt="Eva Jones Logo" class=" navbar-brand float-left fixed-top position-absolute ml-5 mt-3"></a></div>
        <nav class="navbar navbar-fixed-top navbar-dark bg-dark text-light navbar-expand-md mb-5">


            <div class="mb-auto ml-auto">
                <button type="button" class="d-md-none navbar-toggler-icon" data-toggle="collapse" data-target="#navbarNav">
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item text-underline ml-auto"><a href="index.html" class="active nav-link text-light">
                            <h4>Home</h4>
                        </a></li>
                    <li class="nav-item ml-auto"><a href="about.html" class="nav-link text-light">
                            <h4>About</h4>
                        </a></li>
                    <li class="nav-item ml-auto"><a href="contact.html" class="nav-link text-light">
                            <h4><u>Contact</u></h4>
                        </a></li>
                </ul>
            </div>
        </nav>
        <div class="customM1 container">
            <section class="container rounded mt-5 p-5">
                <h1>Admin</h1>
                <h3>Select an employee to see visit information. </h3>
                <aside width: 30%; background-color: green;>
                    <ul style="list-style-type:none;">
                        <?php foreach ($employees as $employee) : ?>
                        <li>
                            <a href="?employee_id=<?php echo $employee['employee_id']; ?>">
                                <?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </aside>
                <table>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Reason</th>
                        <th>Message</th>
                        <th></th>
                    </tr>
                    <?php foreach ($visits as $visit) : ?>
                    <tr>
                        <td><?php echo $visit['first_name']; ?></td>
                        <td><?php echo $visit['last_name']; ?></td>
                        <td><?php echo $visit['email_address']; ?></td>
                        <td><?php echo $visit['visit_date']; ?></td>
                        <td><?php echo $visit['visit_reason']; ?></td>
                        <td><?php echo $visit['visit_msg']; ?></td>
                        <td>
                            <form action="admin.php" method="post">
                                <input type="hidden" name="employee_id"
                                       value="<?php echo $visit['employee_id']; ?>">
                                <input type="hidden" name="action" value="delete_visit">
                                <input type="hidden" name="visit_id"
                                       value="<?php echo $visit['visit_id']; ?>">
                                <input type="submit" value="Delete">
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </section>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    </body>

</html>

<?php
    
    $fname = filter_input(INPUT_POST, 'firstname');
    $lname = filter_input(INPUT_POST, 'lastname');
    $reason = filter_input(INPUT_POST, 'reason');
    $email = filter_input(INPUT_POST, 'email');
    $comments = filter_input(INPUT_POST, 'comments');
    /* echo "Fields: " . $visitor_name . $visitor_email . $visitor_msg;  */
    
    // Validate inputs
    if ($fname == null || $lname == null || $reason == null || 
            $email == null || $comments == null) {
        $error = "Invalid input data. Check all fields and try again.";
        /* include('error.php'); */
        $error = "Invalid input data. Check all fields and try again.";
        /* include('error.php'); */
        echo "Form Data Error: " . $error; 
        exit();
        } else {
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

            // Add the product to the database  
            $query = 'INSERT INTO visit
                         (first_name, last_name, email_address, visit_reason, visit_msg, visit_date, employee_id)
                      VALUES
                         (:fname, :lname, :email, :reason, :comments, NOW(), 1)';
            $statement = $db->prepare($query);
            $statement->bindValue(':fname', $fname);
            $statement->bindValue(':lname', $lname);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':reason', $reason);
            $statement->bindValue(':comments', $comments);
            $statement->execute();
            $statement->closeCursor();
            /* echo "Fields: " . $fname . $lname . $reason . $comments;*/

}

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
     <h1>Message Sent</h1>
     <h3>Thank you, <?php echo $fname; ?>, for contacting us! </h3>
     <h3>We will get back to you shortly.</h3>
    </section>
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>

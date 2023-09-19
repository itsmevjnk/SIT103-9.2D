<?php

// display errors to webpage - helps with debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Personnel Management</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    </head>
    <body>
        <div class="container-fluid text-center bg-primary text-white p-5">
            <h1>Personnel Management</h1>
            <i>Written for the SIT103 9.2D task</i>
        </div>
        <?php require './db.php'; ?>
        <div class="container my-3">
            <h2>Add a new person</h2>
        </div>
        <div class="container my-3">
            <?php
            $query_result = $db_conn->query("SELECT personnel.PER_ID AS ID, CONCAT(PER_FNAME, ' ', PER_LNAME) AS FULL_NAME, authentication.AUTH_EMAIL AS EMAIL, PER_DOB AS DOB, PER_ADDRESS AS ADDR, PER_PHONE AS PHONE, PER_ID_NUMBER AS DOC_ID, PER_HI_ID AS HI_ID, student.PER_ID AS STU_ID, staff.PER_ID AS STF_ID FROM personnel LEFT OUTER JOIN student ON student.PER_ID = personnel.PER_ID LEFT OUTER JOIN staff ON staff.PER_ID = personnel.PER_ID LEFT OUTER JOIN authentication ON authentication.PER_ID = personnel.PER_ID");
            ?>
            <h2>Personnel list (<?php echo $query_result->num_rows; ?>)</h2>
            <?php
            if($query_result->num_rows == 0) echo 'Nothing to show here...';
            else {
            ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Date of Birth</th>
                        <th scope="col">Address</th>
                        <th scope="col">Phone</th>
                        <th scope="col">ID Document #</th>
                        <th scope="col">Insurance ID #</th>
                        <th scope="col">Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($row = $query_result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<th scope="row">' . $row['ID'] . '</th>';
                        if($row['EMAIL'] != null)
                            echo '<td><a href="mailto:' . $row['EMAIL'] . '">'. $row['FULL_NAME'] . '</a></td>';
                        else
                            echo '<td>' . $row['FULL_NAME'] . '</td>';
                        echo '<td>' . $row['DOB'] . '</td>';
                        echo '<td>' . $row['ADDR'] . '</td>';
                        echo '<td>' . $row['PHONE'] . '</td>';
                        echo '<td>' . $row['DOC_ID'] . '</td>';
                        echo '<td>' . $row['HI_ID'] . '</td>';
                        echo '<td>';
                        if($row['STU_ID'] != null) echo 'Student';
                        else if($row['STF_ID'] != null) echo 'Staff';
                        else echo 'Undefined';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <?php
            }
            ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    </body>
</html>
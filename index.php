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
            <form  method="post">
                <fieldset class="row g-2">
                    <legend>Common information</legend>
                    <div class="col-md-5">
                        <label for="fname" class="form-label">First name</label>
                        <input type="text" class="form-control" name="fname" id="fname" placeholder="First name" required>
                    </div>
                    <div class="col-md-5">
                        <label for="lname" class="form-label">Last name</label>
                        <input type="text" class="form-control" name="lname" id="lname" placeholder="Last name" required>
                    </div>
                    <div class="col-md-2">
                        <label for="dob" class="form-label">Date of birth</label>
                        <input type="date" class="form-control" name="dob" id="dob" required>
                    </div>
                    <div class="col-md-9">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" id="address" placeholder="Address" required>
                    </div>
                    <div class="col-md-3">
                        <label for="phone" class="form-label">Phone number</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="04xxxxxxxx" required>
                    </div>
                    <div class="col-md-3">
                        <label for="id_type" class="form-label">ID type</label>
                        <select name="id_type" id="id_type" class="form-select">
                            <option selected>Passport</option>
                            <option>Driver Licence</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="id_num" class="form-label">ID document no.</label>
                        <input type="text" class="form-control" name="id_num" name="id_num" placeholder="ID document no." required>
                    </div>
                    <div class="col-md-3">
                        <label for="id_loc" class="form-label">ID issuer</label>
                        <input type="text" class="form-control" name="id_loc" name="id_loc" placeholder="ID document issuer" required>
                    </div>
                    <div class="col-md-3">
                        <label for="id_expiry" class="form-label">ID expiry date</label>
                        <input type="date" class="form-control" name="id_expiry" id="id_expiry" required>
                    </div>
                    <div class="col-md-4">
                        <label for="hi_provider" class="form-label">Health cover provider</label>
                        <input type="text" class="form-control" name="hi_provider" name="hi_provider" placeholder="HC provider" required>
                    </div>
                    <div class="col-md-4">
                        <label for="hi_num" class="form-label">Health cover ID</label>
                        <input type="text" class="form-control" name="hi_num" name="hi_num" placeholder="HC document no."required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Personnel type</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="per_type" id="per_type_stu" value="STU" checked>
                                <label for="per_type_stu" class="form-check-label">Student</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="per_type" id="per_type_stf" value="STF">
                                <label for="per_type_stf" class="form-check-label">Staff</label>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="row g-2" id="student">
                    <legend>Student-specific information</legend>
                    <div class="col-md-3">
                        <label for="stu_type" class="form-label">Student type</label>
                        <select name="stu_type" id="stu_type" class="form-select">
                            <option selected>Domestic</option>
                            <option>International</option>
                            <option>Exchange</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="stu_usi" class="form-label">USI (optional)</label>
                        <input type="text" class="form-control" name="stu_usi" id="stu_usi" placeholder="USI">
                    </div>
                    <div class="col-md-6">
                        <label for="stu_scholarship" class="form-label">Scholarship (optional)</label>
                        <input type="text" class="form-control" name="stu_scholarship" id="stu_scholarship" placeholder="Scholarship name">
                    </div>
                    <div class="col-md-6">
                        <label for="stu_course" class="form-label">Course</label>
                        <select name="stu_course" id="stu_course" class="form-select">
                            <?php
                            $query_result = $db_conn->query("SELECT CRS_CODE AS CODE, CRS_NAME AS NAME FROM course");
                            if($query_result->num_rows > 0) {
                                while($row = $query_result->fetch_assoc()) {
                                    echo '<option value="' . $row['CODE'] . '">' . $row['CODE'] . ' - ' . $row['NAME'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="stu_campus" class="form-label">Campus</label>
                        <!-- to be filled by JavaScript -->
                        <select name="stu_cc" id="stu_campus" class="form-select"></select>
                    </div>
                    <div class="col-md-3">
                        <label for="stu_course_stat" class="form-label">Course status</label>
                        <select name="stu_course_stat" id="stu_course_stat" class="form-select">
                            <option>Not enrolled</option>
                            <option selected>Enrolled</option>
                            <option>Intermitted</option>
                            <option>Discontinued</option>
                        </select>
                    </div>
                </fieldset>
                <fieldset class="row g-2" id="staff">
                    <legend>Staff-specific information</legend>
                    <div class="col-md-3">
                        <label for="stf_tfn" class="form-label">Tax File Number</label>
                        <input type="text" class="form-control required" name="stf_tfn" id="stf_tfn" placeholder="Tax File Number" required>
                    </div> 
                    <div class="col-md-6">
                        <label for="stf_dept" class="form-label">Department</label>
                        <select name="stf_dept" id="stf_dept" class="form-select">
                            <option value="" selected>None</option>
                            <?php
                            $query_result = $db_conn->query("SELECT DEPT_ID AS ID, DEPT_NAME AS NAME FROM department");
                            if($query_result->num_rows > 0) {
                                while($row = $query_result->fetch_assoc()) {
                                    echo '<option value="' . $row['ID'] . '">' . $row['NAME'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="stf_role" class="form-label">Role</label>
                        <input type="text" class="form-control required" name="stf_tfn" id="stf_tfn" placeholder="Role" required>
                    </div> 
                </fieldset>
                <div class="my-3 d-flex flex-row justify-content-evenly">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-danger">Reset</button>
                </div>
            </form>
        </div>
        <div class="container my-3">
            <?php
            $query_result = $db_conn->query("SELECT personnel.PER_ID AS ID, CONCAT(PER_FNAME, ' ', PER_LNAME) AS FULL_NAME, authentication.AUTH_EMAIL AS EMAIL, PER_DOB AS DOB, PER_ADDRESS AS ADDR, PER_PHONE AS PHONE, PER_ID_NUMBER AS DOC_ID, PER_HI_ID AS HI_ID, student.PER_ID AS STU_ID, staff.PER_ID AS STF_ID FROM personnel LEFT OUTER JOIN student ON student.PER_ID = personnel.PER_ID LEFT OUTER JOIN staff ON staff.PER_ID = personnel.PER_ID LEFT OUTER JOIN authentication ON authentication.PER_ID = personnel.PER_ID ORDER BY personnel.PER_ID DESC");
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
        <script>
        
        </script>
    </body>
</html>
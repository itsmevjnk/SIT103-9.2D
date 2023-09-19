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
        <?php
        /* further SQL format the string (i.e. add quotes if it's not empty, or NULL if it's empty) */
        function sql_fmt($str) {
            return (($str == '') ? 'NULL' : "'$str'");
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            /* POST request */
            
            // echo '<div class="alert alert-primary container my-3" role="alert">';
            // var_dump($_POST);
            // echo '</div>';

            /* sanitise input data */
            $fname = sql_fmt($db_conn->real_escape_string($_POST['fname']));
            $lname = sql_fmt($db_conn->real_escape_string($_POST['lname']));
            $dob = sql_fmt($db_conn->real_escape_string($_POST['dob']));
            $address = sql_fmt($db_conn->real_escape_string($_POST['address']));
            $phone = sql_fmt($db_conn->real_escape_string($_POST['phone']));
            $id_type = sql_fmt($db_conn->real_escape_string($_POST['id_type']));
            $id_num = sql_fmt($db_conn->real_escape_string($_POST['id_num']));
            $id_loc = sql_fmt($db_conn->real_escape_string($_POST['id_loc']));
            $id_expiry = sql_fmt($db_conn->real_escape_string($_POST['id_expiry']));
            $hi_provider = sql_fmt($db_conn->real_escape_string($_POST['hi_provider']));
            $hi_num = sql_fmt($db_conn->real_escape_string($_POST['hi_num']));

            try {
                /* insert generic personnel information */
                if($db_conn->query("INSERT INTO personnel (PER_FNAME, PER_LNAME, PER_DOB, PER_ADDRESS, PER_PHONE, PER_ID_TYPE, PER_ID_NUMBER, PER_ID_LOCATION, PER_ID_EXPIRY, PER_HI_PROVIDER, PER_HI_ID) VALUES ($fname, $lname, $dob, $address, $phone, $id_type, $id_num, $id_loc, $id_expiry, $hi_provider, $hi_num)") !== TRUE)
                    echo '<div class="alert alert-danger container my-3" role="alert">Cannot insert personnel information: ' . $db_conn->error . '</div>';
                else {
                    /* retrieve the added record's ID */
                    $query_result = $db_conn->query("SELECT PER_ID FROM personnel WHERE PER_FNAME = $fname AND PER_LNAME = $lname AND PER_DOB = $dob AND PER_PHONE = $phone ORDER BY PER_ID DESC LIMIT 1"); // also use a number of other fields to get a better filtering effect
                    if($query_result->num_rows > 0) {
                        $id = $query_result->fetch_assoc()['PER_ID'];

                        /* insert specific information */
                        $success = false;                
                        if($_POST['per_type'] == 'STU') {
                            /* insert student information */

                            $stu_type = sql_fmt($db_conn->real_escape_string($_POST['stu_type']));
                            $stu_usi = sql_fmt($db_conn->real_escape_string($_POST['stu_usi']));
                            $stu_scholarship = sql_fmt($db_conn->real_escape_string($_POST['stu_scholarship']));
                            $stu_cc = $db_conn->real_escape_string($_POST['stu_cc']);
                            $stu_course_stat = sql_fmt($db_conn->real_escape_string($_POST['stu_course_stat']));

                            $success = ($db_conn->query("INSERT INTO student (PER_ID, STU_TYPE, STU_USI, STU_SCHOLARSHIP, CC_ID, STU_COURSE_STAT) VALUES ($id, $stu_type, $stu_usi, $stu_scholarship, $stu_cc, $stu_course_stat)") === TRUE);
                        } else {
                            /* insert staff information */

                            $stf_tfn = sql_fmt($db_conn->real_escape_string(($_POST['stf_tfn'])));
                            $stf_dept = $db_conn->real_escape_string($_POST['stf_dept']);
                            $stf_role = sql_fmt($db_conn->real_escape_string(($_POST['stf_role'])));

                            $success = ($db_conn->query("INSERT INTO staff (PER_ID, STF_TFN, DEPT_ID, STF_ROLE) VALUES ($id, $stf_tfn, $stf_dept, $stf_role)") === TRUE);
                        }
                        
                        if($success == true)
                            echo '<div class="alert alert-success container my-3" role="alert">Record has been inserted into the database.</div>';
                        else
                            echo '<div class="alert alert-danger container my-3" role="alert">Cannot insert personnel information: ' . $db_conn->error . '</div>';
                    } else echo '<div class="alert alert-danger container my-3" role="alert">Personnel information readback failed</div>';
                }
            } catch(Exception $e) {
                echo '<div class="alert alert-danger container my-3" role="alert">Cannot insert personnel information: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <div class="container my-3">
            <h2>Add a new person</h2>
            <form method="post" novalidate>
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
                <fieldset class="row g-2 d-none" id="staff" disabled>
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
                        <input type="text" class="form-control required" name="stf_role" id="stf_role" placeholder="Role" required>
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
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
        <script>
        const campuses = new Map([
            <?php
            $query_result = $db_conn->query("SELECT CP_ID AS ID, CP_NAME AS NAME FROM campus");
            if($query_result->num_rows > 0) {
                while($row = $query_result->fetch_assoc()) {
                    echo '[' . $row['ID'] . ', \'' . $row['NAME'] . '\'],';
                }
            }
            ?>
        ]);

        const course_campuses = {
            <?php
            $course_query = $db_conn->query("SELECT CRS_CODE AS CODE FROM course");
            if($course_query->num_rows > 0) {
                while($course = $course_query->fetch_assoc()) {
                    echo '\'' . $course['CODE'] . '\': new Map([';
                    $campus_query = $db_conn->query("SELECT CC_ID, CP_ID FROM course_campus WHERE CRS_CODE = '" . $course['CODE'] . "'");
                    if($campus_query->num_rows > 0) {
                        while($campus = $campus_query->fetch_assoc()) {
                            echo '[' . $campus['CP_ID'] . ', ' . $campus['CC_ID'] . '],';
                        }
                    }
                    echo ']),';
                }
            }
            ?>
        };

        function update_type() {
            /* toggle between student and staff specific fields */
            if($('input[name=per_type]:checked')[0].value == 'STU') {
                $('#student').removeAttr('disabled');
                $('#student').removeClass('d-none');
                $('#staff').attr('disabled', 'disabled');
                $('#staff').addClass('d-none');
            } else {
                $('#staff').removeAttr('disabled');
                $('#staff').removeClass('d-none');
                $('#student').attr('disabled', 'disabled');
                $('#student').addClass('d-none');
            }
        }
        
        function populate_campus() {
            /* populate campus menu */
            $('#stu_campus').empty();
            course_campuses[$('#stu_course')[0].value].forEach(function(value, key, map) {
                $('#stu_campus').append('<option value="' + value + '">' + campuses.get(key) + '</option>');
            });
        }

        $('input[name=per_type]').change(update_type);
        $('#stu_course').change(populate_campus);

        $(document).ready(function() {
            update_type();
            populate_campus();
        });
        </script>
    </body>
</html>
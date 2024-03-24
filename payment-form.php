<?php
session_start(); // Start the session at the very beginning

// Initialize message variables
$message = '';
$alertType = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Include the database configuration file
    require_once 'dbconfig.php';

    // Check if the terms checkbox is checked
    if (isset($_POST['terms'])) {
        // Sanitize and retrieve the form data
        $student_id = filter_var($_POST['student-id'], FILTER_SANITIZE_NUMBER_INT);
        $student_name = filter_var($_POST['student-name'], FILTER_SANITIZE_STRING);
        $student_email = filter_var($_POST['student-email'], FILTER_SANITIZE_EMAIL);
        $semester = filter_var($_POST['semester'], FILTER_SANITIZE_STRING);
        $year = filter_var($_POST['year'], FILTER_SANITIZE_NUMBER_INT);
        $program = filter_var($_POST['program'], FILTER_SANITIZE_STRING);
        $mobile_number = filter_var($_POST['mobile-number'], FILTER_SANITIZE_STRING);
        $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
        $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
        $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $payment_type = filter_var($_POST['payment-type'], FILTER_SANITIZE_STRING);

        // Prepare the SQL statement
        $query = "INSERT INTO admission (student_id, student_name, student_email, semester, year, program, mobile_number, city, address, amount, payment_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($query)) { // -> arrow operator
            // Bind parameters to the prepared statement
            $stmt->bind_param("isssissssds", $student_id, $student_name, $student_email, $semester, $year, $program, $mobile_number, $city, $address, $amount, $payment_type);

            
            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['message'] = 'Payment was successful.';
                $_SESSION['alertType'] = 'success';
            } else {
                $_SESSION['message'] = "Error inserting record: " . $stmt->error;
                $_SESSION['alertType'] = 'error';
            }

            // Close the statement
            $stmt->close();
        } else {
            $_SESSION['message'] = "Error preparing statement: " . $conn->error;
            $_SESSION['alertType'] = 'error';
        }

        // Close the database connection
        $conn->close();

        // Redirect to the same page to prevent form resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['message'] = "You must agree to the Terms & Conditions.";
        $_SESSION['alertType'] = 'warning';
    }
}

// Check session for messages
if (isset($_SESSION['message']) && isset($_SESSION['alertType'])) {
    $message = $_SESSION['message'];
    $alertType = $_SESSION['alertType'];

    // Clear the message from the session
    unset($_SESSION['message']);
    unset($_SESSION['alertType']);
}


?>

    <title>Online Payment Form</title>
    <link rel="stylesheet" href="css/css.css">
<body>
    <div class="form-container">
        <div class="header">
            <img src="img/CIULogo.png" alt="CIU Logo">
            <h1>Chittagong Independent University</h1>
            <p>Minhaj Complex, 12, Jamal Khan Road, Chattogram 4203<br>
            Phone: +88 031 622946, 623233, 623232, 626582, Fax: +88 031 611262</p>
        </div>
        <h2>Online Payment Form</h2>
        <form action="#" method="post">
            <label for="student-id">Student ID:</label>
            <input type="text" id="student-id" name="student-id" placeholder="Student ID">
            
            <label for="student-name">Student Name:</label>
            <input type="text" id="student-name" name="student-name" placeholder="Student Name">
            
            <label for="student-email">Student Email:</label>
            <input type="email" id="student-email" name="student-email" placeholder="someone@mail.com">
            
            <label for="semester">Semester:</label>
            <select id="semester" name="semester">
                <option>Select Semester</option>
                <option>Autumn</option>
                <option>Spring</option>
                <option>Summer</option>
                
            </select>
            
            <label for="year">Year:</label>
            <select id="year" name="year">
                <option value="2031">2031</option>
                <option value="2030">2030</option>
                <option value="2029">2029</option>
                <option value="2028">2028</option>
                <option value="2027">2027</option>
                <option value="2026">2026</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
                <option value="2020">2020</option>
                <option value="2019">2019</option>
                <option value="2018">2018</option>
                <option value="2017">2017</option>
                <option value="2016">2016</option>
                <option value="2015">2015</option>
                
            </select>
            
            <label for="program">Program:</label>
            <select id="program" name="program">
                <option>Select Program</option>
                <option>Undergraduate</option>
                <option>Graduate</option>
                
            </select>
            
            <label for="mobile-number">Mobile Number:</label>
            <input type="tel" id="mobile-number" name="mobile-number" placeholder="Mobile Number">
            
            <label for="city">City:</label>
            <input type="text" id="city" name="city" placeholder="Current City">
            
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Current Address">
            
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" placeholder="Amount">
            
            <label for="payment-type">Payment Type:</label>
            <select id="payment-type" name="payment-type">
                <option>Select Payment Type</option>
                <option>Tuition Fee</option>
                <option>Transcrip-Student Copy @ 100 tk</option>
                <option>Transcrip-Office Copy @ 500 tk</option>
               
            </select>
            
            <div class="terms">
                <input type="checkbox" id="terms" name="terms">
                <label for="terms">I agree to the Terms & Conditions, Refund Policy and Privacy Policy</label>
            </div>
            
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>
 <!-- Display message if set -->
 <?php if (!empty($message) && !empty($alertType)): ?>
        <script type="text/javascript">
            alert('<?php echo $message; ?>');
        </script>
    <?php endif; ?>
</body>
</html>

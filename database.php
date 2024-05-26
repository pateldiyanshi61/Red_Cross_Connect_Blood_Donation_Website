<?php
// Database connection parameters
$hostname = "YOUR_HOST_NAME";
$username = "YOUR_USER_NAME";
$password = "YOUR_PASSWORD";
$database = "DATABASE_NAME";

// Create a database connection
$connection = mysqli_connect($hostname, $username, $password, $database);

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $blood_type = $_POST["blood-type"];
    $city = $_POST["city"];
    $state = $_POST["state"];

    if (empty($name)) {
        $nameError = "Name is required";
        echo "$nameError";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $nameError = "Only letters and white spaces are allowed";
        echo "$nameError";
    }
    if (empty($email)) {
        $emailError = "Email is required";
        echo "$emailError";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format";
        echo "$emailError";
    }
    if (empty($phone)) {
        $phoneError = "Phone number is required";
        echo "$phoneError";
    } elseif (!preg_match("/^\d{10}$/", $phone)) {
        $phoneError = "Invalid phone number format (10 digits)";
        echo "$phoneError";
    }

    // Insert data into the "donor" table
    $sql = "INSERT INTO donor(name, phone, email, blood_type, city, state) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $phone, $email, $blood_type, $city, $state);

    if (mysqli_stmt_execute($stmt)) {
        echo "Data inserted successfully.";
    } else {
        echo "Error: " . mysqli_error($connection);
    }

    // Close the statement and the database connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}
?>

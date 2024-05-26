<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form inputs
    $email = isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    $servername = "YOUR_SERVER_NAME";
    $username = "YOUR_USERNAME";
    $dbpassword = "YOUR_DATABASE_PASSWORD";
    $dbname = "DATABASE_NAME";
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT password FROM registered_users WHERE email = ?");
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, set session or do any other action
            $_SESSION["loggedin"] = true;
            $_SESSION["email"] = $email;
            // Check if "Remember Me" is checked
            if (isset($_POST["remember_me"]) && $_POST["remember_me"] === "1") {
                $cookie_name = "remember_me_cookie";
                $cookie_value = $email;
                $cookie_expiry = time() + (86400 * 30); // 30 days (86400 seconds per day)
                setcookie($cookie_name, $cookie_value, $cookie_expiry, "/");
            }
            // Redirect the user to the dashboard or any other page
            header("Location: index.html");
            exit();
        } else {
            echo '<script>alert("Incorrect email or password. Please try again.");</script>';
        }
    } else {
        echo '<script>alert("Incorrect email or password. Please try again.");</script>';
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Red Cross Connect</title>

  <link rel="stylesheet" href="./assets/css/style.css">

  <style>
    hr {
      border: none;
      height: 1px;
      background-color: #c5c7c9;
      margin: 20px 0;
    }

    .form-container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #f9f9f9;
      border-radius: 10px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-title {
      color: rgb(14, 37, 78);
      font-family: "Poppins", sans-serif;
      font-size: 3.4rem;
      font-weight: 800;
      text-align: center;
      margin-bottom: 20px;
    }

    .form-section {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    .form-field {
      flex: 0 0 48%;
      margin-bottom: 20px;
    }

    .form-field label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form-field input,
    .form-field select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .form-field input[type="submit"] {
      background-color: #216aca;
      color: #fff;
      cursor: pointer;
    }

    video{
      display: block;
      height: auto;
      width: 100%;
    }
  </style>

</head>

<body>
<header class="header">
  <div class="header-top">
      <div class="container">
        <ul class="contact-list">
          <li class="contact-item">
            <ion-icon name="mail-outline"></ion-icon>
            <a href="mailto:redcrossconnect78@gmail.com" class="contact-link">redcrossconnect78@gmail.com</a>
          </li>
          <li class="contact-item">
            <ion-icon name="call-outline"></ion-icon>
            <a href="tel:+913123456897" class="contact-link">+91-3123-456-897</a>
          </li>
        </ul>
      </div>
    </div>
    <div class="header-bottom" data-header>
      <div class="container">
        <a href="#" class="logo">Red Cross Connect</a>
        <nav class="navbar container" data-navbar>
          <ul class="navbar-list">
            <li>
              <a href="index.html" class="navbar-link" data-nav-link>Home</a>
            </li>
            <li>
              <a href="index.html#service" class="navbar-link" data-nav-link>Find donor</a>
            </li>
            <li>
              <a href="about.html" class="navbar-link" data-nav-link>About Us</a>
            </li>
            <li>
              <a href="contact.html" class="navbar-link" data-nav-link>Contact</a>
            </li>
          </ul>
        </nav>
        <a href="register.php" class="btn">Login / Register</a>
        <button class="nav-toggle-btn" aria-label="Toggle menu" data-nav-toggler>
          <ion-icon name="menu-sharp" aria-hidden="true" class="menu-icon"></ion-icon>
          <ion-icon name="close-sharp" aria-hidden="true" class="close-icon"></ion-icon>
        </button>
      </div>
    </div>
  </header>

  <main>
    <article>
      <section class="section hero" id="home" style="background-image: url('./assets/images/mainbg.jpg'); margin: 0%;"
        aria-label="hero">
        <div class="container">
          <div class="form-container">
            <div class="form-title">Login</div>
            <hr><br><br>
            <form action="" method="POST">
              <div class="form-section">
                <div class="form-field">
                  <label for="email">Email:</label>
                  <input type="email" id="email" name="email" required>
                </div>
                <div class="form-field">
                  <label for="password">Password:</label>
                  <input type="password" id="password" name="password" required>
                </div>
                <div class="form-field">
                <label for="remember_me">Remember Me:</label>
                <input type="checkbox" id="remember_me" name="remember_me" value="1">
                </div>
              </div><br>
              <button type="submit" class="btn">Login</button>
            </form><br><br>
            <div class="form-title">Not Registered? <u><a href="register.php" style="display: inline; color: #216aca;" onmouseover="this.style.color='#03d9ff'" onmouseout="this.style.color='#216aca'">Register Here</a></u></div>
          </div>
          <video alt="hero banner" class="w-100" autoplay loop>
            <source src="./assets/images/bg2.mp4"  type="video/mp4">
            <source src="./assets/images/bg2.ogg"  type="video/ogg">
          </video>
        </div>
      </section>
    </article>
  </main>
  <footer class="footer">
    <div class="footer-top section">
      <div class="container">
        <div class="footer-brand">
          <a href="#" class="logo">Red Cross Connect</a>
          <p class="footer-text">
            We are passionate about connecting blood donors with recipients and bridging the gap in the healthcare
            industry. We strive to create a community that fosters empathy, support, and solidarity among 
            individuals who are committed to making a difference.
          </p>
          <div class="schedule">
            <div class="schedule-icon">
              <ion-icon name="time-outline"></ion-icon>
            </div>
            <span class="span">
              24 X 7:<br>
              365 Days
            </span>
          </div>
        </div>
        <ul class="footer-list">
          <li>
            <p class="footer-list-title">Other Links</p>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">Home</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">Find donor</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">About us</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">Contact</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">Login / Register</span>
            </a>
          </li>
        </ul>
        <ul class="footer-list">
          <li>
            <p class="footer-list-title">Our Services</p>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">xxxxxxxxx</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">xxxxxxxxx</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">xxxxxxxxx</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">xxxxxxxxx</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">xxxxxxxxx</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">xxxxxxxxx</span>
            </a>
          </li>
        </ul>
        <ul class="footer-list">
          <li>
            <p class="footer-list-title">Contact Us</p>
          </li>
          <li class="footer-item">
            <div class="item-icon">
              <ion-icon name="location-outline"></ion-icon>
            </div>
            <a href="https://maps.app.goo.gl/YCna81aBf5TxU6GfA" target="_blank">
            <address class="item-text">
              Knowledge Corridor, Raisan <br>
              Village, PDPU Rd, Gandhinagar,<br>
              Gujarat 382007
            </address>
          </a>
          </li>
          <li class="footer-item">
            <div class="item-icon">
              <ion-icon name="call-outline"></ion-icon>
            </div>
            <a href="tel:+913123456897" class="footer-link">+91-3123-456-879</a>
          </li>
          <li class="footer-item">
            <div class="item-icon">
              <ion-icon name="mail-outline"></ion-icon>
            </div>
            <a href="mailto:redcrossconnect78@gmail.com" class="footer-link">redcrossconnect78@gmail.com</a>
          </li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <p class="copyright">
          &copy; 2024 All Rights Reserved by Red Cross Connect
        </p>
      </div>
    </div>
  </footer>

  <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
    <ion-icon name="caret-up" aria-hidden="true"></ion-icon>
  </a>

  <script src="./assets/js/script.js" defer></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>

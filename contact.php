<?php
  function is_valid_email($email) {
      return preg_match('/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/', $email);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = $_REQUEST["email_address"];
      
      if (is_valid_email($email)) {
          echo "Valid Email: $email , we will reach to you soon";
      } else {
          echo "Invalid Email: $email";
      }
  }
?>

  
<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $fullName = $_POST['fullname'];
    $telephone = $_POST['telephone'];

    try {
        $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, telephone, email) VALUES (:username, :password, :fullname, :telephone, :email)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':fullname', $fullName);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            session_start();
            //$_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            $_SESSION['fullname'] = $fullName;
            $_SESSION['telephone'] = $telephone;
            $_SESSION['email'] = $email;
            // Generate a random session key
            $sessionKey = bin2hex(random_bytes(16));

            // Set session expiration time to 1 hour from now
            $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

            

            // Set the session key as a cookie
            setcookie('session_key', $sessionKey, strtotime('+1 hour'), '/');

            header("Location: ../profile.php");

        echo "Registration successful!";
    }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
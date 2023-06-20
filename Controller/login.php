<?php
require_once '../config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    var_dump($username, $password);
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        if ($stmt->rowCount() >= 1) {
            session_start();
            $row = $stmt->fetchall(PDO::FETCH_ASSOC);
            $_SESSION['user_id'] = $row[0]['id'];
            $_SESSION['username'] = $row[0]['username'];

            // Generate a random session key
            $sessionKey = bin2hex(random_bytes(16));

            // Set session expiration time to 1 hour from now
            $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

            

            // Set the session key as a cookie
            setcookie('session_key', $sessionKey, strtotime('+1 hour'), '/');

            header("Location: ../profile.php");
        } else {
            //header("Location: ../login.php");
            echo "Invalid username or password";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'user_system');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, password, user_type FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password, $user_type);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        session_start();
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['user_type'] = $user_type;

        switch ($user_type) {
            case 'user1':
                header('Location: userAdmin.php');
                break;
            case 'user2':
                header('Location: userRT.php');
                break;
            case 'user3':
                header('Location: userWarga.php');
                break;
            default:
                echo 'Invalid user type';
                break;
        }
    } else {
        echo 'Invalid username or password';
    }

    $stmt->close();
}

$conn->close();
?>
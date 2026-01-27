<?php
session_start();

// 1. Clear all session variables
$_SESSION = array();

// 2. Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Destroy the session
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logging Out...</title>

    <!-- Bootstrap + SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.85)),
                url('https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }
    </style>
</head>
<body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    Swal.fire({
        icon: 'success',
        title: 'Logged Out',
        text: 'You have been logged out successfully.',
        timer: 1500,
        showConfirmButton: false,
        background: '#fff',
        heightAuto: false
    }).then(() => {
        window.location.href = 'index.php';
    });
</script>

</body>
</html>

<?php session_start(); ?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Style for error messages */
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .input-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
        }

  /* Basic Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Khmer OS', Arial, sans-serif; /* Using a Khmer-compatible font */
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f4f4f9; /* Light gray for a soft background */
}

/* Wrapper for Form */
.wrapper {
    width: 100%;
    max-width: 450px;
    padding: 40px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Slight shadow for depth */
    text-align: center;
    position: relative;
}

/* Logo Styling */
.logo-container img {
    width: 120px;
    height: auto;
    margin-top: -40px;
}

/* Form Title */
form h1 {
    font-size: 22px;
    font-weight: 600;
    color: #2b2b2b;
    margin-bottom: 20px;
    color: #388e3c; /* Matches the green in the logo */
}

/* Input Container */
.input-container {
    position: relative;
    margin-bottom: 20px;
}

/* Input Fields */
.input-container input {
    width: 100%;
    padding: 12px 45px 12px 15px; /* Spacing to avoid icon overlap */
    font-size: 15px;
    color: #333;
    border: 1px solid #d1d1d1;
    border-radius: 5px;
    transition: all 0.3s;
    background-color: #f9f9f9; /* Soft background for inputs */
}

.input-container input:focus {
    border-color: #388e3c; /* Green border on focus */
    box-shadow: 0 0 5px rgba(56, 142, 60, 0.3); /* Green shadow on focus */
    outline: none;
}

/* Icons Inside Input Fields */
.input-container i {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #888;
    font-size: 18px;
}

/* Submit Button */
.btn {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    color: #fff;
    background-color: #1c815c;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #145f46;
}

/* Footer Text */
.wrapper .footer-text {
    margin-top: 20px;
    font-size: 13px;
    color: #666;
}

    </style>
</head>
<body>
    <div class="wrapper">
        <div class="logo-container">
            <img src="../img/logo.png" alt="KSIT Logo">
        </div>
        <form action="login_process.php" method="post">
            <h1>ប្រព័ន្ធគ្រប់គ្រងអន្តេវាសិកដ្ឋានសម្រាប់វិទ្យាស្ថានបច្ចេកវិទ្យាកំពង់ស្ពឺ</h1>

            <div class="input-container">
                <input type="text" class="form-control" id="username" name="username" placeholder="USERNAME" required>
                <i class='bx bxs-user'></i>
                <?php if (isset($_SESSION['username_error'])): ?>
                    <div class="error-message"><?php echo $_SESSION['username_error']; unset($_SESSION['username_error']); ?></div>
                <?php endif; ?>
            </div>
            <div class="input-container">
                <input type="password" id="password" placeholder="ពាក្យសម្ងាត់" name="password" required>
                <i class='bx bx-show toggle-password' onclick="togglePassword()"></i>
                <?php if (isset($_SESSION['password_error'])): ?>
                    <div class="error-message"><?php echo $_SESSION['password_error']; unset($_SESSION['password_error']); ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn">ចូលគណនី</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const toggleIcon = document.querySelector(".toggle-password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.replace("bx-show", "bx-hide");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.replace("bx-hide", "bx-show");
            }
        }
    </script>
</body>
</html>

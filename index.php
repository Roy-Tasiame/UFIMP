<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ashesi Forms Platform</title>
    <style>
        :root {
            --maroon: #800000;
            --black: #000000;
            --white: #ffffff;
            --gray: #f5f5f5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: var(--gray);
            background-image: url('assets/img/bg.png'); 
            background-size: cover;
            background-position: center; 
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        .tabs {
            display: flex;
            margin-bottom: 2rem;
        }

        .tab {
            flex: 1;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 1.1rem;
            color: #666;
            border-bottom: 2px solid transparent;
        }

        .tab.active {
            color: var(--maroon);
            border-bottom: 2px solid var(--maroon);
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo img {
            width: 150px;
            height: auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--maroon);
        }

        .error {
            color: red;
            font-size: 0.9rem;
            margin-top: 0.3rem;
            display: none;
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background-color: var(--maroon);
            color: var(--white);
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #600000;
        }

        #signup-form {
            display: none;
        }

        @media (max-width: 480px) {
            .container {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="assets/img/ashesilogo.png" alt="Ashesi University Logo">
        </div>
        
        <div class="tabs">
            <button class="tab active" onclick="showForm('login')">Login</button>
            <button class="tab" onclick="showForm('signup')">Sign Up</button>
        </div>

        <form id="login-form" action = "actions/login_action.php" method = "post">
            <div class="form-group">
                <label for="login-email">Email</label>
                <input type="email" id="login-email" name  = "email" required>
                <div class="error" id="login-email-error">Please enter a valid email address</div>
            </div>
            <div class="form-group">
                <label for="login-password">Password</label>
                <input type="password" id="login-password" name = "password" required>
                <div class="error" id="login-password-error">Password is required</div>
            </div>
            <button type="submit" class="submit-btn">Login</button>
        </form>

        <form id="signup-form" action = "actions/register_action.php" method = "post">
            <div class="form-group">
                <label for="signup-name">Full Name</label>
                <input type="text" id="signup-name" name = "fullname" required>
                <div class="error" id="signup-name-error">Please enter your full name</div>
            </div>
            <div class="form-group">
                <label for="signup-email">Email</label>
                <input type="email" id="signup-email" name = "email" required>
                <div class="error" id="signup-email-error">Please enter a valid email address</div>
            </div>
            <div class="form-group">
                <label for="signup-password">Password</label>
                <input type="password" id="signup-password" name = "password" required>
                <div class="error" id="signup-password-error">Password must be at least 8 characters</div>
            </div>
            <div class="form-group">
                <label for="signup-confirm-password">Confirm Password</label>
                <input type="password" id="signup-confirm-password" name = "confirm_password" required>
                <div class="error" id="signup-confirm-password-error">Passwords do not match</div>
            </div>
            <button type="submit" class="submit-btn">Sign Up</button>
        </form>
    </div>

    <script src="assets/js/script.js">

    </script>
</body>
</html>
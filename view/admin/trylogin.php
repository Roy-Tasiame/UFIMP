<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ashesi Forms Platform - Login/Signup</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --maroon: #800000;
            --maroon-light: #a06666;
            --black: #000000;
            --white: #ffffff;
            --gray: #f8f9fa;
            --input-bg: #f8f9fa;
            --shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', 'Arial', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--maroon) 0%, #4a0000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .container {
            background-color: var(--white);
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
            padding: 2.5rem;
            position: relative;
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
        }

        .tabs {
            display: flex;
            margin-bottom: 2.5rem;
            border-radius: 12px;
            background-color: var(--input-bg);
            padding: 0.5rem;
            position: relative;
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
            position: relative;
            transition: all 0.3s ease;
            border-radius: 8px;
            font-weight: 500;
            z-index: 1;
        }

        .tab.active {
            color: var(--white);
        }

        .tab-indicator {
            position: absolute;
            height: calc(100% - 1rem);
            width: calc(50% - 1rem);
            background-color: var(--maroon);
            border-radius: 8px;
            left: 0.5rem;
            top: 0.5rem;
            transition: transform 0.3s ease;
        }

        .logo {
            text-align: center;
            margin-bottom: 2.5rem;
            padding: 1rem;
        }

        .logo img {
            width: 180px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.05);
        }

        .form-group {
            margin-bottom: 1.8rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.8rem;
            color: #333;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .form-group input {
            width: 100%;
            padding: 1rem 1rem 1rem 2.8rem;
            border: 2px solid transparent;
            border-radius: 10px;
            font-size: 1rem;
            background-color: var(--input-bg);
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--maroon);
            background-color: var(--white);
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.1);
        }

        .error {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: none;
            padding-left: 0.5rem;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .error.visible {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .submit-btn {
            width: 100%;
            padding: 1.2rem;
            background-color: var(--maroon);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            background-color: #600000;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(128, 0, 0, 0.2);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        #signup-form {
            display: none;
            animation: fadeOut 0.3s ease;
        }

        #signup-form.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        #login-form {
            animation: fadeIn 0.3s ease;
        }

        #login-form.hidden {
            display: none;
            animation: fadeOut 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(-20px);
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }
            
            .container {
                padding: 1.5rem;
            }

            .form-group {
                margin-bottom: 1.2rem;
            }

            .submit-btn {
                padding: 1rem;
            }
        }

        /* Form success animation */
        .success-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--maroon);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .success-animation.active {
            opacity: 1;
        }

        .checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            stroke-width: 2;
            stroke: #fff;
            stroke-miterlimit: 10;
            box-shadow: inset 0 0 0 var(--maroon);
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }

        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #fff;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes scale {
            0%, 100% {
                transform: none;
            }
            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        @keyframes fill {
            100% {
                box-shadow: inset 0 0 0 100px var(--maroon);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../assets/img/ashesilogo.png" alt="Ashesi University Logo">
        </div>
        
        <div class="tabs">
            <div class="tab-indicator"></div>
            <button class="tab active" onclick="showForm('login')">Login</button>
            <button class="tab" onclick="showForm('signup')">Sign Up</button>
        </div>

        <form id="login-form">
            <div class="form-group">
                <label for="login-email">Email Address</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="login-email" placeholder="Enter your email" required>
                </div>
                <div class="error" id="login-email-error">Please enter a valid email address</div>
            </div>
            <div class="form-group">
                <label for="login-password">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="login-password" placeholder="Enter your password" required>
                </div>
                <div class="error" id="login-password-error">Password is required</div>
            </div>
            <button type="submit" class="submit-btn">
                Login
                <div class="success-animation">
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                </div>
            </button>
        </form>

        <form id="signup-form">
            <div class="form-group">
                <label for="signup-name">Full Name</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="signup-name" placeholder="Enter your full name" required>
                </div>
                <div class="error" id="signup-name-error">Please enter your full name</div>
            </div>
            <div class="form-group">
                <label for="signup-email">Email Address</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="signup-email" placeholder="Enter your email" required>
                </div>
                <div class="error" id="signup-email-error">Please enter a valid email address</div>
            </div>
            <div class="form-group">
                <label for="signup-department">Department</label>
                <div class="input-group">
                    <i class="fas fa-building"></i>
                    <input type="text" id="signup-department" placeholder="Enter your department" required>
                </div>
                <div class="error" id="signup-department-error">Please enter your department</div>
            </div>
            <div class="form-group">
                <label for="signup-password">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="signup-password" placeholder="Create a password" required>
                </div>
                <div class="error" id="signup-password-error">Password must be at least 8 characters</div>
            </div>
            <div class="form-group">
                <label for="signup-confirm-password">Confirm Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="signup-confirm-password" placeholder="Confirm your password" required>
                </div>
                <div class="error" id="signup-confirm-password-error">Passwords do not match</div>
            </div>
            <button type="submit" class="submit-btn">
                Create Account
                <div class="success-animation">
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                </div>
            </button>
        </form>
    </div>

    <script>
        function showForm(formType) {
            const loginForm = document.getElementById('login-form');
            const signupForm = document.getElementById('signup-form');
            const tabs = document.querySelectorAll('.tab');
            const indicator = document.querySelector('.tab-indicator');
            
            if (formType === 'login') {
                loginForm.classList.remove('hidden');
                signupForm.classList.remove('active');
                tabs[0].classList.add('active');
                tabs[1].classList.remove('active');
                indicator.style.transform = 'translateX(0)';
            } else {
                loginForm.classList.add('hidden');
                signupForm.classList.add('active');
                tabs[0].classList.remove('active');
                tabs[1].classList.add('active');
                indicator.style.transform = 'translateX(100%)';
            }
        }

        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function showError(elementId, show) {
            const error = document.getElementById(elementId);
            if (show) {
                error.classList.add('visible');
            } else {
                error.classList.remove('visible');
            }
        }

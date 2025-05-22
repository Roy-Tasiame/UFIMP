<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Ashesi Form Builder</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --maroon: #800020;
            --border-color: #ddd;
            --light-gray: #f8f9fa;
            --dark: #333;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 850px;
            margin: 40px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }
        
        .thank-you-header {
            background-color: var(--maroon);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        
        .thank-you-content {
            padding: 40px 20px;
            text-align: center;
        }
        
        .thank-you-icon {
            font-size: 80px;
            color: var(--maroon);
            margin-bottom: 20px;
        }
        
        .thank-you-title {
            font-size: 32px;
            margin-bottom: 20px;
            color: var(--dark);
        }
        
        .thank-you-message {
            font-size: 18px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .btn-primary {
            background-color: var(--maroon);
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .actions {
            margin-top: 20px;
        }
        
        .share-options {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .share-button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .share-button:hover {
            transform: scale(1.1);
        }
        
        .share-button.facebook {
            background-color: #3b5998;
        }
        
        .share-button.twitter {
            background-color: #1da1f2;
        }
        
        .share-button.linkedin {
            background-color: #0077b5;
        }
        
        .share-button.email {
            background-color: #666;
        }
        
        .email-form {
            margin-top: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            padding: 20px;
            background-color: var(--light-gray);
            border-radius: 8px;
            text-align: left;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--dark);
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--maroon);
            box-shadow: 0 0 0 2px rgba(128, 0, 32, 0.2);
        }
        
        .form-group .btn {
            margin-top: 10px;
        }
        
        .email-sent-message {
            display: none;
            color: green;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="thank-you-header">
            <h1>Ashesi Form Builder</h1>
        </div>
        
        <div class="thank-you-content">
            <div class="thank-you-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="thank-you-title">Thank You for Your Submission!</h2>
            <p class="thank-you-message">
                Your response has been successfully recorded. We appreciate your time and contribution.
                <br>
                If you have any questions or need further assistance, please don't hesitate to contact us.
            </p>
            
            <div class="actions">
                <a href="#" class="btn btn-primary">
                    <i class="fas fa-home"></i> Return to Homepage
                </a>
            </div>
            
            <div class="email-form">
                <h3>Want a copy of your responses?</h3>
                <p>Enter your email below to receive a copy of your submission.</p>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="yourname@example.com" required>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" onclick="sendResponseCopy()">
                        <i class="fas fa-paper-plane"></i> Send Copy
                    </button>
                </div>
                <div id="emailSentMessage" class="email-sent-message">
                    Email sent successfully! Please check your inbox.
                </div>
            </div>
            
            <div class="share-options">
                <div class="share-button linkedin">
                    <i class="fab fa-linkedin-in"></i>
                </div>
                <div class="share-button email">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle share button clicks
            document.querySelectorAll('.share-button').forEach(button => {
                button.addEventListener('click', function() {
                    const type = this.classList[1];
                    let url = '';
                    
                    switch(type) {
                        case 'linkedin':
                            url = 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(window.location.href);
                            break;
                        case 'email':
                            url = 'mailto:?subject=' + encodeURIComponent('Form Submission') + '&body=' + encodeURIComponent('I just submitted a form at ' + window.location.href);
                            break;
                    }
                    
                    if (url) {
                        window.open(url, '_blank');
                    }
                });
            });
        });
        
        function sendResponseCopy() {
            const email = document.getElementById('email').value;
            if (!email) {
                alert('Please enter a valid email address');
                return;
            }
            
            // Here you would typically make an AJAX request to your server
            // to send the email with the response data
            
            // For demonstration, we'll simulate a successful email sending
            setTimeout(() => {
                document.getElementById('emailSentMessage').style.display = 'block';
                document.getElementById('email').value = '';
                
                // Hide the success message after 5 seconds
                setTimeout(() => {
                    document.getElementById('emailSentMessage').style.display = 'none';
                }, 5000);
            }, 1000);
            
            // In a real application, you would send the data to your server like this:
            /*
            fetch('send_response_copy.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    formId: getFormIdFromUrl(), // You'd need to implement this function
                    responseId: getResponseIdFromUrl() // You'd need to implement this function
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('emailSentMessage').style.display = 'block';
                    document.getElementById('email').value = '';
                    
                    setTimeout(() => {
                        document.getElementById('emailSentMessage').style.display = 'none';
                    }, 5000);
                } else {
                    alert('Error sending email: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error sending email. Please try again later.');
            });
            */
        }
    </script>
</body>
</html>
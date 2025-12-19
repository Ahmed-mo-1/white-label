<?php
/**
 * Custom AJAX Login Page Template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | <?php bloginfo('name'); ?></title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

body {
    display: flex;
    width: 100%;
    height: 100dvh;
    overflow: hidden;
    background-color: #f7f7f7;
}

.design-container {
    width: 75%;
    background: url("<?php echo get_stylesheet_directory_uri(); ?>/imgs/1.jpg") center/cover;
    transition: width 0.3s ease;
}

.login-container {
    width: 25%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 20px;
    padding: 20px;
    background-color: #ffffff;
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.login-container h1 {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.login-container p {
    font-size: 14px;
    color: #666;
}

.login-container form {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 15px;
    width: 100%;
    max-width: 300px;
}

input {
    width: 100%;
    font-size: 16px;
    padding: 12px 18px;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    transition: border-color 0.2s, box-shadow 0.2s;
}

input:focus {
    outline: none;
    border-color: #3858e9;
    box-shadow: 0 0 0 3px rgba(56, 88, 233, 0.15);
}

input[type="submit"],
.dashboard-btn {
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    padding: 12px 16px;
    border-radius: 10px;
    border: none;
    background: #3858e9;
    color: white;
    box-shadow: 0 4px 10px rgba(56, 88, 233, 0.4);
    transition: background 0.2s ease, box-shadow 0.2s ease, transform 0.1s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

input[type="submit"]:hover,
.dashboard-btn:hover {
    background: #1838c9;
    box-shadow: 0 6px 15px rgba(56, 88, 233, 0.6);
    transform: translateY(-1px);
}

input[type="submit"]:disabled {
    background: #9ca3af;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
}

.message {
    width: 100%;
    max-width: 300px;
    padding: 12px 18px;
    border-radius: 8px;
    font-size: 14px;
    text-align: center;
    margin-bottom: 10px;
    animation: slideIn 0.3s ease;
}

.error {
    background-color: #ffe0e0;
    border: 1px solid #ff9999;
    color: #cc0000;
}

.success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.spinner {
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top: 3px solid white;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    animation: spin 0.8s linear infinite;
    display: inline-block;
    margin-left: 8px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.success-screen {
    display: none;
    text-align: center;
    max-width: 300px;
}

.success-screen.active {
    display: flex;
    flex-direction: column;
    gap: 20px;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.checkmark {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #d4edda;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    animation: scaleIn 0.5s ease;
}

@keyframes scaleIn {
    0% { transform: scale(0); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.checkmark svg {
    width: 40px;
    height: 40px;
    stroke: #28a745;
    stroke-width: 3;
    stroke-linecap: round;
    stroke-linejoin: round;
    fill: none;
    animation: drawCheck 0.5s ease 0.3s forwards;
    stroke-dasharray: 50;
    stroke-dashoffset: 50;
}

@keyframes drawCheck {
    to { stroke-dashoffset: 0; }
}

input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px white inset !important;
    box-shadow: 0 0 0 1000px white inset !important;
    -webkit-text-fill-color: #000 !important;
    transition: background-color 9999s ease-in-out 0s;
}

@media(max-width: 1000px) {
    body {
        overflow-y: auto;
    }

    .design-container {
        display: none;
    }

    .login-container {
        width: 100%;
        box-shadow: none;
    }
}
</style>
</head>

<body>
<div class="design-container"></div>

<div class="login-container">

    <div style="width: 80px; aspect-ratio: 1; margin-bottom: 10px;">
        <img style="width: 100%; height: auto; object-fit: contain;"
             src="<?php echo get_stylesheet_directory_uri(); ?>/imgs/1.webp"
             onerror="this.onerror=null;this.src='https://placehold.co/80x80/3858e9/ffffff?text=LOGO';"
             alt="Logo">
    </div>

    <!-- Login Form -->
    <div id="login-form-wrapper">
        <h1>Welcome Back</h1>
        <p>Sign in to access your dashboard.</p>

        <div id="message-container"></div>

        <form id="custom-login-form" method="post">
            <input type="text" id="username" name="username" placeholder="Username" required autocomplete="username">
            <input type="password" id="password" name="password" placeholder="Password" required autocomplete="current-password">
            
            <label style="align-self: flex-start; font-size: 14px; display: flex; align-items: center;">
                <input type="checkbox" name="remember" id="remember" value="true" style="width: auto; margin-right: 8px; border-radius: 3px; padding: 0;">
                Remember Me
            </label>

            <input type="submit" id="submit-btn" value="Log In">
            
            <a href="<?php echo wp_lostpassword_url(); ?>" style="font-size: 13px; color: #3858e9; text-decoration: none; margin-top: 10px;">
                Forgot Password?
            </a>
        </form>
    </div>

    <!-- Success Screen -->
    <div class="success-screen" id="success-screen">
        <div class="checkmark">
            <svg viewBox="0 0 52 52">
                <polyline points="14 27 22 35 38 19"/>
            </svg>
        </div>
        <h1 style="color: #28a745;">Login Successful!</h1>
        <p id="welcome-message">Welcome back!</p>
        <a href="<?php echo admin_url('/admin.php?page=modern-dark-report'); ?>" class="dashboard-btn">Enter Dashboard</a>
    </div>

</div>

<script>
document.getElementById('custom-login-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const messageContainer = document.getElementById('message-container');
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const remember = document.getElementById('remember').checked;
    
    // Disable submit button and show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Logging in<span class="spinner"></span>';
    messageContainer.innerHTML = '';
    
    // Prepare form data
    const formData = new FormData();
    formData.append('action', 'custom_ajax_login');
    formData.append('username', username);
    formData.append('password', password);
    formData.append('remember', remember);
    formData.append('nonce', '<?php echo wp_create_nonce("custom_login_action"); ?>');
    
    // Send AJAX request
    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            messageContainer.innerHTML = '<div class="message success">' + data.data.message + '</div>';
            
            // Hide login form and show success screen
            setTimeout(() => {
                document.getElementById('login-form-wrapper').style.display = 'none';
                document.getElementById('success-screen').classList.add('active');
                document.getElementById('welcome-message').textContent = 'Welcome back, ' + data.data.user_name + '!';
            }, 500);
            
        } else {
            // Show error message
            messageContainer.innerHTML = '<div class="message error">' + data.data.message + '</div>';
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Log In';
        }
    })
    .catch(error => {
        messageContainer.innerHTML = '<div class="message error">An error occurred. Please try again.</div>';
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Log In';
        console.error('Login error:', error);
    });
});
</script>

</body>
</html>
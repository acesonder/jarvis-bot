<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tweak Easy</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="theme-light auth-page">
    <div class="auth-container">
        <div class="auth-card register-card">
            <div class="auth-header">
                <a href="../index.html" class="auth-logo">
                    <svg viewBox="0 0 60 60" class="logo-svg" width="50" height="50">
                        <circle cx="30" cy="30" r="28" fill="url(#authLogoGradient)"/>
                        <path d="M20 30 L30 20 L40 30 L30 40 Z" fill="#fff" opacity="0.9"/>
                        <circle cx="30" cy="30" r="8" fill="#fff"/>
                        <defs>
                            <linearGradient id="authLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#667eea"/>
                                <stop offset="100%" style="stop-color:#764ba2"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <span>Tweak Easy</span>
                </a>
                <h1>Create Account</h1>
                <p>Join us to access harm reduction services</p>
            </div>
            
            <form id="registerForm" class="auth-form" method="POST" action="#" onsubmit="return handleRegister(event);">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name</label>
                        <div class="input-wrapper">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" class="input-icon">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                            </svg>
                            <input type="text" id="first_name" name="first_name" placeholder="First name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name</label>
                        <div class="input-wrapper">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" class="input-icon">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                            </svg>
                            <input type="text" id="last_name" name="last_name" placeholder="Last name" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" class="input-icon">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                        </svg>
                        <input type="text" id="username" name="username" placeholder="Choose a username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" class="input-icon">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number (Optional)</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" class="input-icon">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="role" class="form-label">I am a...</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" class="input-icon">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <select id="role" name="role" required>
                            <option value="">Select your role</option>
                            <option value="client">Client - Seeking harm reduction services</option>
                            <option value="outreach_worker">Outreach Worker - Providing field services</option>
                            <option value="service_provider">Service Provider - Healthcare/social services</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" class="input-icon">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                        <input type="password" id="password" name="password" placeholder="Create a password (min 8 characters)" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <span class="password-hint" id="strengthText">Password strength</span>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" class="input-icon">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="terms" required>
                        <span class="checkmark"></span>
                        I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Create Account
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                    </svg>
                </button>
            </form>
            
            <div class="auth-footer">
                <p>Already have an account? <a href="login.php">Sign in</a></p>
            </div>
        </div>
        
        <div class="auth-background">
            <div class="bg-shape shape-1"></div>
            <div class="bg-shape shape-2"></div>
            <div class="bg-shape shape-3"></div>
        </div>
    </div>
    
    <div class="theme-toggle" id="themeToggle">
        <span class="theme-icon">🌙</span>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
        
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            const colors = ['#f56565', '#ed8936', '#ecc94b', '#48bb78', '#38a169'];
            const texts = ['Very Weak', 'Weak', 'Fair', 'Strong', 'Very Strong'];
            
            strengthBar.style.width = (strength * 20) + '%';
            strengthBar.style.background = colors[strength - 1] || '#e2e8f0';
            strengthText.textContent = texts[strength - 1] || 'Password strength';
            strengthText.style.color = colors[strength - 1] || 'var(--text-muted)';
        });
        
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                window.TweakEasy.Notifications.error('Passwords do not match');
                return;
            }
            
            if (window.TweakEasy.FormValidator.validate(this)) {
                window.TweakEasy.Loading.show();
                
                setTimeout(() => {
                    window.TweakEasy.Loading.hide();
                    window.TweakEasy.Notifications.success('Account created successfully! Please check your email to verify.');
                    
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                }, 1500);
            }
        });
    </script>
</body>
</html>

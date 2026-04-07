<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
    * {
        font-family: 'Poppins', sans-serif;
    }
    
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        background-image: 
            radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(120, 200, 120, 0.2) 0%, transparent 50%);
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="registerLeaves" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/><path d="M8,8 Q10,6 12,8 Q10,10 8,8" fill="rgba(120,200,120,0.15)"/></pattern></defs><rect width="100" height="100" fill="url(%23registerLeaves)"/></svg>') repeat;
        opacity: 0.6;
        animation: float 20s ease-in-out infinite;
        z-index: 1;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(1deg); }
    }

    .register-container {
        position: relative;
        z-index: 10;
        padding: 2rem 0;
    }

    .nature-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        box-shadow: 
            0 25px 45px rgba(0, 0, 0, 0.1),
            0 0 0 1px rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .nature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #4CAF50, #8BC34A, #CDDC39);
        border-radius: 25px 25px 0 0;
    }

    .nature-card:hover {
        transform: translateY(-5px);
        box-shadow: 
            0 30px 60px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(255, 255, 255, 0.1);
    }

    .nature-title {
        color: #2E7D32;
        background: linear-gradient(135deg, #2E7D32, #4CAF50);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
        display: inline-block;
    }

    .nature-title::after {
        content: '🌱';
        position: absolute;
        right: -30px;
        top: 0;
        font-size: 0.8em;
        animation: grow 2s ease-in-out infinite;
    }

    @keyframes grow {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .nature-subtitle {
        color: #558B2F;
        font-weight: 500;
    }

    .nature-text {
        color: #689F38;
        font-weight: 400;
    }

    .nature-form {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 1.5rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .nature-input {
        border: 2px solid #E8F5E8;
        border-radius: 15px;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
        font-weight: 400;
        margin: 0.5rem 0;
    }

    .nature-input:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
        background: rgba(255, 255, 255, 1);
        outline: none;
    }

    .nature-input::placeholder {
        color: #81C784;
        font-weight: 400;
    }

    .nature-btn {
        background: linear-gradient(135deg, #4CAF50, #66BB6A, #8BC34A);
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 15px rgba(76, 175, 80, 0.3);
        color: white;
    }

    .nature-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s;
    }

    .nature-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(76, 175, 80, 0.4);
        background: linear-gradient(135deg, #66BB6A, #8BC34A, #9E9D24);
        color: white;
    }

    .nature-btn:hover::before {
        left: 100%;
    }

    .nature-link {
        color: #4CAF50;
        text-decoration: none;
        font-weight: 500;
        position: relative;
        transition: all 0.3s ease;
    }

    .nature-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #4CAF50, #8BC34A);
        transition: width 0.3s ease;
    }

    .nature-link:hover {
        color: #2E7D32;
        transform: translateX(3px);
        text-decoration: none;
    }

    .nature-link:hover::after {
        width: 100%;
    }

    .floating-elements {
        position: fixed;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
        z-index: 2;
    }

    .leaf {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #4CAF50;
        border-radius: 0 100% 0 100%;
        opacity: 0.3;
        animation: fall linear infinite;
    }

    @keyframes fall {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 0;
        }
        10% {
            opacity: 0.3;
        }
        90% {
            opacity: 0.3;
        }
        100% {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }
</style>

<body>
    <div class="floating-elements">
        <div class="leaf" style="left: 10%; animation-duration: 15s; animation-delay: 0s;"></div>
        <div class="leaf" style="left: 20%; animation-duration: 18s; animation-delay: 2s;"></div>
        <div class="leaf" style="left: 30%; animation-duration: 12s; animation-delay: 4s;"></div>
        <div class="leaf" style="left: 40%; animation-duration: 20s; animation-delay: 1s;"></div>
        <div class="leaf" style="left: 50%; animation-duration: 16s; animation-delay: 3s;"></div>
        <div class="leaf" style="left: 60%; animation-duration: 14s; animation-delay: 5s;"></div>
        <div class="leaf" style="left: 70%; animation-duration: 19s; animation-delay: 2s;"></div>
        <div class="leaf" style="left: 80%; animation-duration: 17s; animation-delay: 4s;"></div>
        <div class="leaf" style="left: 90%; animation-duration: 13s; animation-delay: 1s;"></div>
    </div>

    <div class="d-flex align-items-center justify-content-center register-container">
        <div class="nature-card shadow-lg p-4" style="width: 30rem;">
            <div class="h1 fw-bold nature-title">Soil Monitoring</div>
            <div class="h6 mb-0 mt-3 nature-subtitle">Hello! Let's get started.</div>
            <div class="nature-text">Create your account to continue.</div>
            <div class="nature-form">
                <form action="./api/registerAPI.php" method="POST">
                    <input class="form-control nature-input" type="text" name="username" placeholder="Username"  autocomplete="off" required>
                    <input class="form-control nature-input" type="password" name="password" placeholder="Password" required>
                    <input class="form-control nature-input" type="password" name="confirmPassword" placeholder="Confirm Password" required>
                    <input class="form-control nature-input" type="email" name="email" placeholder="Email"  autocomplete="off" required>
                    
                    <input class="form-control nature-input" type="text" name="adminCode" placeholder="Admin Secret Code (Optional)">
                    
                    <div class="d-grid">
                        <button class="rounded-pill btn nature-btn" type="submit" name="register">Register</button>
                    </div>
                </form>
            </div>
            <div class="text-center my-4 nature-text">Already have an account? <a href="login.php" class="nature-link">Login now!</a></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
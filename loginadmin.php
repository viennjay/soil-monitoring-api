<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- BS css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- BS icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
    * {
        font-family: 'Poppins', sans-serif;
    }
    
    .login-container {
        height: 100vh;
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 50%, #27ae60 100%);
        background-image: 
            radial-gradient(circle at 20% 80%, rgba(52, 152, 219, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(39, 174, 96, 0.25) 0%, transparent 50%);
        position: relative;
        overflow: hidden;
    }

    .login-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="adminPattern" x="0" y="0" width="25" height="25" patternUnits="userSpaceOnUse"><circle cx="12.5" cy="12.5" r="1.5" fill="rgba(255,255,255,0.08)"/><path d="M10,10 Q12.5,8 15,10 Q12.5,12 10,10" fill="rgba(52,152,219,0.12)"/></pattern></defs><rect width="100" height="100" fill="url(%23adminPattern)"/></svg>') repeat;
        opacity: 0.7;
        animation: adminFloat 25s ease-in-out infinite;
    }

    @keyframes adminFloat {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(2deg); }
    }

    .admin-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        box-shadow: 
            0 25px 45px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .admin-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3498db, #2c3e50, #27ae60);
        border-radius: 25px 25px 0 0;
    }

    .admin-card:hover {
        transform: translateY(-5px);
        box-shadow: 
            0 30px 60px rgba(0, 0, 0, 0.2),
            0 0 0 1px rgba(255, 255, 255, 0.1);
    }

    .admin-title {
        color: #2c3e50;
        background: linear-gradient(135deg, #2c3e50, #3498db);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
        display: inline-block;
    }

    .admin-title::after {
        content: '⚙️';
        position: absolute;
        right: -35px;
        top: 0;
        font-size: 0.8em;
        animation: rotate 3s linear infinite;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .admin-subtitle {
        color: #34495e;
        font-weight: 600;
    }

    .admin-text {
        color: #5d6d7e;
        font-weight: 400;
    }

    .admin-input-group {
        position: relative;
        margin: 1.5rem 0;
    }

    .admin-input-group .input-group-text {
        background: linear-gradient(135deg, #3498db, #2980b9);
        border: none;
        color: white;
        border-radius: 15px 0 0 15px;
        box-shadow: inset 0 1px 3px rgba(255, 255, 255, 0.3);
    }

    .admin-input-group .form-control {
        border: 2px solid #e8f4fd;
        border-left: none;
        border-radius: 0 15px 15px 0;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
        font-weight: 400;
    }

    .admin-input-group .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        background: rgba(255, 255, 255, 1);
    }

    .admin-btn {
        background: linear-gradient(135deg, #3498db, #2980b9, #2c3e50);
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 15px rgba(52, 152, 219, 0.3);
    }

    .admin-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s;
    }

    .admin-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(52, 152, 219, 0.4);
        background: linear-gradient(135deg, #2980b9, #2c3e50, #27ae60);
    }

    .admin-btn:hover::before {
        left: 100%;
    }

    .admin-link {
        color: #3498db;
        text-decoration: none;
        font-weight: 500;
        position: relative;
        transition: all 0.3s ease;
    }

    .admin-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #3498db, #2c3e50);
        transition: width 0.3s ease;
    }

    .admin-link:hover {
        color: #2c3e50;
        transform: translateX(3px);
    }

    .admin-link:hover::after {
        width: 100%;
    }

    .floating-elements {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
    }

    .gear {
        position: absolute;
        width: 15px;
        height: 15px;
        opacity: 0.2;
        animation: adminFall linear infinite;
    }

    .gear::before {
        content: '⚙️';
        font-size: 15px;
        color: #3498db;
    }

    @keyframes adminFall {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 0;
        }
        10% {
            opacity: 0.2;
        }
        90% {
            opacity: 0.2;
        }
        100% {
            transform: translateY(100vh) rotate(720deg);
            opacity: 0;
        }
    }

    .response-message {
        background: rgba(52, 152, 219, 0.1);
        border: 1px solid rgba(52, 152, 219, 0.3);
        border-radius: 10px;
        padding: 10px;
        margin-top: 15px;
        color: #2c3e50;
        font-weight: 500;
    }
</style>

<body>
    <div class="d-flex align-items-center justify-content-center login-container">
        <div class="floating-elements">
            <div class="gear" style="left: 10%; animation-duration: 20s; animation-delay: 0s;"></div>
            <div class="gear" style="left: 20%; animation-duration: 25s; animation-delay: 3s;"></div>
            <div class="gear" style="left: 30%; animation-duration: 18s; animation-delay: 6s;"></div>
            <div class="gear" style="left: 40%; animation-duration: 22s; animation-delay: 2s;"></div>
            <div class="gear" style="left: 50%; animation-duration: 28s; animation-delay: 5s;"></div>
            <div class="gear" style="left: 60%; animation-duration: 24s; animation-delay: 8s;"></div>
            <div class="gear" style="left: 70%; animation-duration: 19s; animation-delay: 1s;"></div>
            <div class="gear" style="left: 80%; animation-duration: 26s; animation-delay: 4s;"></div>
            <div class="gear" style="left: 90%; animation-duration: 21s; animation-delay: 7s;"></div>
        </div>
        
        <div class="admin-card shadow-lg p-4" style="width: 30rem; z-index: 10;">
            <div class="h1 fw-bold admin-title">Admin Portal</div>
            <div class="h6 mb-0 mt-3 admin-subtitle">Hello Admin!</div>
            <div class="admin-text">Sign in to continue.</div>
            <form action="admin.php" method="POST">
                <div class="admin-input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input class="form-control" type="text" name="username" placeholder="Username" autocomplete="off" required>
                </div>

                <div class="admin-input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                </div>

                <div class="d-grid">
                    <button class="btn admin-btn rounded-pill" type="submit" name="login">Login</button>
                </div>
            </form>

            <div class="text-center my-4 admin-text">Sign in as User? <a href="login.php" class="admin-link">Login</a></div>

            <div class="text-center">
                <?php if (!empty($_GET['response'])) {
                    echo '<div class="response-message">' . $_GET['response'] . '</div>';
                } ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
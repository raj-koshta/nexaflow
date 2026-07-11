<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Unauthorized Access</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #8b5cf6;
            --primary-light: rgba(139, 92, 246, 0.1);
            --bg-color: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        .error-container {
            text-align: center;
            max-width: 500px;
            padding: 3rem;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            position: relative;
            z-index: 10;
        }

        .icon-box {
            width: 100px;
            height: 100px;
            background: var(--primary-light);
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin: 0 auto 2rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .error-code {
            font-size: 4rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color), #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .error-message {
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-return {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-return:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px var(--primary-light);
            color: white;
        }

        /* Background decorations */
        .decoration {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 1;
            opacity: 0.4;
        }
        
        .dec-1 {
            width: 400px;
            height: 400px;
            background: var(--primary-color);
            top: -100px;
            left: -100px;
        }
        
        .dec-2 {
            width: 300px;
            height: 300px;
            background: #ec4899;
            bottom: -50px;
            right: -50px;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-color: #0f172a;
                --text-main: #f8fafc;
                --text-muted: #94a3b8;
            }
            .error-container {
                background: #1e293b;
                box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            }
        }
    </style>
</head>
<body>
    <div class="decoration dec-1"></div>
    <div class="decoration dec-2"></div>

    <div class="error-container">
        <div class="icon-box">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        
        <div class="error-code">403</div>
        <h1 class="error-title">Access Denied</h1>
        
        <p class="error-message">
            Oops! It looks like you don't have the required permissions to view this page. If you believe this is a mistake, please contact your administrator.
        </p>

        <a href="{{ route('dashboard') }}" class="btn-return">
            <i class="bi bi-arrow-left"></i> Return to Dashboard
        </a>
    </div>
</body>
</html>

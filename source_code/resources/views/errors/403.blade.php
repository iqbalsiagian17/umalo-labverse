<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #fff3cd;
            color: #856404;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .error-container {
            text-align: center;
        }
        .error-icon {
            width: 150px;
            height: 150px;
            margin-bottom: 20px;
        }
        .error-code {
            font-size: 96px;
            font-weight: bold;
        }
        .error-message {
            font-size: 24px;
            margin-bottom: 30px;
        }
        .error-link {
            font-size: 18px;
            color: #007bff;
            text-decoration: none;
        }
        .error-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <!-- SVG Icon (Lock) -->
        <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="currentColor">
            <!-- Lock body -->
            <rect x="18" y="28" width="28" height="20" rx="4" ry="4" fill="#856404"/>
            <!-- Lock shackle -->
            <path d="M22,28 Q32,8,42,28" fill="none" stroke="#856404" stroke-width="3"/>
            <!-- Lock's keyhole -->
            <circle cx="32" cy="38" r="2" fill="#856404"/>
        </svg>

        <div class="error-code">403</div>
        <div class="error-message">Access Denied!</div>
        <a href="{{ url('/') }}" class="error-link">Go back to Home</a>
    </div>
</body>
</html>

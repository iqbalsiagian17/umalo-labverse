<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #d1ecf1;
            color: #0c5460;
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
        <!-- SVG Icon (Exclamation) -->
        <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="currentColor">
            <!-- Shield shape -->
            <path d="M32 4 L60 20 L32 60 L4 20 Z" fill="#0c5460"/>
            <!-- Exclamation mark -->
            <line x1="32" y1="28" x2="32" y2="40" stroke="#d1ecf1" stroke-width="4"/>
            <circle cx="32" cy="48" r="2" fill="#d1ecf1"/>
        </svg>

        <div class="error-code">401</div>
        <div class="error-message">Unauthorized Access</div>
        <a href="{{ url('/') }}" class="error-link">Go back to Home</a>
    </div>
</body>
</html>

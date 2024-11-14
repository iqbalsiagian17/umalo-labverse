<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Server Error</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8d7da;
            color: #721c24;
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
        <!-- SVG Icon (Server) -->
        <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="currentColor">
            <!-- Server shape -->
            <rect x="8" y="16" width="48" height="32" rx="4" ry="4" fill="#721c24"/>
            <!-- Lights on server -->
            <circle cx="20" cy="24" r="3" fill="#f8d7da"/>
            <circle cx="28" cy="24" r="3" fill="#f8d7da"/>
            <circle cx="36" cy="24" r="3" fill="#f8d7da"/>
            <circle cx="44" cy="24" r="3" fill="#f8d7da"/>
            <!-- X for error -->
            <line x1="20" y1="36" x2="44" y2="36" stroke="#e3342f" stroke-width="3"/>
        </svg>

        <div class="error-code">500</div>
        <div class="error-message">Oops! Something went wrong.</div>
        <a href="{{ url('/') }}" class="error-link">Go back to Home</a>
    </div>
</body>
</html>

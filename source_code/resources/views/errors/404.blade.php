<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            color: #343a40;
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
            animation: shake 0.5s infinite alternate;
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

        @keyframes shake {
            0% { transform: translate(0px, 0px) rotate(0deg); }
            25% { transform: translate(2px, -2px) rotate(-1deg); }
            50% { transform: translate(-2px, 2px) rotate(1deg); }
            75% { transform: translate(2px, 2px) rotate(0deg); }
            100% { transform: translate(-2px, -2px) rotate(-1deg); }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <!-- SVG Icon -->
        <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="currentColor">
            <!-- Body of the robot -->
            <rect x="16" y="16" width="32" height="32" rx="4" ry="4" fill="#343a40"/>
            <!-- Robot's eyes -->
            <circle cx="24" cy="24" r="4" fill="#007bff"/>
            <circle cx="40" cy="24" r="4" fill="#007bff"/>
            <!-- Robot's antenna -->
            <line x1="32" y1="16" x2="32" y2="10" stroke="#343a40" stroke-width="2"/>
            <circle cx="32" cy="8" r="2" fill="#343a40"/>
            <!-- Robot's arms -->
            <line x1="16" y1="32" x2="6" y2="32" stroke="#343a40" stroke-width="2"/>
            <line x1="48" y1="32" x2="58" y2="32" stroke="#343a40" stroke-width="2"/>
            <!-- Robot's mouth (broken) -->
            <path d="M 24 40 Q 32 36, 40 40" stroke="#e3342f" stroke-width="2" fill="none"/>
            <!-- Additional broken lines -->
            <line x1="26" y1="36" x2="24" y2="38" stroke="#e3342f" stroke-width="2"/>
            <line x1="38" y1="36" x2="40" y2="38" stroke="#e3342f" stroke-width="2"/>
        </svg>

        <div class="error-code">404</div>
        <div class="error-message">Oops! Page not found.</div>
        @if(Auth::check() && Auth::user()->role === 'admin')
            <a href="{{ route('dashboard') }}" class="error-link">Go to Dashboard</a>
        @else
            <a href="{{ url('/') }}" class="error-link">Go back to Home</a>
        @endif
    </div>
</body>
</html>

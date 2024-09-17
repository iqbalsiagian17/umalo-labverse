

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Logic Error</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #d1ecf1;
            color: #0c5460;
            font-family: 'Arial', sans-serif;
            margin: 0;
        }
        .error-container {
            text-align: center;
        }
        .error-icon {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite; /* Apply animation */
        }
        .error-code {
            font-size: 72px;
            font-weight: bold;
        }
        .error-message {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .error-link {
            font-size: 18px;
            color: #007bff;
            text-decoration: none;
        }
        .error-link:hover {
            text-decoration: underline;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <!-- SVG Icon (Broken Code) -->
        <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="currentColor">
            <polygon points="32,2 61,17 61,47 32,62 3,47 3,17" fill="#0c5460"/>
            <text x="32" y="32" text-anchor="middle" fill="#d1ecf1" font-size="9" font-family="Arial" dy=".3em">&#9888;</text> <!-- Lightning bolt or warning sign -->
        </svg>
        
        <div class="error-code">500</div>
        <div class="error-message">There was an error in the logic of our code. We are fixing it!</div>
        <p>If you need immediate assistance, please email us at <a href="mailto:info@labtek.id">info@labtek.id</a>.</p>
        <a href="{{ url('/') }}" class="error-link">Go back to Home</a>
    </div>
</body>
</html>

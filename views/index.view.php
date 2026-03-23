<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Leaf PHP Application' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }
        header h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        header p {
            font-size: 1.25rem;
            opacity: 0.9;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        .feature {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .feature h3 {
            color: #667eea;
            margin-bottom: 1rem;
        }
        .feature code {
            background: #f0f0f0;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.9em;
        }
        footer {
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 1rem;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <header>
        <h1>🍃 Leaf PHP</h1>
        <p>A simple, elegant PHP microframework</p>
        <a href="https://leafphp.dev" class="btn">View Documentation</a>
    </header>

    <div class="container">
        <div class="features">
            <div class="feature">
                <h3>🚀 Quick Start</h3>
                <p>Get up and running in seconds with Leaf's intuitive API and minimal setup.</p>
                <code>composer require leafs/leaf</code>
            </div>
            <div class="feature">
                <h3>🛡️ Secure by Default</h3>
                <p>Built-in CSRF protection, input validation, and secure session handling.</p>
            </div>
            <div class="feature">
                <h3>📦 Modular Design</h3>
                <p>Install only what you need. Leaf plays nice with other packages.</p>
            </div>
            <div class="feature">
                <h3>🔧 Flexible Routing</h3>
                <p>Powerful routing with support for middleware, groups, and named routes.</p>
            </div>
            <div class="feature">
                <h3>🎨 View Engines</h3>
                <p>Support for Blade, BareUI, Twig, or plain PHP templates.</p>
            </div>
            <div class="feature">
                <h3>☁️ Deploy Anywhere</h3>
                <p>Works with Railway, Heroku, Docker, or traditional hosting.</p>
            </div>
        </div>

        <section style="margin-top: 4rem; text-align: center;">
            <h2>Ready to Build?</h2>
            <p style="margin: 1rem 0;">Check out the documentation to get started.</p>
            <a href="https://leafphp.dev/docs" class="btn">Get Started</a>
        </section>
    </div>

    <footer>
        <p>Built with Leaf PHP &copy; <?= date('Y') ?></p>
    </footer>
</body>
</html>

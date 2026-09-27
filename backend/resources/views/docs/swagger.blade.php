<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swagger API Documentation - CRM Lead & Customer Tracker</title>
    <!-- Swagger UI CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.11.0/swagger-ui.css">
    <style>
        html { box-sizing: border-box; overflow: -moz-scrollbars-vertical; overflow-y: scroll; }
        *, *:before, *:after { box-sizing: inherit; }
        body { margin: 0; background: #fafafa; font-family: 'Plus Jakarta Sans', sans-serif; }
        .topbar-custom {
            background-color: #0f172a;
            color: #ffffff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar-brand { font-weight: 800; font-size: 1.2rem; color: #818cf8; text-decoration: none; }
        .btn-back { background: #334155; color: #ffffff; padding: 0.4rem 1rem; border-radius: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600; }
        .btn-back:hover { background: #475569; }
    </style>
</head>
<body>
    <div class="topbar-custom">
        <a href="/" class="topbar-brand">⚡ CRM Lead Tracker — Interactive Swagger REST API Docs</a>
        <a href="/" class="btn-back">← Back to CRM Web UI</a>
    </div>

    <div id="swagger-ui"></div>

    <!-- Swagger UI JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.11.0/swagger-ui-bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.11.0/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: "/swagger.json",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout"
            });
            window.ui = ui;
        };
    </script>
</body>
</html>

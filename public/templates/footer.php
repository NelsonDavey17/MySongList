<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NavBar</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css"
    />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            border-top: 1px solid var(--glass-border);
            color: var(--text-sub);
            text-align: center;
            font-size: 0.85rem;
            padding: 0.6rem 0;
            box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease;
            z-index: 50;
        }

        .footer:hover {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body>
    <footer class="footer">
        <p>© 2025 MySongList — Buat Kamu Yang Suka Dengan Lagu.</p>
    </footer>
</body>
</html>
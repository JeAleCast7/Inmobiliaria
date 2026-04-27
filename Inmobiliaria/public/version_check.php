<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PHP Version Check</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #1a1a1a; color: #fff; }
        .version-box { padding: 40px; border-radius: 20px; background: #252525; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center; border: 1px solid #c8a415; }
        h1 { color: #c8a415; margin-bottom: 10px; }
        .version-number { font-size: 3rem; font-weight: bold; margin: 20px 0; color: #fff; }
        .badge { display: inline-block; padding: 5px 15px; border-radius: 5px; background: #c8a415; color: #1a1a1a; font-weight: bold; }
    </style>
</head>
<body>
    <div class="version-box">
        <h1>PHP Engine Status</h1>
        <div class="version-number"><?php echo phpversion(); ?></div>
        <div class="badge">Active & Optimized</div>
        <p style="margin-top:20px; color:#888;">Entorno: <?php echo php_sapi_name(); ?></p>
    </div>
</body>
</html>

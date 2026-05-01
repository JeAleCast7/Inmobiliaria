<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasarela de Pagos Ficticia</title>
    <!-- Mismo CSS para consistencia estetica -->
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/assets/css/globals.css">
    <style>
        body {
            background-color: #f4f8eb;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
        }
        .payment-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }
        .payment-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .payment-header h2 {
            color: #3b4231;
            margin-bottom: 5px;
        }
        .payment-header p {
            color: #607050;
            margin-top: 0;
            font-size: 0.95rem;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #3b4231;
        }
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        .form-input:focus {
            border-color: #c8a415;
            outline: none;
        }
        .row {
            display: flex;
            gap: 15px;
        }
        .row .form-group {
            flex: 1;
        }
        .btn-pay {
            width: 100%;
            padding: 15px;
            background-color: #c8a415;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-pay:hover {
            background-color: #a48611;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #607050;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="payment-card">
    <div class="payment-header">
        <h2>Pago Seguro</h2>
        <p>Inmueble: <?php echo htmlspecialchars($factura['direccion']); ?></p>
        <div style="font-size: 2rem; font-weight: bold; color: #3b4231; margin-top: 15px;">
            $<?php echo number_format($factura['valor_total'], 0, ',', '.'); ?>
        </div>
    </div>

    <form action="<?php echo URL_ROOT; ?>/cliente/procesar-pago" method="POST">
        <input type="hidden" name="id_factura" value="<?php echo $factura['id_factura']; ?>">
        
        <div class="form-group">
            <label class="form-label">Nombre en la tarjeta</label>
            <input type="text" class="form-input" placeholder="Ej. Juan Pérez" required>
        </div>

        <div class="form-group">
            <label class="form-label">Número de la Tarjeta</label>
            <input type="text" class="form-input" placeholder="0000 0000 0000 0000" maxlength="19" required>
        </div>

        <div class="row">
            <div class="form-group">
                <label class="form-label">Fecha Exp.</label>
                <input type="text" class="form-input" placeholder="MM/AA" maxlength="5" required>
            </div>
            <div class="form-group">
                <label class="form-label">CVV</label>
                <input type="text" class="form-input" placeholder="123" maxlength="4" required>
            </div>
        </div>

        <button type="submit" class="btn-pay">Pagar $<?php echo number_format($factura['valor_total'], 0, ',', '.'); ?></button>
    </form>
    
    <a href="<?php echo URL_ROOT; ?>/cliente/dashboard" class="back-link">← Volver al inicio</a>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasarela de Pagos Ficticia</title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/assets/css/estilos.css">
</head>
<body class="payment-page-body">

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
        
        <div class="payment-form-group">
            <label class="payment-form-label">Nombre en la tarjeta</label>
            <input type="text" class="payment-form-input" placeholder="Ej. Juan Pérez" required>
        </div>

        <div class="payment-form-group">
            <label class="payment-form-label">Número de la Tarjeta</label>
            <input type="text" class="payment-form-input" placeholder="0000 0000 0000 0000" maxlength="19" required>
        </div>

        <div class="payment-row">
            <div class="payment-form-group">
                <label class="payment-form-label">Fecha Exp.</label>
                <input type="text" class="payment-form-input" placeholder="MM/AA" maxlength="5" required>
            </div>
            <div class="payment-form-group">
                <label class="payment-form-label">CVV</label>
                <input type="text" class="payment-form-input" placeholder="123" maxlength="4" required>
            </div>
        </div>

        <button type="submit" class="btn-pay">Pagar $<?php echo number_format($factura['valor_total'], 0, ',', '.'); ?></button>
    </form>
    
    <a href="<?php echo URL_ROOT; ?>/cliente/dashboard" class="payment-back-link">← Volver al inicio</a>
</div>

</body>
</html>

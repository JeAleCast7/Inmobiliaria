<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo No. <?php echo str_pad($factura['id_factura'], 6, '0', STR_PAD_LEFT); ?></title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/assets/css/estilos.css">
</head>
<body class="receipt-page-body">

<div style="text-align: center;" class="receipt-print-hide">
    <button class="receipt-btn-print" onclick="window.print()">🖨️ Guardar PDF / Imprimir</button>
    <a href="<?php echo URL_ROOT; ?>/cliente/dashboard" class="receipt-btn-back">← Volver al inicio</a>
</div>

<div class="receipt-container">
    <div class="receipt-header">
        <div class="receipt-logo">
            <h2 style="color: #607050; margin: 0;">Boyacá Real Estate</h2>
            <p style="margin: 0; font-size: 12px; color: #888;">Agencia Inmobiliaria</p>
        </div>
        <div class="receipt-title">
            <h1>RECIBO DE PAGO</h1>
            <p>Factura No. <?php echo str_pad($factura['id_factura'], 6, '0', STR_PAD_LEFT); ?></p>
            <p>Fecha: <?php echo date('d/m/Y H:i', strtotime($factura['fecha'])); ?></p>
        </div>
    </div>

    <div class="receipt-info-grid">
        <div class="receipt-info-box">
            <h3>Facturado a:</h3>
            <p><strong><?php echo htmlspecialchars($factura['cliente_nombre']); ?></strong></p>
            <p>Doc/NIT: <?php echo htmlspecialchars($factura['numero_documento']); ?></p>
        </div>
        <div class="receipt-info-box">
            <h3>Detalles del Inmueble:</h3>
            <p><strong><?php echo htmlspecialchars($factura['direccion']); ?></strong></p>
            <p>Id Propiedad: #<?php echo $factura['id_inmueble']; ?></p>
            <p>Agente a Cargo: <?php echo htmlspecialchars($factura['agente_nombre']); ?></p>
        </div>
    </div>

    <table class="receipt-amount-table">
        <thead>
            <tr>
                <th style="width: 70%;">Descripción</th>
                <th style="width: 30%; text-align: right;">Monto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pago Inmueble - <?php echo ucfirst($factura['tipo']); ?></td>
                <td style="text-align: right;">$<?php echo number_format($factura['valor_total'], 0, ',', '.'); ?></td>
            </tr>
            <tr class="receipt-total-row">
                <td>Total Pagado</td>
                <td>$<?php echo number_format($factura['valor_total'], 0, ',', '.'); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="receipt-footer">
        <p>Este recibo confirma que el pago ha sido procesado exitosamente mediante nuestra plataforma online.</p>
        <p>Gracias por confiar en Boyacá Real Estate.</p>
    </div>
</div>

<script>
    // Trigger print automatically when opening
    window.onload = function() {
        // window.print();
    }
</script>

</body>
</html>

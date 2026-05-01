<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo No. <?php echo str_pad($factura['id_factura'], 6, '0', STR_PAD_LEFT); ?></title>
    <style>
        body { margin: 0; padding: 20px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background: #fff; color: #333; }
        .receipt-container { max-width: 800px; margin: 0 auto; padding: 40px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #c8a415; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { max-width: 150px; }
        .title { text-align: right; }
        .title h1 { color: #3b4231; margin: 0; font-size: 24px; }
        .title p { margin: 5px 0 0; color: #666; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px; }
        .info-box h3 { border-bottom: 1px solid #ddd; padding-bottom: 8px; color: #607050; font-size: 16px; margin-top: 0; }
        .info-box p { margin: 5px 0; font-size: 14px; }
        .amount-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .amount-table th { background: #f4f8eb; color: #607050; text-align: left; padding: 12px; border: 1px solid #ddd; }
        .amount-table td { padding: 12px; border: 1px solid #ddd; }
        .total-row td { font-weight: bold; font-size: 18px; color: #3b4231; background: #f9f9f9; text-align: right; }
        .footer { text-align: center; color: #777; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt-container { border: none; box-shadow: none; padding: 0; }
            .btn-print { display: none; }
        }
        .btn-print { display: inline-block; background: #c8a415; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-bottom: 20px; cursor: pointer; border: none; font-size: 14px; }
        .btn-print:hover { background: #a48611; }
        .btn-back { display: inline-block; background: #3b4231; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-bottom: 20px; margin-left: 10px; font-size: 14px; }
    </style>
</head>
<body>

<div style="text-align: center;">
    <button class="btn-print" onclick="window.print()">🖨️ Guardar PDF / Imprimir</button>
    <a href="<?php echo URL_ROOT; ?>/cliente/dashboard" class="btn-back">← Volver al inicio</a>
</div>

<div class="receipt-container">
    <div class="header">
        <div class="logo">
            <h2 style="color: #607050; margin: 0;">Boyacá Real Estate</h2>
            <p style="margin: 0; font-size: 12px; color: #888;">Agencia Inmobiliaria</p>
        </div>
        <div class="title">
            <h1>RECIBO DE PAGO</h1>
            <p>Factura No. <?php echo str_pad($factura['id_factura'], 6, '0', STR_PAD_LEFT); ?></p>
            <p>Fecha: <?php echo date('d/m/Y H:i', strtotime($factura['fecha'])); ?></p>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <h3>Facturado a:</h3>
            <p><strong><?php echo htmlspecialchars($factura['cliente_nombre']); ?></strong></p>
            <p>Doc/NIT: <?php echo htmlspecialchars($factura['numero_documento']); ?></p>
        </div>
        <div class="info-box">
            <h3>Detalles del Inmueble:</h3>
            <p><strong><?php echo htmlspecialchars($factura['direccion']); ?></strong></p>
            <p>Id Propiedad: #<?php echo $factura['id_inmueble']; ?></p>
            <p>Agente a Cargo: <?php echo htmlspecialchars($factura['agente_nombre']); ?></p>
        </div>
    </div>

    <table class="amount-table">
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
            <tr class="total-row">
                <td>Total Pagado</td>
                <td>$<?php echo number_format($factura['valor_total'], 0, ',', '.'); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
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

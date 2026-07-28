<?php
if (!isset($_SESSION['restaurant_id'])) header('Location: /auth/login');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Mesas - Zife Order Admin</title>
    <link rel="stylesheet" href="/public/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-layout">
        <?php require __DIR__ . '/_sidebar.php'; ?>

        <main class="admin-main">
            <div class="topbar">
                <div class="topbar-content">
                    <h1>Mesas & QR Codes</h1>
                    <button class="btn btn-primary" onclick="alert('Implementar adição de mesas')">+ Adicionar Mesa</button>
                </div>
            </div>

            <div class="admin-content">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
                    <div style="background: white; padding: 20px; border-radius: 12px; text-align: center; border: 2px solid #4B7F52;">
                        <div style="font-size: 40px; margin-bottom: 10px;">🪑</div>
                        <h3 style="font-family: 'Bricolage Grotesque'; margin-bottom: 8px;">Mesa 1</h3>
                        <p style="color: #4B7F52; font-weight: 700; margin-bottom: 12px;">LIVRE</p>
                        <button class="btn btn-secondary" style="width: 100%; margin-bottom: 8px;">Ver QR Code</button>
                        <button class="btn btn-danger" style="width: 100%; padding: 4px;">Editar</button>
                    </div>

                    <div style="background: white; padding: 20px; border-radius: 12px; text-align: center; border: 2px solid #C98A1D;">
                        <div style="font-size: 40px; margin-bottom: 10px;">🪑</div>
                        <h3 style="font-family: 'Bricolage Grotesque'; margin-bottom: 8px;">Mesa 2</h3>
                        <p style="color: #C98A1D; font-weight: 700; margin-bottom: 12px;">OCUPADA</p>
                        <button class="btn btn-secondary" style="width: 100%; margin-bottom: 8px;">Ver Pedido</button>
                        <button class="btn btn-danger" style="width: 100%; padding: 4px;">Editar</button>
                    </div>
                </div>

                <div style="margin-top: 30px;">
                    <h2 style="font-family: 'Bricolage Grotesque'; margin-bottom: 20px;">Sobre QR Codes</h2>
                    <p style="color: #4A3E36;">Os QR Codes foram gerados automaticamente para cada mesa. Imprima e cole nas mesas para que clientes possam escanear e fazer pedidos diretamente.</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

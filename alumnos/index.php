<?php include("funciones.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Alumnos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.3.3/cosmo/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h1 class="text-center mb-4">Sistema de Gestión de Alumnos</h1>
    <div class="card shadow">
        <div class="card-body text-center">
            <p class="fs-4">Hoy es <?= date('d/m/Y') ?></p>
            <a href="app.php" class="btn btn-primary btn-lg mt-3">
                <i class="fas fa-arrow-right me-2"></i>Ir al Catálogo
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso No Autorizado - MateriApp</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="error-page">
    <div class="error-container">
        <div class="error-icon">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                <path d="M2 17l10 5 10-5"></path>
                <path d="M2 12l10 5 10-5"></path>
            </svg>
        </div>
        <h1>Acceso No Autorizado</h1>
        <p>No tienes permisos para acceder a esta sección del sistema.</p>
        <a href="<?= BASE_URL ?>auth/login" class="btn btn-primary">Volver al inicio</a>
    </div>
</body>
</html>







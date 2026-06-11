<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MateriApp</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="50" height="50" rx="10" fill="#1e3a5f"/>
                    <rect x="10" y="10" width="12" height="12" rx="2" fill="#4ade80"/>
                    <rect x="28" y="10" width="12" height="12" rx="2" fill="#60a5fa"/>
                    <rect x="10" y="28" width="12" height="12" rx="2" fill="#fbbf24"/>
                    <rect x="28" y="28" width="12" height="12" rx="2" fill="#34d399"/>
                </svg>
            </div>
            <h1>MateriApp</h1>
            <p>Gestión de Solicitudes y Control de Materiales<br>Telemática SAS</p>
        </div>

<div class="login-form">
            <h2>Iniciar sesión</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (Session::has('flash_success')): ?>
                <div class="alert alert-success"><?= Session::get('flash_success') ?></div>
                <?php Session::destroy('flash_success'); ?>
            <?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>auth/login" novalidate>
                <div class="form-group">
                    <label for="correo_empresarial">Correo empresarial</label>
                    <input type="email" id="correo_empresarial" name="correo_empresarial" 
                           placeholder="usuario@telematicasas.com" required 
                           value="<?= htmlspecialchars($_POST['correo_empresarial'] ?? '') ?>"
                           autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" 
                           placeholder="••••••••" required
                           autocomplete="off">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
            </form>

<p class="login-link">
                ¿Olvidaste tu contraseña? <a href="#">Contactar al administrador</a>
            </p>
        </div>



        <div class="login-footer">
            <p>
                <a href="<?= BASE_URL ?>auth/registro">Crear cuenta nueva</a>
            </p>
            <p class="copyright">© MateriApp - Telemática SAS</p>
</div>
    </div>

    <?php if (Session::has('flash_success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: <?= json_encode(Session::get('flash_success')) ?>,
                confirmButtonColor: '#1e3a5f'
            });
        });
    </script>
    <?php Session::destroy('flash_success'); ?>
    <?php endif; ?>

    <?php if ($error): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: <?= json_encode($error) ?>,
                confirmButtonColor: '#1e3a5f'
            });
        });
    </script>
    <?php endif; ?>

    <script src="<?= BASE_URL ?>public/js/app.js"></script>
</body>
</html>







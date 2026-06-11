<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - MateriApp</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="registro-page">
    <div class="registro-container">
        <div class="registro-header">
            <a href="<?= BASE_URL ?>auth/login" class="back-link">← Volver</a>
            <div class="logo">
                <svg width="40" height="40" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="50" height="50" rx="10" fill="#1e3a5f"/>
                    <rect x="10" y="10" width="12" height="12" rx="2" fill="#4ade80"/>
                    <rect x="28" y="10" width="12" height="12" rx="2" fill="#60a5fa"/>
                    <rect x="10" y="28" width="12" height="12" rx="2" fill="#fbbf24"/>
                    <rect x="28" y="28" width="12" height="12" rx="2" fill="#34d399"/>
                </svg>
            </div>
            <h1>MateriApp</h1>
            <p>Registro de Usuario</p>
        </div>

        <div class="registro-form">
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>auth/registro">
                <fieldset>
                    <legend>Información personal</legend>
                    
                    <div class="form-group">
                        <label for="nombres_completos">Nombres completos *</label>
                        <input type="text" id="nombres_completos" name="nombres_completos" required
                               value="<?= htmlspecialchars($_POST['nombres_completos'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="cedula">Cédula de ciudadanía *</label>
                        <input type="text" id="cedula" name="cedula" required 
                               placeholder="Ej: 1.098.765.432"
                               value="<?= htmlspecialchars($_POST['cedula'] ?? '') ?>">
                    </div>

<div class="form-group">
                        <label for="correo_empresarial">Correo empresarial *</label>
                        <input type="email" id="correo_empresarial" name="correo_empresarial" required
                               placeholder="c.perez@telematica.com"
                               value="<?= htmlspecialchars($_POST['correo_empresarial'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="celular">Celular *</label>
                        <input type="tel" id="celular" name="celular" required
                               placeholder="+57 315 842 7610"
                               value="<?= htmlspecialchars($_POST['celular'] ?? '') ?>">
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Acceso y seguridad</legend>

                    <div class="form-group">
                        <label for="rol">Rol en el sistema *</label>
                        <select id="rol" name="rol" required>
                            <option value="">Seleccione un rol</option>
                            <option value="Trabajador" <?= (($_POST['rol'] ?? '') === 'Trabajador') ? 'selected' : '' ?>>Trabajador</option>
                            <option value="Almacen" <?= (($_POST['rol'] ?? '') === 'Almacen') ? 'selected' : '' ?>>Almacén</option>
                            <option value="Administrador" <?= (($_POST['rol'] ?? '') === 'Administrador') ? 'selected' : '' ?>>Administrador</option>
                        </select>
                    </div>

<div class="form-group">
                        <label for="contrasena">Contraseña *</label>
                        <input type="password" id="contrasena" name="contrasena" required 
                               placeholder="Ingrese contraseña">
                    </div>

                    <div class="alert alert-info">
                        <strong>ℹ El rol Administrador</strong> requiere cédula autorizada. Tu cédula será validada automáticamente contra el registro de cédulas autorizadas en la base de datos.
                    </div>
                </fieldset>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear cuenta</button>
                </div>
            </form>
        </div>

        <div class="registro-footer">
            <p>© MateriApp - Telemática SAS</p>
</div>
    </div>

    <?php if ($success): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Registro exitoso!',
                text: <?= json_encode($success) ?>,
                confirmButtonText: 'Iniciar sesión',
                confirmButtonColor: '#1e3a5f'
            }).then(() => {
                window.location.href = '<?= BASE_URL ?>auth/login';
            });
        });
    </script>
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







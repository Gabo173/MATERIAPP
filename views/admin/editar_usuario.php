<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - MateriApp</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/js/styles.js.css"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script></head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <svg width="40" height="40" viewBox="0 0 50 50" fill="none">
                        <rect width="50" height="50" rx="10" fill="#1e3a5f"/>
                        <rect x="10" y="10" width="12" height="12" rx="2" fill="#4ade80"/>
                        <rect x="28" y="10" width="12" height="12" rx="2" fill="#60a5fa"/>
                        <rect x="10" y="28" width="12" height="12" rx="2" fill="#fbbf24"/>
                        <rect x="28" y="28" width="12" height="12" rx="2" fill="#34d399"/>
                    </svg>
                    <span class="logo-text">MateriApp</span>
                </div>
                <div class="user-info">
                    <div class="user-avatar"><?= strtoupper(substr(Session::getUserName(), 0, 2)) ?></div>
                    <div class="user-details">
                        <span class="user-name"><?= htmlspecialchars(Session::getUserName()) ?></span>
                        <span class="user-role">Administrador</span>
                    </div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="<?= BASE_URL ?>admin/usuarios" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'usuarios') !== false ? 'active' : '' ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Gestión de Cuentas
                </a>
                <a href="<?= BASE_URL ?>admin/solicitudes" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'solicitudes') !== false ? 'active' : '' ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    Solicitudes
                </a>
                <a href="<?= BASE_URL ?>admin/registro" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'registro') !== false ? 'active' : '' ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <line x1="19" y1="8" x2="19" y2="14"></line>
                        <line x1="22" y1="11" x2="16" y2="11"></line>
                    </svg>
                    Registro
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="<?= BASE_URL ?>auth/logout" class="logout-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Cerrar sesión
                </a>
            </div>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1><?= $pageTitle ?></h1>
            </div>

            <?php if (Session::has('flash_success')): ?>
                <div class="alert alert-success"><?= Session::get('flash_success') ?></div>
                <?php Session::destroy('flash_success'); ?>
            <?php endif; ?>

            <?php if (Session::has('flash_error')): ?>
                <div class="alert alert-error"><?= Session::get('flash_error') ?></div>
                <?php Session::destroy('flash_error'); ?>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" action="<?= BASE_URL ?>admin/usuario/<?= $usuario->getId() ?>/editar">
                    <div class="form-group">
                        <label for="nombres_completos">Nombres Completos</label>
                        <input type="text" id="nombres_completos" name="nombres_completos" value="<?= htmlspecialchars($usuario->getNombresCompletos()) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="correo_empresarial">Correo Empresarial</label>
                        <input type="email" id="correo_empresarial" name="correo_empresarial" value="<?= htmlspecialchars($usuario->getCorreoEmpresarial()) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="celular">Celular</label>
                        <input type="text" id="celular" name="celular" value="<?= htmlspecialchars($usuario->getCelular()) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <select id="rol" name="rol" required>
                            <option value="Administrador" <?= $usuario->getRol() === 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                            <option value="Almacen" <?= $usuario->getRol() === 'Almacen' ? 'selected' : '' ?>>Almacén</option>
                            <option value="Trabajador" <?= $usuario->getRol() === 'Trabajador' ? 'selected' : '' ?>>Trabajador</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nueva_contrasena">Nueva Contraseña (dejar vacío para no cambiar)</label>
                        <input type="password" id="nueva_contrasena" name="nueva_contrasena" placeholder="Mínimo 8 caracteres">
                    </div>

                    <div class="form-actions">
                        <a href="<?= BASE_URL ?>admin/usuarios" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <?php if (Session::has('flash_success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            MateriApp.toast.show(<?= json_encode(Session::get('flash_success')) ?>, 'success');
        });
    </script>
    <?php Session::destroy('flash_success'); ?>
    <?php endif; ?>

    <?php if (Session::has('flash_error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            MateriApp.toast.show(<?= json_encode(Session::get('flash_error')) ?>, 'error');
        });
    </script>
    <?php Session::destroy('flash_error'); ?>
    <?php endif; ?>

    <script src="<?= BASE_URL ?>public/js/app.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - MateriApp</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/js/styles.js.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
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
                        <span class="user-role">Almacén</span>
                    </div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="<?= BASE_URL ?>almacen/solicitudes" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    Solicitudes
                </a>
                <a href="<?= BASE_URL ?>almacen/inventario" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 7h-3a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2H8a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
                        <path d="M10 7V5a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Inventario
                </a>
                <a href="<?= BASE_URL ?>almacen/perfil" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Mi Perfil
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
                <a href="<?= BASE_URL ?>almacen/inventario" class="btn btn-secondary">Volver</a>
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
                <form method="POST" action="<?= BASE_URL ?>almacen/material/<?= $item->getIdItem() ?>/editar">
                    <div class="form-grid">
                        <div class="form-group" style="grid-column: span 2;">
                            <label for="nombre_producto">Nombre del Producto *</label>
                            <input type="text" id="nombre_producto" name="nombre_producto" value="<?= htmlspecialchars($item->getNombreProducto()) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="cod_producto">Código de Producto *</label>
                            <input type="text" id="cod_producto" name="cod_producto" value="<?= htmlspecialchars($item->getCodProducto()) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="cantidad_disponible">Cantidad Disponible *</label>
                            <input type="number" id="cantidad_disponible" name="cantidad_disponible" value="<?= $item->getCantidadDisponible() ?>" required min="0">
                        </div>

                        <!-- Campos ocultos para bodega -->
                        <input type="hidden" name="nombre_bodega" value="<?= htmlspecialchars($item->getNombreBodega()) ?>">
                        <input type="hidden" name="cod_bodega" value="<?= htmlspecialchars($item->getCodBodega()) ?>">
                    </div>

                    <div class="form-actions">
                        <a href="<?= BASE_URL ?>almacen/inventario" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Material</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <style>
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
    </style>
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
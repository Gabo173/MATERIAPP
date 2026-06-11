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
                <a href="<?= BASE_URL ?>almacen/inventario" class="nav-item active">
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
                <button type="button" class="btn btn-primary" onclick="document.getElementById('modalAgregar').style.display='block'">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    Agregar Material
                </button>
            </div>

            <?php if (Session::has('flash_success')): ?>
                <div class="alert alert-success"><?= Session::get('flash_success') ?></div>
                <?php Session::destroy('flash_success'); ?>
            <?php endif; ?>

            <?php if (Session::has('flash_error')): ?>
                <div class="alert alert-error"><?= Session::get('flash_error') ?></div>
                <?php Session::destroy('flash_error'); ?>
            <?php endif; ?>

            <div class="inventario-list">
                <div class="search-container">
                    <div class="search-input-wrapper">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35"/>
                        </svg>
                        <input type="text" class="form-control" data-table-search="tabla-inventario" placeholder="Buscar por producto, código, bodega...">
                    </div>
                </div>
                <table class="table" id="tabla-inventario">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Stock</th>
                            <th>Bodega</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($inventario)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">No hay materiales registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($inventario as $item): ?>
                                <tr>
                                    <td><?= $item->getIdItem() ?></td>
                                    <td><?= htmlspecialchars($item->getCodProducto()) ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($item->getNombreProducto()) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge <?= $item->getCantidadDisponible() > 10 ? 'badge-aprobado' : ($item->getCantidadDisponible() > 0 ? 'badge-pendiente' : 'badge-rechazado') ?>">
                                            <?= $item->getCantidadDisponible() ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($item->getNombreBodega()) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>almacen/material/<?= $item->getIdItem() ?>/editar" class="btn btn-sm btn-secondary">Editar</a>
                                        <form method="POST" action="<?= BASE_URL ?>almacen/material/<?= $item->getIdItem() ?>/eliminar" style="display:inline;">
                                            <button type="submit" class="btn btn-sm" style="background:#fee2e2; color:#991b1b;" data-confirm-delete data-item-name="<?= htmlspecialchars($item->getNombreProducto()) ?>">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal Agregar Material -->
    <div id="modalAgregar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Agregar Nuevo Material</h2>
                <span class="modal-close" onclick="document.getElementById('modalAgregar').style.display='none'">&times;</span>
            </div>
            <form method="POST" action="<?= BASE_URL ?>almacen/inventario/agregar">
                <div class="form-grid">
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="nombre_producto">Nombre del Producto *</label>
                        <input type="text" id="nombre_producto" name="nombre_producto" required>
                    </div>

                    <div class="form-group">
                        <label for="cod_producto">Código de Producto *</label>
                        <input type="text" id="cod_producto" name="cod_producto" required>
                    </div>

                    <div class="form-group">
                        <label for="cantidad_disponible">Cantidad Disponible *</label>
                        <input type="number" id="cantidad_disponible" name="cantidad_disponible" required min="0">
                    </div>

                    <!-- Campos ocultos para bodega automática -->
                    <input type="hidden" name="nombre_bodega" value="Bodega Principal - Barrancabermeja">
                    <input type="hidden" name="cod_bodega" value="BP-BER-001">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalAgregar').style.display='none'">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar Material</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 85vh;
            overflow-y: auto;
        }
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: var(--gray-600);
        }
        .modal-close:hover {
            color: var(--gray-900);
        }
        .modal-footer {
            padding: 20px;
            border-top: 1px solid var(--gray-200);
            text-align: right;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            padding: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--gray-700);
        }
        .form-group input {
            padding: 10px;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 14px;
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
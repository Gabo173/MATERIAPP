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
                        <span class="user-role">Administrador</span>
                    </div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="<?= BASE_URL ?>admin/usuarios" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Gestión de Cuentas
                </a>
                <a href="<?= BASE_URL ?>admin/solicitudes" class="nav-item active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    Solicitudes
                </a>
                <a href="<?= BASE_URL ?>admin/registro" class="nav-item">
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

            <div class="solicitudes-list">
                <div class="search-container">
                    <div class="search-input-wrapper">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35"/>
                        </svg>
                        <input type="text" class="form-control" data-table-search="tabla-solicitudes-admin" placeholder="Buscar solicitudes...">
                    </div>
                </div>
                <table class="table" id="tabla-solicitudes-admin">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Solicitante</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Almacenista</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $item): ?>
                            <?php $solicitud = $item['solicitud']; ?>
                            <tr>
                                <td>#<?= $solicitud->getIdSolicitud() ?></td>
                                <td><?= htmlspecialchars($item['nombre_solicitante']) ?></td>
                                <td><?= htmlspecialchars($solicitud->getTipoMovimiento()) ?></td>
                                <td><?= date('d/m/Y', strtotime($solicitud->getFechaSolicitud())) ?></td>
                                <td>
                                    <span class="badge badge-<?= strtolower($solicitud->getEstado()) ?>">
                                        <?= htmlspecialchars($solicitud->getEstado()) ?>
                                    </span>
                                </td>
                                <td><?= $item['nombre_almacenista'] ? htmlspecialchars($item['nombre_almacenista']) : '-' ?></td>
                                <td>
                                    <?php if ($solicitud->getEstado() === 'Aprobado'): ?>
                                        <span title="Observaciones de aprobación"><?= htmlspecialchars(substr($solicitud->getObservacionesAlmacen() ?? 'Sin observaciones', 0, 50)) ?><?= strlen($solicitud->getObservacionesAlmacen() ?? '') > 50 ? '...' : '' ?></span>
                                    <?php elseif ($solicitud->getEstado() === 'Rechazado'): ?>
                                        <span title="Motivo del rechazo"><?= htmlspecialchars(substr($solicitud->getObservacionesAlmacen() ?? 'Sin motivo', 0, 50)) ?><?= strlen($solicitud->getObservacionesAlmacen() ?? '') > 50 ? '...' : '' ?></span>
                                    <?php else: ?>
                                        <span style="color: var(--gray-400);">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>admin/ver-solicitud/<?= $solicitud->getIdSolicitud() ?>" class="btn btn-sm btn-primary">Ver</a>
                                    <form method="POST" action="<?= BASE_URL ?>admin/solicitud/<?= $solicitud->getIdSolicitud() ?>/eliminar" style="display:inline;">
                                        <button type="submit" class="btn btn-sm" style="background:#fee2e2; color:#991b1b;" data-confirm-delete data-item-name="Solicitud #<?= $solicitud->getIdSolicitud() ?>">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
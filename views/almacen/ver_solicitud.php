<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud #<?= $solicitud->getIdSolicitud() ?> - MateriApp</title>
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
                <h1>Solicitud #<?= $solicitud->getIdSolicitud() ?></h1>
                <a href="<?= BASE_URL ?>almacen/solicitudes" class="btn btn-secondary">Volver</a>
            </div>

            <div class="solicitud-detalle">
                <div class="form-section">
                    <h2>Información General</h2>
                    <div class="info-grid">
                        <div>
                            <strong>Solicitante:</strong>
                            <p><?= htmlspecialchars($nombre_solicitante) ?></p>
                        </div>
                        <div>
                            <strong>Tipo de movimiento:</strong>
                            <p><?= htmlspecialchars($solicitud->getTipoMovimiento()) ?></p>
                        </div>
                        <div>
                            <strong>Fecha de solicitud:</strong>
                            <p><?= date('d/m/Y H:i', strtotime($solicitud->getFechaSolicitud())) ?></p>
                        </div>
                        <div>
                            <strong>Estado:</strong>
                            <p><span class="badge badge-<?= strtolower($solicitud->getEstado()) ?>"><?= htmlspecialchars($solicitud->getEstado()) ?></span></p>
                        </div>
                    </div>
                    
                    <div class="info-grid">
                        <div style="grid-column: span 2">
                            <strong>Justificación:</strong>
                            <p><?= htmlspecialchars($solicitud->getObservaciones()) ?></p>
                        </div>
                    </div>

                    <?php if (!empty($solicitud->getObservacionesAlmacen())): ?>
                    <div class="info-grid">
                        <div style="grid-column: span 2">
                            <strong>Motivo de <?= htmlspecialchars($solicitud->getEstado()) ?>:</strong>
                            <p><?= htmlspecialchars($solicitud->getObservacionesAlmacen()) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="form-section">
                    <h2>Materiales Solicitados</h2>
                    <div class="search-container">
                        <div class="search-input-wrapper">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input type="text" class="form-control" data-table-search="tabla-ver_solicitud" placeholder="Buscar...">
                        </div>
                    </div>
                    <table class="table" id="tabla-ver_solicitud">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Bodega</th>
                                <th>Cantidad Solicitada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalles as $detalle): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($detalle['nombre_producto']) ?></strong><br>
                                        <small>SKU: <?= htmlspecialchars($detalle['cod_producto']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($detalle['nombre_bodega']) ?></td>
                                    <td><?= $detalle['cantidad_solicitada'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($solicitud->getEstado() === 'Pendiente'): ?>
                    <div class="form-section">
                        <h2>Gestionar Solicitud</h2>
                        
                        <form method="POST" action="<?= BASE_URL ?>almacen/aprobar/<?= $solicitud->getIdSolicitud() ?>" style="display:inline-block; margin-right: 10px;">
                            <div class="form-group">
                                <label>Observaciones (opcional)</label>
                                <textarea name="observaciones_almacen" rows="2" placeholder="Observaciones de la aprobación" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" style="background: #10b981;">Aprobar Solicitud</button>
                        </form>

                        <form method="POST" action="<?= BASE_URL ?>almacen/rechazar/<?= $solicitud->getIdSolicitud() ?>" style="display:inline-block;">
                            <div class="form-group">
                                <label>Motivo del rechazo *</label>
                                <textarea name="observaciones_almacen" rows="2" placeholder="Motivo del rechazo" required class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-secondary" style="background: #ef4444; color: white;">Rechazar Solicitud</button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if (!empty($historial)): ?>
                    <div class="form-section">
                        <h2>Historial de Movimientos</h2>
                        <div class="search-container">
                        <div class="search-input-wrapper">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input type="text" class="form-control" data-table-search="tabla-ver_solicitud" placeholder="Buscar...">
                        </div>
                    </div>
                    <table class="table" id="tabla-ver_solicitud">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Estado Anterior</th>
                                    <th>Estado Nuevo</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historial as $mov): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($mov['fecha_accion'])) ?></td>
                                        <td><?= htmlspecialchars($mov['nombre_usuario']) ?></td>
                                        <td><?= htmlspecialchars($mov['accion']) ?></td>
                                        <td><?= $mov['estado_anterior'] ? htmlspecialchars($mov['estado_anterior']) : '-' ?></td>
                                        <td><?= htmlspecialchars($mov['estado_nuevo']) ?></td>
                                        <td><?= !empty($mov['observaciones_almacen']) ? htmlspecialchars($mov['observaciones_almacen']) : '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
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
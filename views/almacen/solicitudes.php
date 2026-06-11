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
                <a href="<?= BASE_URL ?>almacen/solicitudes" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'solicitudes') !== false && strpos($_SERVER['REQUEST_URI'], 'inventario') === false ? 'active' : '' ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    Solicitudes
                </a>
                <a href="<?= BASE_URL ?>almacen/inventario" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'inventario') !== false ? 'active' : '' ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 7h-3a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2H8a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
                        <path d="M10 7V5a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Inventario
                </a>
                <a href="<?= BASE_URL ?>almacen/perfil" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'perfil') !== false ? 'active' : '' ?>">
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
                <span class="badge badge-pendiente"><?= count($pendientes) ?> pendientes</span>
            </div>

            <?php if (Session::has('flash_success')): ?>
                <div class="alert alert-success"><?= Session::get('flash_success') ?></div>
                <?php Session::destroy('flash_success'); ?>
            <?php endif; ?>

            <?php if (Session::has('flash_error')): ?>
                <div class="alert alert-error"><?= Session::get('flash_error') ?></div>
                <?php Session::destroy('flash_error'); ?>
            <?php endif; ?>

            <div class="tabs">
                <button class="tab-btn active" onclick="showTab('pendientes')">Pendientes</button>
                <button class="tab-btn" onclick="showTab('aprobadas')">Aprobadas</button>
                <button class="tab-btn" onclick="showTab('rechazadas')">Rechazadas</button>
            </div>

            <div id="pendientes" class="tab-content active">
                <?php if (empty($pendientes)): ?>
                    <div class="empty-state">
                        <h3>No hay solicitudes pendientes</h3>
                    </div>
                <?php else: ?>
                    <div class="search-container">
                        <div class="search-input-wrapper">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input type="text" class="form-control" data-table-search="tabla-pendientes" placeholder="Buscar solicitudes pendientes...">
                        </div>
                    </div>
                    <table class="table" id="tabla-pendientes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Solicitante</th>
                                <th>Tipo</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendientes as $item): ?>
                                <tr>
                                    <td>#<?= $item['solicitud']->getIdSolicitud() ?></td>
                                    <td><?= htmlspecialchars($item['nombre_solicitante']) ?></td>
                                    <td><?= htmlspecialchars($item['solicitud']->getTipoMovimiento()) ?></td>
                                    <td><?= date('d/m/Y', strtotime($item['solicitud']->getFechaSolicitud())) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>almacen/ver/<?= $item['solicitud']->getIdSolicitud() ?>" class="btn btn-sm btn-primary">Gestionar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div id="aprobadas" class="tab-content">
                <?php if (empty($aprobadas)): ?>
                    <div class="empty-state">
                        <h3>No hay solicitudes aprobadas</h3>
                    </div>
                <?php else: ?>
                    <div class="search-container">
                        <div class="search-input-wrapper">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input type="text" class="form-control" data-table-search="tabla-aprobadas" placeholder="Buscar solicitudes aprobadas...">
                        </div>
                    </div>
                    <table class="table" id="tabla-aprobadas">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Solicitante</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($aprobadas as $item): ?>
                                <tr>
                                    <td>#<?= $item['solicitud']->getIdSolicitud() ?></td>
                                    <td><?= htmlspecialchars($item['nombre_solicitante']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($item['solicitud']->getFechaSolicitud())) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>almacen/ver/<?= $item['solicitud']->getIdSolicitud() ?>" class="btn btn-sm btn-secondary">Ver</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div id="rechazadas" class="tab-content">
                <?php if (empty($rechazadas)): ?>
                    <div class="empty-state">
                        <h3>No hay solicitudes rechazadas</h3>
                    </div>
                <?php else: ?>
                    <div class="search-container">
                        <div class="search-input-wrapper">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input type="text" class="form-control" data-table-search="tabla-rechazadas" placeholder="Buscar solicitudes rechazadas...">
                        </div>
                    </div>
                    <table class="table" id="tabla-rechazadas">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Solicitante</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rechazadas as $item): ?>
                                <tr>
                                    <td>#<?= $item['solicitud']->getIdSolicitud() ?></td>
                                    <td><?= htmlspecialchars($item['nombre_solicitante']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($item['solicitud']->getFechaSolicitud())) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>almacen/ver/<?= $item['solicitud']->getIdSolicitud() ?>" class="btn btn-sm btn-secondary">Ver</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <style>
        .tabs { margin-bottom: 20px; display: flex; gap: 10px; }
        .tab-btn { 
            padding: 10px 20px; 
            border: 1px solid var(--gray-200); 
            background: white; 
            cursor: pointer;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            color: var(--gray-700);
        }
        .tab-btn:hover { 
            background: var(--gray-100);
        }
        .tab-btn.active { 
            background: var(--primary-color); 
            color: white; 
            border-color: var(--primary-color);
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>

    <script>
        function showTab(id) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(id).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
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
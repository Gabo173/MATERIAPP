<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Solicitud - MateriApp</title>
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
                        <span class="user-role">Empleado</span>
                    </div>
                </div>
            </div>

<nav class="sidebar-nav">
                <a href="<?= BASE_URL ?>empleado/solicitudes" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    Solicitudes
                </a>
                <a href="<?= BASE_URL ?>empleado/nueva" class="nav-item active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    Nueva Solicitud
                </a>
                <a href="<?= BASE_URL ?>empleado/perfil" class="nav-item">
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
                <h1>Nueva Solicitud de Materiales</h1>
            </div>

            <div class="steps-container">
                <div class="step active">
                    <span class="step-number">1</span>
                    <span class="step-label">Información</span>
                </div>
                <div class="step">
                    <span class="step-number">2</span>
                    <span class="step-label">Materiales</span>
                </div>
                <div class="step">
                    <span class="step-number">3</span>
                    <span class="step-label">Confirmación</span>
                </div>
            </div>

            <form method="POST" action="<?= BASE_URL ?>empleado/crear" id="formSolicitud">
                <div class="form-section">
                    <h2>Tipo de movimiento</h2>
                    <div class="radio-group">
                        <label class="radio-card">
                            <input type="radio" name="tipo_movimiento" value="Salida" checked>
                            <span class="radio-label">Salida de material</span>
                        </label>
                        <label class="radio-card">
                            <input type="radio" name="tipo_movimiento" value="Entrada">
                            <span class="radio-label">Entrada de material</span>
                        </label>
                    </div>
                </div>

<div class="form-section">
                    <h2>Bodega <span id="bodegaLabel">origen</span></h2>
                    <input type="text" value="Bodega Principal - Barrancabermeja" disabled class="form-control">
                    <input type="hidden" name="bodega_destino" value="Bodega Principal - Barrancabermeja">
                </div>

<div class="form-section">
                    <h2>Fecha requerida</h2>
                    <?php date_default_timezone_set('America/Bogota'); ?>
                    <input type="date" name="fecha_requerida" required min="<?= date('Y-m-d') ?>" class="form-control">
                </div>

                <div class="form-section">
                    <h2>Justificación</h2>
                    <textarea name="justificacion" rows="3" placeholder="Describa el uso o destino de los materiales" required class="form-control"></textarea>
                </div>

<div class="form-section">
                    <h2>Materiales solicitados</h2>
                    
                    <!-- Inputs ocultos para enviar los materiales -->
                    <input type="hidden" name="materiales_json" id="materiales_json">
                    
                    <!-- Lista de materiales seleccionados -->
                    <table class="table" id="tablaSeleccionados" style="margin-bottom: 20px;">
                        <thead>
                            <tr>
                                <th>Material (SKU)</th>
                                <th>Bodega</th>
                                <th>Cantidad</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tbodySeleccionados">
                        </tbody>
                    </table>
                    
                    <div id="noHayMateriales" style="text-align: center; padding: 40px; color: var(--gray-600);">
                        <p>No hay materiales seleccionados</p>
                        <button type="button" class="btn btn-primary" onclick="abrirModalMateriales()" style="margin-top: 15px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle;">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                            Agregar materiales
                        </button>
                    </div>

                    <span id="contadorMateriales" style="display: none; color: var(--gray-600); font-size: 14px; margin-bottom: 10px;">0 materiales seleccionados</span>
                </div>

                <!-- Modal de materiales disponibles -->
                <div id="modalMateriales" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
                    <div style="background: white; border-radius: 12px; max-width: 900px; width: 90%; max-height: 80vh; overflow: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
                        <div style="padding: 20px; border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center;">
                            <h2 style="margin: 0; font-size: 20px;">Agregar Materiales</h2>
                            <button type="button" onclick="cerrarModalMateriales()" style="background: none; border: none; font-size: 28px; cursor: pointer; color: var(--gray-600);">&times;</button>
                        </div>
                        <div style="padding: 20px;">
                            <input type="text" id="buscadorMateriales" placeholder="Buscar material..." class="form-control" style="margin-bottom: 15px;">
                            <div class="search-container">
                        <div class="search-input-wrapper">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input type="text" class="form-control" data-table-search="tabla-nueva_solicitud" placeholder="Buscar...">
                        </div>
                    </div>
                    <table class="table" id="tabla-nueva_solicitud">
                                <thead>
                                    <tr>
                                        <th>Material (SKU)</th>
                                        <th>Bodega</th>
                                        <th>Disponible</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyDisponibles">
                                    <?php foreach ($inventario as $item): ?>
                                        <tr data-id="<?= $item->getIdItem() ?>" data-nombre="<?= strtolower(htmlspecialchars($item->getNombreProducto())) ?>" data-bodega="<?= htmlspecialchars($item->getNombreBodega()) ?>" data-stock="<?= $item->getCantidadDisponible() ?>">
                                            <td>
                                                <strong><?= htmlspecialchars($item->getNombreProducto()) ?></strong><br>
                                                <small>SKU: <?= htmlspecialchars($item->getCodProducto()) ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($item->getNombreBodega()) ?></td>
                                            <td><?= $item->getCantidadDisponible() ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="agregarDesdeModal(<?= $item->getIdItem() ?>, '<?= addslashes($item->getNombreProducto()) ?>', '<?= htmlspecialchars($item->getCodProducto()) ?>', '<?= htmlspecialchars($item->getNombreBodega()) ?>', <?= $item->getCantidadDisponible() ?>)">
                                                    Agregar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div style="padding: 20px; border-top: 1px solid var(--gray-200); text-align: right;">
                            <button type="button" class="btn btn-secondary" onclick="cerrarModalMateriales()">Cerrar</button>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="<?= BASE_URL ?>empleado/solicitudes" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Enviar solicitud</button>
                </div>
            </form>
        </main>
    </div>

<script>
        let materialesEnSolicitud = {};

        function abrirModalMateriales() {
            document.getElementById('modalMateriales').style.display = 'flex';
            document.getElementById('buscadorMateriales').value = '';
            filtrarMaterialesModal('');
        }

        function cerrarModalMateriales() {
            document.getElementById('modalMateriales').style.display = 'none';
        }

        function filtrarMaterialesModal(termino) {
            const busqueda = termino.toLowerCase();
            document.querySelectorAll('#tbodyDisponibles tr').forEach(fila => {
                const nombre = fila.getAttribute('data-nombre');
                const id = fila.getAttribute('data-id');
                
                if (materialesEnSolicitud[id] || (busqueda && !nombre.includes(busqueda))) {
                    fila.style.display = 'none';
                } else {
                    fila.style.display = '';
                }
            });
        }

        document.getElementById('buscadorMateriales').addEventListener('input', function(e) {
            filtrarMaterialesModal(e.target.value);
        });

        function agregarDesdeModal(id, nombre, sku, bodega, stock) {
            if (materialesEnSolicitud[id]) {
                alert('Este material ya está en la solicitud');
                return;
            }

            materialesEnSolicitud[id] = {
                nombre: nombre,
                sku: sku,
                bodega: bodega,
                stock: stock,
                cantidad: 1
            };

            renderizarSeleccionados();
            actualizarEstado();
        }

        function eliminarMaterial(id) {
            delete materialesEnSolicitud[id];
            renderizarSeleccionados();
            actualizarEstado();
        }

        function incrementarCantidad(id) {
            if (materialesEnSolicitud[id]) {
                if (materialesEnSolicitud[id].cantidad < materialesEnSolicitud[id].stock) {
                    materialesEnSolicitud[id].cantidad++;
                    renderizarSeleccionados();
                } else {
                    alert('No hay más disponibilidad de este material');
                }
            }
        }

        function decrementarCantidad(id) {
            if (materialesEnSolicitud[id]) {
                if (materialesEnSolicitud[id].cantidad > 1) {
                    materialesEnSolicitud[id].cantidad--;
                    renderizarSeleccionados();
                }
            }
        }

        function renderizarSeleccionados() {
            const tbody = document.getElementById('tbodySeleccionados');
            tbody.innerHTML = '';

            Object.keys(materialesEnSolicitud).forEach(id => {
                const mat = materialesEnSolicitud[id];
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        <strong>${mat.nombre}</strong><br>
                        <small>SKU: ${mat.sku}</small>
                    </td>
                    <td>${mat.bodega}</td>
                    <td>
                        <div class="cantidad-control">
                            <button type="button" class="btn-cantidad" onclick="decrementarCantidad(${id})">−</button>
                            <input type="number" name="materiales[${id}][cantidad]" 
                                   value="${mat.cantidad}" min="0" max="${mat.stock}" 
                                   class="cantidad-input" readonly>
                            <button type="button" class="btn-cantidad" onclick="incrementarCantidad(${id})">+</button>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn-remove" onclick="eliminarMaterial(${id})" title="Eliminar material">×</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            actualizarInputOculto();
        }

        function actualizarInputOculto() {
            const inputJson = document.getElementById('materiales_json');
            const materiales = [];
            
            Object.keys(materialesEnSolicitud).forEach(id => {
                const mat = materialesEnSolicitud[id];
                materiales.push({
                    id_item: id,
                    cantidad: mat.cantidad
                });
            });
            
            inputJson.value = JSON.stringify(materiales);
        }

        function actualizarEstado() {
            const noHayMateriales = document.getElementById('noHayMateriales');
            const tablaSeleccionados = document.getElementById('tablaSeleccionados');
            const contador = document.getElementById('contadorMateriales');
            const total = Object.keys(materialesEnSolicitud).length;

            if (total === 0) {
                noHayMateriales.style.display = 'block';
                tablaSeleccionados.style.display = 'none';
                contador.style.display = 'none';
            } else {
                noHayMateriales.style.display = 'none';
                tablaSeleccionados.style.display = 'table';
                contador.style.display = 'block';
                contador.textContent = total + ' material' + (total !== 1 ? 'es' : '') + ' seleccionado' + (total !== 1 ? 's' : '');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            actualizarEstado();
        });

        document.querySelectorAll('input[name="tipo_movimiento"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const label = document.getElementById('bodegaLabel');
                if (this.value === 'Salida') {
                    label.textContent = 'origen';
                } else {
                    label.textContent = 'destino';
                }
            });
        });

        document.getElementById('modalMateriales').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalMateriales();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                cerrarModalMateriales();
            }
        });

        document.getElementById('formSolicitud').addEventListener('submit', function(e) {
            const total = Object.keys(materialesEnSolicitud).length;
            if (total === 0) {
                e.preventDefault();
                alert('Debe agregar al menos un material a la solicitud');
                return false;
            }
            
            actualizarInputOculto();
        });
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







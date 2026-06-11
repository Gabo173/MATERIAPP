/**
 * MateriApp - JavaScript Principal
 * Funcionalidades interactivas para el sistema
 */

const MateriApp = {
    config: {
        baseUrl: '/',
        toastDuration: 4000,
        debounceDelay: 300
    },
    
    toast: {
        show(message, type = 'info', duration = null) {
            const toast = document.createElement('div');
            toast.className = 'toast toast-' + type;
            const icon = this.getIcon(type);
            toast.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' + icon + '</svg><span>' + message + '</span><button class="toast-close" onclick="this.parentElement.remove()">×</button>';
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('toast-hide');
                setTimeout(() => toast.remove(), 300);
            }, duration || MateriApp.config.toastDuration);
        },
        
        getIcon(type) {
            const icons = {
                success: '<circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/>',
                error: '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
                info: '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
                warning: '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>'
            };
            return icons[type] || icons.info;
        }
    },
    
    confirm: {
        async show(title, text = '¿Estás seguro?', confirmText = 'Sí, confirmar', cancelText = 'Cancelar') {
            if (typeof Swal !== 'undefined') {
                const result = await Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1e3a5f',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    language: 'es'
                });
                return result.isConfirmed;
            }
            return confirm(title + '\n\n' + text);
        }
    },
    
    validation: {
        init() {
            document.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => {
                    if (input.classList.contains('invalid')) {
                        this.validateField(input);
                    }
                });
            });
        },
        
        validateField(field) {
            const value = field.value.trim();
            const type = field.type;
            let isValid = true;
            let errorMessage = '';
            
            if (field.required && !value) {
                isValid = false;
                errorMessage = 'Este campo es requerido';
            } else if (type === 'email' && value && !this.isEmail(value)) {
                isValid = false;
                errorMessage = 'Ingresa un correo válido';
            } else if (type === 'number' && value && isNaN(value)) {
                isValid = false;
                errorMessage = 'Ingresa un número válido';
            } else if (field.pattern && value && !new RegExp(field.pattern).test(value)) {
                isValid = false;
                errorMessage = field.title || 'Formato inválido';
            }
            
            this.setFieldValidity(field, isValid, errorMessage);
            return isValid;
        },
        
        setFieldValidity(field, isValid, errorMessage = '') {
            field.classList.remove('invalid', 'valid');
            field.classList.add(isValid ? 'valid' : 'invalid');
            
            let errorEl = field.parentElement.querySelector('.error-message');
            
            if (!isValid && errorMessage) {
                if (!errorEl) {
                    errorEl = document.createElement('small');
                    errorEl.className = 'error-message';
                    field.parentElement.appendChild(errorEl);
                }
                errorEl.textContent = errorMessage;
            } else if (errorEl) {
                errorEl.remove();
            }
        },
        
        isEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        },
        
        validateForm(form) {
            let isValid = true;
            form.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
                if (!this.validateField(field)) {
                    isValid = false;
                }
            });
            return isValid;
        }
    },
    
    tableSearch: {
        init() {
            document.querySelectorAll('[data-table-search]').forEach(input => {
                const tableId = input.dataset.tableSearch;
                const table = document.getElementById(tableId);
                if (table) {
                    input.addEventListener('input', (e) => this.filter(table, e.target.value));
                }
            });
        },
        
        filter(table, query) {
            const rows = table.querySelectorAll('tbody tr');
            query = query.toLowerCase().trim();
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
            
            const visibleRows = Array.from(rows).filter(r => r.style.display !== 'none');
            const emptyState = table.parentElement.querySelector('.search-empty');
            
            if (visibleRows.length === 0 && query) {
                if (!emptyState) {
                    const msg = document.createElement('div');
                    msg.className = 'search-empty empty-state';
                    msg.innerHTML = '<svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg><h3>No se encontraron resultados</h3><p>Intenta con otro término de búsqueda</p>';
                    table.parentElement.appendChild(msg);
                }
            } else if (emptyState) {
                emptyState.remove();
            }
        }
    },
    
    ui: {
        init() {
            this.addAnimations();
            this.initTooltips();
            this.initDropdowns();
            this.initAutoCloseAlerts();
        },
        
        addAnimations() {
            document.querySelectorAll('.main-content > .form-section, .table-container, .card').forEach((el, i) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, i * 100);
            });
        },
        
        initTooltips() {
            document.querySelectorAll('[data-tooltip]').forEach(el => {
                el.addEventListener('mouseenter', (e) => {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'tooltip';
                    tooltip.textContent = el.dataset.tooltip;
                    document.body.appendChild(tooltip);
                    
                    const rect = el.getBoundingClientRect();
                    tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
                    tooltip.style.left = rect.left + (rect.width - tooltip.offsetWidth) / 2 + 'px';
                    
                    el._tooltip = tooltip;
                });
                
                el.addEventListener('mouseleave', () => {
                    if (el._tooltip) {
                        el._tooltip.remove();
                        el._tooltip = null;
                    }
                });
            });
        },
        
        initDropdowns() {
            document.querySelectorAll('.dropdown-toggle').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const dropdown = btn.nextElementSibling;
                    dropdown.classList.toggle('show');
                });
            });
            
            document.addEventListener('click', () => {
                document.querySelectorAll('.dropdown-menu.show').forEach(el => el.classList.remove('show'));
            });
        },
        
        initAutoCloseAlerts() {
            document.querySelectorAll('.alert-auto-close').forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.3s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        }
    },
    
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.validation.init();
            this.tableSearch.init();
            this.ui.init();
            this.attachDeleteConfirmations();
            this.initQuantityControls();
            console.log('MateriApp initialized');
        });
    },
    
    attachDeleteConfirmations() {
        document.querySelectorAll('[data-confirm-delete]').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const form = btn.closest('form');
                const itemName = btn.dataset.itemName || 'este elemento';
                
                const confirmed = await this.confirm.show(
                    'Eliminar elemento',
                    '¿Estás seguro de que deseas eliminar "' + itemName + '"? Esta acción no se puede deshacer.',
                    'Sí, eliminar',
                    'Cancelar'
                );
                
                if (confirmed && form) {
                    form.submit();
                }
            });
        });
    },
    
    initQuantityControls() {
        document.querySelectorAll('.cantidad-control').forEach(control => {
            const minusBtn = control.querySelector('.btn-menos');
            const plusBtn = control.querySelector('.btn-mas');
            const input = control.querySelector('.cantidad-input');
            
            if (minusBtn && plusBtn && input) {
                minusBtn.addEventListener('click', () => {
                    const min = parseInt(input.min) || 0;
                    const value = parseInt(input.value) || 0;
                    if (value > min) {
                        input.value = value - 1;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });
                
                plusBtn.addEventListener('click', () => {
                    const max = parseInt(input.max) || Infinity;
                    const value = parseInt(input.value) || 0;
                    if (value < max) {
                        input.value = value + 1;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });
                
                input.addEventListener('change', () => {
                    const min = parseInt(input.min) || 0;
                    const max = parseInt(input.max) || Infinity;
                    let value = parseInt(input.value) || 0;
                    
                    if (value < min) value = min;
                    if (value > max) value = max;
                    
                    input.value = value;
                });
            }
        });
    }
};

MateriApp.init();
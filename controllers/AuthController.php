<?php
/**
 * Controlador de Autenticación
 * Gestiona login, registro y control de sesiones
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

require_once ROOT_PATH . 'models/UsuarioModel.php';
require_once ROOT_PATH . 'models/CedulaAdministradorModel.php';
require_once ROOT_PATH . 'core/Validator.php';

class AuthController {
    private $usuarioModel;
    private $cedulaModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
        $this->cedulaModel = new CedulaAdministradorModel();
    }

    public function login() {
        if (Session::isLoggedIn()) {
            $this->redirectByRole();
            return;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['correo_empresarial'] ?? '';
            $password = $_POST['contrasena'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Correo y contraseña son obligatorios';
            } else {
                $usuario = $this->usuarioModel->findByEmail($email);

                if ($usuario && $usuario->getContrasena() === $password) {
                    Session::set('user_id', $usuario->getId());
                    Session::set('user_role', $usuario->getRol());
                    Session::set('user_name', $usuario->getNombresCompletos());
                    Session::set('user_email', $usuario->getCorreoEmpresarial());
                    Session::set('user_cedula', $usuario->getCedula());

                    $this->redirectByRole();
                    return;
                } else {
                    $error = 'Credenciales incorrectas';
                }
            }
        }

        View::render('auth/login', ['error' => $error]);
    }

public function registro() {
        if (Session::isLoggedIn()) {
            $this->redirectByRole();
            return;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator($_POST);
            
            $validator->validateRequired('cedula')
                     ->validateRequired('nombres_completos')
                     ->validateEmail('correo_empresarial')
                     ->validateRequired('celular')
                     ->validateRequired('rol')
                     ->validateRequired('contrasena', 'Contraseña')
                     ->validateMinLength('contrasena', 8, 'Contraseña');

            if ($validator->fails()) {
                $error = implode('<br>', $validator->errors());
            } else {
                $data = $validator->getData();
                
                if ($data['rol'] === ROLE_ADMIN && !$this->cedulaModel->exists($data['cedula'])) {
                    $error = 'La cédula ingresada no está autorizada para el rol de administrador';
                } else {
                    $usuario = new Usuario($data);
                    
                    if ($this->usuarioModel->findByEmail($data['correo_empresarial'])) {
                        $error = 'El correo empresarial ya está registrado';
                    } elseif ($this->usuarioModel->findByCedula($data['cedula'])) {
                        $error = 'La cédula ya está registrada';
                    } else {
                        try {
                            $this->usuarioModel->create($usuario);
                            Session::set('flash_success', 'Registro exitoso. Ahora puede iniciar sesión.');
                            header('Location: ' . BASE_URL . 'auth/login');
                            exit;
                        } catch (Exception $e) {
                            $error = 'Error al registrar: ' . $e->getMessage();
                        }
                    }
                }
            }
        }

        $cedulasAdmin = $this->cedulaModel->getAll();
        View::render('auth/registro', [
            'error' => $error,
            'success' => $success,
            'cedulasAdmin' => $cedulasAdmin
        ]);
    }

    public function logout() {
        Session::destroyAll();
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }

    public function unauthorized() {
        http_response_code(403);
        View::render('auth/unauthorized');
    }

private function redirectByRole() {
        $role = Session::getUserRole();
        
        switch ($role) {
            case ROLE_EMPLEADO:
                header('Location: ' . BASE_URL . 'empleado/solicitudes');
                exit;
            case ROLE_ALMACEN:
                header('Location: ' . BASE_URL . 'almacen/solicitudes');
                exit;
            case ROLE_ADMIN:
                header('Location: ' . BASE_URL . 'admin/usuarios');
                exit;
            default:
                header('Location: ' . BASE_URL . 'auth/login');
                exit;
        }
    }
}

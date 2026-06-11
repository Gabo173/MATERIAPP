<?php
/**
 * Clase Validator
 * Validación de datos de formularios
 * MateriApp - Sistema de Gestión de Solicitudes y Control de Materiales
 */

class Validator {
    private $errors = [];
    private $data = [];

    public function __construct($data) {
        $this->data = $data;
    }

    public function validateEmail($field, $required = true) {
        if (!$required && empty($this->data[$field])) {
            return $this;
        }

        if (empty($this->data[$field])) {
            $this->errors[$field] = "El campo {$field} es obligatorio";
        } elseif (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "El campo {$field} debe ser un correo válido";
        }

        return $this;
    }

    public function validateRequired($field, $fieldName = null) {
        $fieldName = $fieldName ?? $field;
        
        if (empty($this->data[$field])) {
            $this->errors[$field] = "El campo {$fieldName} es obligatorio";
        }

        return $this;
    }

    public function validateMinLength($field, $min, $fieldName = null) {
        $fieldName = $fieldName ?? $field;
        
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field] = "El campo {$fieldName} debe tener al menos {$min} caracteres";
        }

        return $this;
    }

    public function validateNumeric($field, $fieldName = null) {
        $fieldName = $fieldName ?? $field;
        
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "El campo {$fieldName} debe ser numérico";
        }

        return $this;
    }

    public function validatePhone($field, $fieldName = null) {
        $fieldName = $fieldName ?? $field;
        
        if (!empty($this->data[$field]) && !preg_match('/^[0-9+\-\s()]+$/', $this->data[$field])) {
            $this->errors[$field] = "El campo {$fieldName} debe ser un número de teléfono válido";
        }

        return $this;
    }

    public function fails() {
        return !empty($this->errors);
    }

    public function errors() {
        return $this->errors;
    }

    public function getError($field) {
        return $this->errors[$field] ?? null;
    }

    public function getData() {
        return $this->data;
    }
}

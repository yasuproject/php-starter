<?php

class Session {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        self::init();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        self::init();
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key) {
        self::init();
        return isset($_SESSION[$key]);
    }

    public static function remove($key) {
        self::init();
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        self::init();
        session_destroy();
        $_SESSION = [];
    }

    public static function isLoggedIn() {
        self::init();
        return isset($_SESSION['admin_id']);
    }

    public static function requireAuth() {
        if (!self::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    public static function flash($key, $message = null) {
        self::init();
        if ($message !== null) {
            $_SESSION['flash'][$key] = $message;
        } else {
            $value = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $value;
        }
    }
}

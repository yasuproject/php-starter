<?php

class Session {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            // Security settings
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', 1);
            ini_set('session.cookie_samesite', 'Strict');
            ini_set('session.use_strict_mode', 1);
            ini_set('session.gc_maxlifetime', 3600);
            
            session_start();
            
            // Regenerate session ID periodically
            if (!isset($_SESSION['last_regeneration'])) {
                self::regenerateSession();
            } else if (time() - $_SESSION['last_regeneration'] > 300) {
                self::regenerateSession();
            }
            
            // Validate session
            if (isset($_SESSION['ip_address']) && $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
                self::destroy();
                header('Location: /login');
                exit;
            }
            
            if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
                self::destroy();
                header('Location: /login');
                exit;
            }
        }
    }
    
    private static function regenerateSession() {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
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
        $_SESSION = [];
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
    }

    public static function isLoggedIn() {
        self::init();
        return isset($_SESSION['admin_id']) && isset($_SESSION['login_time']);
    }

    public static function requireAuth() {
        self::init();
        if (!self::isLoggedIn()) {
            self::flash('error', 'Please login first');
            header('Location: /login');
            exit;
        }
        
        // Check session timeout (30 minutes)
        if (time() - ($_SESSION['login_time'] ?? 0) > 1800) {
            self::destroy();
            self::flash('error', 'Session expired. Please login again.');
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

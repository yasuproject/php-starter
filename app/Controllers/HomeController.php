<?php

require_once __DIR__ . '/../Config/Session.php';

class HomeController {
    public function index() {
        Session::init();
        
        // Redirect to dashboard if logged in, otherwise to login page
        if (Session::isLoggedIn()) {
            header('Location: /admin/dashboard');
        } else {
            header('Location: /login');
        }
        exit;
    }
}

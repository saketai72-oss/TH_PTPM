<?php

class SessionLogger {
    // Log user actions to PHP Session
    public static function log($action) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_actions'])) {
            $_SESSION['user_actions'] = [];
        }

        // Add action with timestamp
        $_SESSION['user_actions'][] = [
            'action' => $action,
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
        ];
    }

    // Retrieve all logged actions
    public static function getActions() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user_actions'] ?? [];
    }

    // Clear session history
    public static function clear() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_actions'] = [];
    }
}

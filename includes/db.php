<?php
require_once dirname(__DIR__) . '/config.php';

function getDB() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die('<div style="font-family:sans-serif;padding:20px;background:#fee;border:1px solid #c00;color:#c00;margin:20px;">
                <h3>Database Error</h3><p>' . $conn->connect_error . '</p>
                <p>Please run <a href="' . SITE_URL . '/setup.php">setup.php</a> first.</p></div>');
        }
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}

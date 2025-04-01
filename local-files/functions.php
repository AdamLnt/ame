<?php

function is_connected() {
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: login_page.html"); // Redirige si non connecté
        exit;
    }
}
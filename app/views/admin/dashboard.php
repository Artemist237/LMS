<?php 
require_once __DIR__ . '/../../core/AuthGuard.php';
restrictTo(['admin']); // Seul le promoteur passera cette ligne
?>
<?php
require 'vendor/autoload.php';
try {
    $mongo = new MongoDB\Client('mongodb://localhost:27017');
    $db = $mongo->investhub;
} catch(Exception $e) {
    exit('MongoDB Connection Error: '.$e->getMessage());
}
?>
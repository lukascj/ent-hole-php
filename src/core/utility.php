<?php

function load_env() {
    $path = '.env';
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach($lines as $line) {
        // Skippa kommentarer
        if(strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key-value pairs
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        // Ta bort citattecken
        if(preg_match('/^"(.*)"$/', $value, $matches)) {
            $value = $matches[1];
        } elseif(preg_match("/^'(.*)'$/", $value, $matches)) {
            $value = $matches[1];
        }

        // Set env variabel
        if(!isset($_ENV[$name])) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// Används för att kolla om något fält är tomt
function any_empty($array) {
    foreach($array as $e) { if(empty($e)) return true; }
    return false;
}
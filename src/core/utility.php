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
function any_empty($array, $check = 'all') {
    if($check == 'all') {
        foreach($array as $e) { if(empty($e)) return true; }
    } elseif(is_array($check)) {
        foreach($check as $k) { if(!isset($array[$k]) || empty($array[$k])) return true; }
    } else {
        throw new Exception("Check input is not an array.");
    }
    return false;
}

function protect_route($session, $level = 'logged-in') {
    if(!in_array($level, ['logged-in', 'admin'])) {
        throw new Exception("Faulty level-param for route protection.");
    }
    // Gå till inlogg-formulär om ej inloggad
    if(!isset($session['logged-in']) || !$session['logged-in']) {
        header('location: /auth');
        exit();
    }
    if($level == 'admin') {
        if(!isset($session['admin']) || !$session['admin']) {
            header('location: /?error=no-access');
            exit();
        }
    }
}
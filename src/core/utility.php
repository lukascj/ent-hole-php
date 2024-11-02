<?php
// Används för att kolla om något fält är tomt
function any_empty($array) {
    foreach($array as $e) { if(empty($e)) return true; }
    return false;
}
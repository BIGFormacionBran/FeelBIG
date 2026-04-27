<?php
$title = "Minijuegos Saludables";
$viewAllLink = "index.php?page=minijuegos";

$items = [
    [
        "id"    => "1", 
        "type"  => "minijuego", // Esto define a dónde irá el enlace
        "name"  => "Salud Canarias Gaming", 
        "img"   => "game1.jpg", 
        "badge" => "Interactivo"
    ],
    [
        "id"    => "2", 
        "type"  => "minijuego", 
        "name"  => "Reto Nutrición", 
        "img"   => "game2.jpg", 
        "badge" => "Interactivo"
    ]
];

include __DIR__ . '/../carousel_template.php';
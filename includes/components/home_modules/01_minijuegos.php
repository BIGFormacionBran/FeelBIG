<?php
$title = "Minijuegos Saludables";
$viewAllLink = "/minijuegos";

$items = [
    [
        "id"          => "1", 
        "type"        => "minijuegos", 
        "name"        => "Salud Canarias Gaming", 
        "img"         => "game1.jpg", 
        "badge"       => "Interactivo",
        "description" => "Esta es la descripción completa del juego de Canarias.",
        "extra_info"  => ["Dificultad" => "Fácil", "Tiempo" => "5 min"]
    ],
    [
        "id"          => "2", 
        "type"        => "minijuegos", 
        "name"        => "Reto Nutrición", 
        "img"         => "game2.jpg", 
        "badge"       => "Interactivo",
        "description" => "Aprende nutrición con este reto dinámico.",
        "extra_info"  => ["Dificultad" => "Media", "Tiempo" => "10 min"]
    ]
];

include __DIR__ . '/../carousel_template.php';
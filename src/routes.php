<?php
/**
 * Rutas
 */
    return [
        'get' => '\/user\/\d+\/accommodations(\/\d+)?:Accommodations@showAll',
        'post' => '\/user\/\d+\/accommodations:Accommodations@newAccommodation',
        'put' => '\/user\/\d+\/accommodations\/\d+:Accommodations@updateAccommodation'
    ];

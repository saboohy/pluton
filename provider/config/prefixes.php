<?php

/**
 *  PlutonPHP - Prefikslər
 *  -----------------------------------------------------
 *  İstifadə oluna biləcək siniflərin prefiks dəyərlərinə
 *  görə qovluq istiqamətlərini təşkil edir. Mövcud sinif
 *  adındakı prefiks dəyərləri dir massivindəki 
 *  dəyərlərlə əvəz olunacaq.
 */

return [

    /**
     *  İstifadə oluna biləcək prefikslər...
     */
    'prefix' => [
        '/App\/Controllers/',
        '/App\/Middlewares/',
        '/App\/Models/',
        '/Pluton/'
    ],

    /**
     *  Prefiksləri əvəz edəcək dəyərlər...
     */
    'dir' => [
        'http/Controllers',
        'http/Middlewares',
        'visualize/Models',
        'source'
    ]
];
<?php

/**
 *  PlutonPHP - Köməkçi avtoyükləyici
 *  -----------------------------------------------------
 *  Bu avtoyükləyici istifadəçiyə aid olmayan pluton
 *  köməkçi funksiyaları ifadə edəcək. ../source/helpers
 *  qovluğu içərisindəki köməkçiləri daxil edəcək.
 */

# Qovluq yoxlanışı.
$rootHelpers = scandir(SOURCE . 'Helpers/');

# Qayıdan massivin ilk iki dəyərini silsin.
$rootHelpers = array_diff($rootHelpers, ['.', '..']);

# Qalan massiv üzvlərini dövrə salmaq.
foreach($rootHelpers as $helper) {

    # Faylın tam istiqaməti.
    $rootHelperFileDir = SOURCE . 'Helpers/' . $helper;

    # Mövcud olduğu təqdirdə daxil olsun.
    if(file_exists($rootHelperFileDir)) include $rootHelperFileDir;
}
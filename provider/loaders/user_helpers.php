<?php

/**
 *  PlutonPHP - Köməkçi avtoyükləyici
 *  -----------------------------------------------------
 *  Bu avto yükləyici istifadəçiyə aiddir. helpers adlı
 *  qovluqda mövcud olan bütün faylları daxil edəcək.
 */

# Qovluq yoxlanışı.
$userHelpers = scandir(HELPER);

# Qayıdan massivin ilk iki dəyərini silsin.
$userHelpers = array_diff($userHelpers, ['.', '..']);

# Qalan massiv üzvlərini dövrə salmaq.
foreach($userHelpers as $helper) {

    # Faylın tam istiqaməti.
    $userHelperFileDir = HELPER . $helper;

    # Mövcud olduğu təqdirdə daxil olsun.
    if(file_exists($userHelperFileDir)) include $userHelperFileDir;
}
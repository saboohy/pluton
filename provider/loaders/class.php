<?php

/**
 *  PlutonPHP - Sinif avtoyükləyici
 *  -----------------------------------------------------
 *  Mövcud sinfi aldıqdan sonra sinfə aid olan faylı
 *  daxil edəcək.
 *  
 *  @param string $currentClass
 *  @return void
 */

spl_autoload_register(function(string $currentClass) : void {
    
    # Mövcud sinif adını aid olduğu fayl (istiqamətilə) adına çevirmə.
    $currentClassFile = classToDir($currentClass);
    
    # Fayl mövcuddursa daxil etsin.
    if(file_exists($currentClassFile)) require $currentClassFile;
    
});
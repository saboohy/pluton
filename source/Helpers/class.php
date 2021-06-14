<?php

/**
 *  Aldığı dəyəri(sinif adını) qovluq istiqamətinə çevirəcək.
 * 
 *  @param string $className
 *  @return string 
 */
if(!function_exists('classToDir')) {

    function classToDir(string $className) : string {

        # Prefiks məlumatları.
        $config = include ROOT . 'provider/config/prefixes.php';

        # Gələn sinif adında `\` simvollarını `/` simvolu ilə əvəzləmə.
        $className = str_replace('\\', '/', $className);

        # Sinif adındakı prefiksləri aid oluqları qovluq istiqamətlərilə əvəzləmə.
        $className = preg_replace($config['prefix'], $config['dir'], $className) . '.php';

        # Dəyəri qaytarsın.
        return $className;
    }
}

/**
 *  Aldığı `namespace` dəyərindən sinif adını döndürəcək.
 * 
 *  @param string $namespace
 *  @return string
 */
if(!function_exists('getClassName')) {

    function getClassName(string $namespace) : string {
        
        # Gələn dəyəri `\` simvoluna görə massivə çevirsin.
        $parsedNameSpace = explode('\\', $namespace);

        # Qayıdan massivin sonuncu üzvünü (indeksini) alsın.
        $getClassName = array_key_last($parsedNameSpace);

        # Massivin sonuncu üzvünü qaytarsın.
        return $parsedNameSpace[$getClassName];
    }
}
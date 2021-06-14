<?php

/**
 *  languages.php faylında dönən massivdən
 *  aldığı arqumentə görə massivi qaytaracaq.
 * 
 *  @param string $key
 *  @return array
 */
if(!function_exists('lang')) {

    function lang(string $key) : array {

        $config = include CONFIG . 'language.php';
        return $config[$key];
    }
}
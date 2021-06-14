<?php

/**
 *  Aldığı (URI) dəyərində parametr olduğu 
 *  təqdirdə lazım olan dəyərlərlə əvəz edəcək
 * 
 *  @param string $uri
 *  @return string
 */
if(!function_exists('uriParamsConverter')) {

    function uriParamsConverter(string $uri) : string {

        /**
         *  Arqumentlə gələn dəyərdə parametrləri aid 
         *  olduğu dəyərlər əvəzləmə.
         */
        $uri = preg_replace('/{([a-zA-Z]+)}/', '([a-zA-Z0-9:@#-_,.]+)', $uri);

        # Alınan dəyəri RegEx yoxlama üçün standarda uyğunlaşdırma
        $uri = '/^' . str_replace('/', '\/', $uri) . '$/';

        # Dəyəri döndürmə
        return $uri;
    }   
};
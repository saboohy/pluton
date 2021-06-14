<?php

/**
 *  Aldığı massiv dəyərə görə xəbərdarlıq şablonu yazdıracaq.
 * 
 *  @param array $data
 *  @return void 
 */

if(!function_exists('warning')) {

    function warning(array $data) : void {

        extract($data);
        die("<b>{$title}</b> {$hint}");
    }
}
<?php

/**
 *  Aldığı istiqamət dəyərini Uri sinfinə göndərəcək.
 * 
 *  @param string $uri
 *  @return object
 */
if(!function_exists('uri')) {

    function uri(string $uri) : object {

        return new \Pluton\Library\Header\Uri($uri);
    }
}
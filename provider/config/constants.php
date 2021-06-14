<?php

/**
 *  PlutonPHP - Sabitlər
 *  -----------------------------------------------------
 *  Layihə daxili qovluq istiqamətləri və bəzi http istək
 *  dəyərlərinə sabit şəkilcə müraciəti təmin edəcək.
 */

# Mövcud HTTP istiqaməti.
define('HTTP_PATH',     $_SERVER['REQUEST_URI'], true);

# Mövcud HTTP metod dəyəri.
define('HTTP_METHOD',   $_SERVER['REQUEST_METHOD'], true);

# Layihənin kök istiqaməti.
define('ROOT',          $_SERVER['DOCUMENT_ROOT'] . '/', true);

# assets qovluq istiqaməti.
define('ASSET',         ROOT . 'assets/', true);

# config qovluq istiqaməti.
define('CONFIG',        ROOT . 'config/', true);

# helper qovluq istiqaməti.
define('HELPER',        ROOT . 'helpers/', true);

# http qovluq istiqaməti.
define('HTTP',          ROOT . 'http/', true);

# controllers qovluq istiqaməti.
define('CONTROLLER',    HTTP . 'Controllers/', true);

# middleware qovluq istiqaməti.
define('MIDDLEWARE',    HTTP . 'Middlewares/', true);

# source qovluq istiqaməti.
define('SOURCE',        ROOT . 'source/', true);

# visualize qovluq istiqaməti.
define('VISUALIZE',     ROOT . 'visualize/', true);

# models qovluq istiqaməti.
define('MODEL',         VISUALIZE . 'Models/', true);

# views qovluq istiqaməti.
define('VIEW',          VISUALIZE . 'Views/', true);
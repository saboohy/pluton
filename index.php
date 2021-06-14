<?php

/**
 *  PlutonPHP - The MVC Framework
 *  -----------------------------------------------------
 *  @author     Sabuhi Alizada <sabuhi.alizada@yandex.ru>
 *  @source     https://github.com/sabuhiali/pluton
 *  @link       https://plutonphp.com
 *  @license    MIT
 *  @version    1.0.0
 *  @copyright  Pluton © 2021
 */

# Tip mütləqliyi.
declare(strict_types=1);

# Minimum PHP versiyası.
define('PHP_MIN_VERSION', '7.2', true);

/**
 *  PHP versiya kontrolu
 *  -----------------------------------------------------
 *  Sistemdə qurulmuş mövcud PHP versiyası 7.2 olacağı
 *  təqdirlə layihə işə salına bilər.
 */
if(!version_compare(PHP_VERSION, PHP_MIN_VERSION, '>=')) {

    die('<span>Mövcud PHP versiyanız: <b>' . PHP_VERSION . '</b>. Minimum <b>7.2</b> olmalıdır.</span>');
}

# Təminat.
include __DIR__ . '/provider/start.php';

# Pluton hazır...
\Pluton\App::ready();
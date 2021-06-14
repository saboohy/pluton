<?php

use \Pluton\Http\Router;

Router::set('GET', '/', 'Welcome.main');

Router::execute();
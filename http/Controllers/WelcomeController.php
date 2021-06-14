<?php

namespace App\Controllers;

use \Pluton\Http\Controller;

class WelcomeController extends Controller {

    public function main() {

        $this->view('welcome');
    }
}
<?php

namespace App\Controllers;

/**
 * Class Moderator - Represents all the functionalities that aa moderator has
 * @package App\Controllers
 *
 * @version 1.0
 */
class Moderator extends PrivilegedUser
{

    /**
     * A method to show main moderator page
     */
    public function index()
    {
        $this->showView('modMenu');
    }

    /**
     * An optional method which a class can implement if additional data is required by the showView method
     * @return array
     */
    protected function showAdditionalData() {
        return ['welcomeMessage' => "Welcome, {$this->session->get('username')} <br> <div style='color: blue'>Moderator</div>"];
    }

}

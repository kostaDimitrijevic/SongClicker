<?php

namespace App\Controllers;
use App\Models\UserModel;

/**
 * Class Login - For logging in every type of registered user
 * @package App\Controllers
 *
 * @version 1.0
 */
class Login extends BaseController
{
    /**
     * A method to show login page
     */
    public function index()
    {
        $this->showView('login');
    }

    /**
     * An optional method which a class can implement if additional data is required by the showView method
     *
     * @return array
     */
    protected function showAdditionalData()
    {
        return ['footerPart' => view("pages/loginFooter")];
    }

    /**
     * A method that checks user credentials, return errors if there are problems or redirect to
     * appropriate user type controller
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|void
     */
    public function checkUserCredentials()
    {
        if(!$this->validate([
            'username' => 'trim|required',
            'password' => 'trim|required'
        ])){
            return $this->showView('login', ['errors' => $this->validator->getErrors()]);
        }

        /**
         * A user model representing all types of users from database
         */
        $userModel = new UserModel();
        $user = $userModel->find($this->request->getVar('username'));

        if($user == null)
        {
            return $this->showView('login', ['errors' => ['username' => "Wrong username"]]);
        }
        if($user->password != $this->request->getVar('password'))
        {
            return $this->showView('login', ['errors' => ['password' => "Wrong password"]]);
        }

        $this->session->set("username", $this->request->getVar('username'));
        $this->session->set("type", $user->type);

        if($user->type == "admin"){
            return redirect()->to(base_url("Administrator"));
        }
        if($user->type == "mod") {
            return redirect()->to(base_url("Moderator"));
        }
        return redirect()->to(base_url("User"));
    }

}
<?php

namespace App\Controllers;

class User extends BaseController
{
    public function login(): string
    {
        if(session()->get('id') != null){
            return redirect()->to('public/dashboard');
        }
        return view('login');
    }

    public function validate_login(){
        $user = $_POST['username'];
        $pass = $_POST['password'];
        //$this->load->model('user');
        $usermodel = model('UserModel');
        $login = $usermodel->login($user, $pass);
        if(!empty($login)){
            $session = session();
            
            $session->set('username', $login[0]->username);
            $session->set('id', $login[0]->id);
            
            return redirect()->to('public/dashboard');
            
        } else {
            return view('login', ['error' => 'Invalid username or password']);
        }
    }

    public function logout(){
        $session = session();
        $session->destroy();
        return redirect()->to('public/');
    }
}

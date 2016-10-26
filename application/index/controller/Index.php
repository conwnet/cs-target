<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use app\index\model;

class Index extends Controller
{
    public function login() {
        if(input('username')) {
            $username = input('post.username');
            $password = input('post.password');
            if(!($password && $username))
                return $this->redirect('/login');
            $user = model\User::get(['username' => $username]);
            if($user && hash_crypt($password) == $user->password) {
                session('id', $user->id);
                session('power', $user->power);
                return $this->redirect('/user');
            } else {
                Session::flash('message', 'denied');
                return $this->redirect('/login');
            }
        }
        return $this->fetch();
    }

    public function i_sau_login() {
        if(input('post.username')) {
            $username = input('post.username');
            $password = input('post.password');
            $result = i_sau_validate($username, $password);
            if($result == 'ok') {
                $user = model\User::get(['username' => $username]);
                if(!$user) $user = $this->add_user($username);
                if(!$user) return $this->error('数据库错误！');
                session('id', $user->id);
                session('power', 1);
                return $this->redirect('/user');
            } else if($result == 'denied') {
                Session::flash('message', 'denied');
                return $this->redirect('/ilogin');
            } else if($result == 'timeout') {
                Session::flash('message', 'timeout');
                return $this->redirect('/ilogin');
            } else {
                Session::flash('message', 'unknow');
                return $this->redirect('/ilogin');
            }
        } else {
            return $this->fetch();
        }
    }

    private function add_user($username) {
        $user = new model\User();
        $user->username = $username;
        if($user->save())
            return $user;
        return NULL;
    }

    public function logout() {
        Session::set('id', 0);
        return $this->redirect('/');
    }

}

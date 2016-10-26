<?php
namespace app\index\controller;
use think\Session;
use app\index\model;

class User extends Access
{
    public function user($id=0) {
        if(session('power') || !model\User::get(['id' => $id]))
            $id = session('id');
        $user = model\User::get($id);
        if(session('power') && $user->password == '') Session::flash('message', 'no_password');
        if(input('post.number') == $user->username) {
            if(hash_crypt(input('post.password')) == $user->password || $user->password == '') {
                $user->name = input('post.name');
                $user->sex = input('post.sex');
                $user->birthday = input('post.birthday');
                $user->email = input('post.email');
                $user->team_id = input('post.team_id');
                $user->phone = input('post.phone');
                $user->address = input('post.address');
                $user->remark = input('post.remark');
                if(input('post.new_password') != '' && input('post.new_password') == input('post.re_password'))
                    $user->password = hash_crypt(input('post.new_password'));
                if($user->save())
                    Session::flash('message', 'update_success');
            } else {
                Session::flash('message', 'denied');
            }
            return $this->redirect('/user');
        }

        $teams = model\Team::select();
        $this->assign('teams', $teams);
        $this->assign('user', $user);
        $this->assign('title', '个人信息');
        return $this->fetch();
    }

    public function users($page=0, $team=0, $type=0) {
        if(session('power')) return $this->redirect('/user');
        if($page < 0) $page = 0;
        $users = model\User::where(['power' => 1]);
        if($team) $users = $users->where(['team_id' => $team]);
        if($type) $users = $users->alias('u')->join('cs_target t', 'u.id=t.user_id')->where(['type_id' => $type]);
        $users = $users->limit($page * 10, 10)->select();

        $_teams = model\Team::order('year, number')->select();
        $teams = [];
        foreach($_teams as $v)
            $teams[$v->year][] = $v;

        $types = model\Type::order('id desc')->select();

        $this->assign('types', $types);
        $this->assign('teams', $teams);
        $this->assign('users', $users);
        $this->assign('page', $page);
        $this->assign('team', $team);
        $this->assign('type', $type);
        $this->assign('title', '用户列表');

        return $this->fetch();
    }

    public function delete($id=0) {
        if(session('power'))
            return $this->redirect('/user');
        $user = model\User::get(['id' => $id]);
        if($user) $user->delete();
        return $this->redirect('/users');
    }

}

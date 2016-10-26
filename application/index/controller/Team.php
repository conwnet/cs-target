<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use app\index\model;

class Team extends Controller
{
    public function teams() {
        if(session('power'))
            return $this->redirect('/user');
        $teams = model\Team::order('number')->select();
        $this->assign('teams', $teams);
        $this->assign('title', '班级列表');
        return $this->fetch();
    }

    public function team($id=0) {
        if(session('power'))
            return $this->redirect('/user');
        $team = model\Team::get(['id' => $id]);
        if(input('post.id')) {
            $team = model\Team::get(input('post.id'));
            if($team) {
                $team->number = input('post.number');
                $team->name = input('post.name');
                $team->year = input('post.year');
                $team->user_sum = input('post.user_sum');
                if($team->save()) {
                    Session::flash('message', 'update_success');
                    $this->redirect('/target/' . $team->id);
                }
            } else {
                $team = new model\Team();
                $team->number = input('post.number');
                $team->name = input('post.name');
                $team->year = input('post.year');
                $team->user_sum = input('post.user_sum');
                if($team->save()) {
                    Session::flash('message', 'add_success');
                    $this->redirect('/team/' . $team->id);
                }
            }
        }
        $this->assign('team', $team);
        $this->assign('title', '班级信息');

        return $this->fetch();
    }

    public function delete($id=0) {
        if(session('power'))
            return $this->redirect('/user');
        $team = model\Team::get(['id' => $id]);
        if($team) $team->delete();
        return $this->redirect('/teams');
    }
}

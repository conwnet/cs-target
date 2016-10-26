<?php
namespace app\index\controller;
use think\Controller;
use app\index\model;
use think\Session;

class Target extends Controller
{
    public function targets($id=0) {
        if(session('power') || !model\User::get(['id' => $id])) $id = session('id');
        $user = model\User::get(['id' => $id]);
        $targets = model\Target::where(['user_id' => $id])->select();

        $this->assign('user', $user);
        $this->assign('targets', $targets);
        $this->assign('title', '规划列表');
        return $this->fetch();
    }

    public function target($id=0) {
        $target = model\Target::get(['id' => $id]);
        if($id && (!$target || (session('power') && $target->user_id != session('id'))))
            return $this->redirect('/target');
        if(input('post.id')) {
            $target = model\Target::get(input('post.id'));
            if($target) {
                if(session('power') && $target->user_id != session('id'))
                    return $this->redirect('/target');
                $target->title = input('post.title');
                $target->start_time = input('post.start_time');
                $target->achieve_time = input('post.achieve_time');
                $target->detail = input('post.detail');
                $target->type_id = input('post.type_id');
                if($target->save()) {
                    Session::flash('message', 'update_success');
                    $this->redirect('/target/' . $target->id);
                }
            } else {
                $target = new model\Target();
                $target->title = input('post.title');
                $target->start_time = input('post.start_time');
                $target->achieve_time = input('post.achieve_time');
                $target->detail = input('post.detail');
                $target->type_id = input('post.type_id') ? input('post.type_id') : 1;
                $target->user_id = session('id');
                if($target->save()) {
                    Session::flash('message', 'add_success');
                    $this->redirect('/target/' . $target->id);
                }
            }
        }
        $types = model\Type::select();
        $this->assign('types', $types);
        $this->assign('target', $target);
        $this->assign('title', '我的目标');

        return $this->fetch();
    }


    public function delete($id=0) {
        $target = model\Target::get(['id' => $id]);
        if($target && $target->user_id == session('id'))
            $target->delete();
        return $this->redirect('/targets');
    }
}

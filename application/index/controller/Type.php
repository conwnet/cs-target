<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use app\index\model;

class Type extends Controller
{
    public function types() {
        if(session('power'))
            return $this->redirect('/user');
        $types = model\Type::order('id desc')->select();
        $this->assign('types', $types);
        $this->assign('title', '目标类型');
        return $this->fetch();
    }

    public function type($id=0) {
        if(session('power'))
            return $this->redirect('/user');
        $type = model\Type::get(['id' => $id]);
        if(input('post.id')) {
            $type = model\Type::get(input('post.id'));
            if($type) {
                $type->name = input('post.name');
                if($type->save()) {
                    Session::flash('message', 'update_success');
                    $this->redirect('/type/' . $type->id);
                }
            } else {
                $type = new model\Type();
                $type->name = input('post.name');
                if($type->save()) {
                    Session::flash('message', 'add_success');
                    $this->redirect('/type/' . $type->id);
                }
            }
        }
        $this->assign('type', $type);
        $this->assign('title', '班级信息');

        return $this->fetch();
    }

    public function delete($id=0) {
        if(session('power'))
            return $this->redirect('/user');
        $type = model\Type::get(['id' => $id]);
        if($id != 1 && $type) $type->delete();
        return $this->redirect('/types');
    }
}

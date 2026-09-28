<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/29
 * Time: 17:06
 */

namespace app\index\controller;

use app\cms\model\Column;
use app\cms\model\Document;
use app\cms\model\Page;

class About extends Home
{

    //关于我们
    public function index()
    {
//        $page = Page::find(1);
//        $this->assign('info', $page);

        return $this->fetch();
    }

    //TERMS OF USE
    public function terms()
    {
//        $page = Page::find(2);
//        $this->assign('info', $page);

        return $this->fetch();
    }

    //policy
    public function policy()
    {
//        $page = Page::find(3);
//        $this->assign('info', $page);

        return $this->fetch();
    }

    //policy
    public function policy_cn()
    {
//        $page = Page::find(3);
//        $this->assign('info', $page);

        return $this->fetch();
    }
    //联系我们
    public function contact()
    {
        return $this->fetch();
    }

    //帮助列表
    public function help()
    {
        $type_id = input('type_id', 0);
        if ($type_id) {
            if ($type_id == 2) {
                //老师
                $pid = 227;
                $title = 'Tutor';
            } else {
                //学生
                $pid = 230;
                $title = 'Learner';
            }
            $this->assign('title_type', $title);
            $this->assign('type_id', $type_id);
            $cats = Column::field('id,name')->where(['pid' => $pid, 'status' => 1])->order('sort asc,id asc')->select();
            foreach ($cats as $k => $c) {
                $cats[$k]['arts'] = Document::get_list_app([['cid', 'eq', $c['id']]], 'id,title,content,create_time,d.descr');
            }
            $this->assign('cats', $cats);
            return $this->fetch('help_m');
        }

        $cats = Column::field('id,name')->where(['pid' => 227, 'status' => 1])->order('sort asc,id asc')->select();
        foreach ($cats as $k => $c) {
            $cats[$k]['arts'] = Document::get_list_app([['cid', 'eq', $c['id']]], 'id,title,content,create_time,d.descr,d.thumb');
        }
        $this->assign('cats_teacher', $cats);
        $cats = Column::field('id,name')->where(['pid' => 230, 'status' => 1])->order('sort asc,id asc')->select();
        foreach ($cats as $k => $c) {
            $cats[$k]['arts'] = Document::get_list_app([['cid', 'eq', $c['id']]], 'id,title,content,create_time,d.descr,d.thumb');
        }
        $this->assign('cats_student', $cats);

        $isMobile = $this->isMobile();
        $this->assign('isMobile', $isMobile);

        return $this->fetch();
    }

    //机构
    public function partner()
    {
        $type_id = input('type_id', 1);
        $this->assign('type_id', $type_id);
        return $this->fetch();
    }

}
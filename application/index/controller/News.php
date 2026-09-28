<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/23
 * Time: 11:28
 */

namespace app\index\controller;

use app\cms\model\Column;
use app\cms\model\Document;
use think\Db;

class News extends Home
{

    public function lists()
    {
        $id = input('id', 0, 'intval');
        $cat = Column::find($id);
        if (!$cat) {
            $this->redirect('/');
        }
        if ($cat['pid'] == 0 && $id != 194) {
            $re = Column::where(['pid' => $id, 'status' => 1])->order('id')->find();
            if ($re) {
                $cat = $re;
            }
        }
        if ($cat['pid']) {
            $pid = $cat['pid'];
        } else {
            $pid = $id;
        }
        $pagesize = 20;
        if ($cat['id'] == 199) {
            $pagesize = 1000;
        }
        if ($cat['list_template']) {
            $template = $cat['list_template'];
        } elseif ($cat['type_id'] == 0) {
            $template = 'list_txt';
        } elseif ($cat['type_id'] == 1) {
            $template = 'list_img';
        } else {
            $template = 'about';
        }
        $child = Column::where(['pid' => $pid, 'status' => 1])->order('sort,id')->select();
        if ($cat['id'] == '223') {
            $zke = config('custom.zke');
            $his = config('custom.his');
            $this->assign('zke', $zke);
            $this->assign('his', $his);
        } elseif ($cat['id'] == 194) {
            //科室
            foreach ($child as $k=>$row) {
                $map = ['cid' => $row['id'], 'cms_document.status'=>1, 'trash'=>0];
                $child[$k]['list'] = Document::getList2($map, '', 1000);
            }
        } else if ($cat['type_id'] < 2) {
            $list = Document::alias('d')->field('d.*')
                ->join('cms_column c', "d.cid = c.id")
                ->where('cid|pid', $id)
                ->where(['d.status' => 1, 'trash' => 0])->order('sort desc,id desc')->paginate($pagesize);
            $this->assign('lists', $list);
        }
        $this->assign('childs', $child);
        $this->assign('cat_current', $cat);

        return $this->fetch($template);
    }
    public function search()
    {
        $word = input('word');
        $list = Document::where(['status' => 1, 'trash' => 0])->where('title','like', "%$word%")->paginate(15);
        $child = Column::where(['pid' => 0, 'status' => 1])->order('sort desc')->select();
        $this->assign('childs', $child);
        $this->assign('word', $word);

        $this->assign('lists', $list);

        return $this->fetch();
    }

    public function detail()
    {
        $id = input('id', 0, 'intval');
        $info = Document::find($id)->toArray();
        $info_append = Db::name('cms_document_article')->where('aid', $id)->find();
        $info = array_merge($info, $info_append);
        if (!$info) {
            $this->redirect('/');
        }
        if ($info['status'] != 1 || $info['trash'] != 0)
        {
            $this->redirect('/');
        }
        Document::where('id', $id)->setInc('view', 1);
        $this->assign('article', $info);
        $cat = Column::find($info['cid']);
        $cat_current = $cat;
        $this->assign('cat_current', $cat);
        if ($cat['pid']) {
            $pid = $cat['pid'];
        } else {
            $pid = $cat['id'];
        }
        $cat = Column::find($cat['pid']);
        if ($cat['pid']) {
            $pid = $cat['pid'];
        } else {
            $pid = $cat['id'];
        }
        $this->assign('cat_parent', $cat);
        $map = ['cid' => $id, 'status' => 1, 'trash' => 0];
        $prov = Document::where($map)->where('id','gt', $id)->order('id')->find();
        $next = Document::where($map)->where('id','lt', $id)->order('id desc')->find();
        $this->assign('aprev', $prov);
        $this->assign('anext', $next);

        $child = Column::where(['pid' => $pid, 'status' => 1])->order('sort,id')->select();
        $this->assign('childs', $child);

        $template = 'detail';
        if ($cat_current['detail_template']) {
            $template = $cat_current['detail_template'];
        }
        return $this->fetch($template);
    }

}
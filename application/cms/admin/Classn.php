<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/3/21
 * Time: 22:28
 */

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\cms\model\Classsn;
use app\common\builder\ZBuilder;
use think\Db;

class Classn extends Admin
{

    public function index()
    {
        $data_list = Db::name('cms_classsn')->paginate(30);

        $btnAdd = [
            'class' => 'btn btn-xs btn-default ajax-get confirm',
            'icon'  => 'fa fa-fw fa-eraser',
            'title' => '清空会员',
            'href'  => url('clear', ['id' => '__id__'])
        ];

        return ZBuilder::make('table')
            ->setSearch(['class_sn' => '编号']) // 设置搜索框
            ->hideCheckbox(true)
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['class_sn', '编号'],
                ['user_id', '教师id'],
                ['reg_time', '注册时间'],
                ['right_button', '操作', 'btn']
            ])
//            ->addRightButton('custom', $btnAdd)
            ->addTopButtons('add') // 批量添加顶部按钮
            ->addRightButton('delete', ['data-tips' => '删除编号前，请确保没有注册！']) // 添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'Classsn');
            if(true !== $result) $this->error($result);

            if ($column = Classsn::create_sn($data)) {
                // 记录行为
                action_log('classsn_add', 'cms_classsn', 0, UID, $data['sn_min'].'-'.$data['sn_max']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        return ZBuilder::make('form')
            ->addFormItems([
                ['text', 'sn_min', '最小号码'],
                ['text', 'sn_max', '最大号码'],
            ])
            ->fetch();
    }

    public function clear($id = 0)
    {
        $info = Classsn::find($id);
        Classsn::where('id', $id)->update(['user_id'=>0, 'reg_time' => null]);
        action_log('classsn_clear', 'cms_classsn', $id, UID, $info['class_sn']);
        $this->success('清空成功', 'index');
    }

    public function delete($ids = 0)
    {
        $info = Classsn::find($ids);
        if (!$info['user_id']) {
            Classsn::where('id', $ids)->delete();
            action_log('classsn_delete', 'cms_classsn', $ids, UID, $info['class_sn']);
            $this->success('删除成功', 'index');
        } else {
            $this->success('删除失败', 'index');
        }
    }

    public function edit($id = 0)
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'Classsn');
            if(true !== $result) $this->error($result);

            if ($column = Classsn::update($data)) {
                // 记录行为
                action_log('classsn_edit', 'cms_classsn', $column['id'], UID, $data['sn_min'].'-'.$data['sn_max']);
                $this->success('修改成功', 'index');
            } else {
                $this->error('修改失败');
            }
        }
        $info = Classsn::find($id);
        if (!$id) {
            $this->redirect('index');
        }
        return ZBuilder::make('form')
            ->addFormItems([
                ['text', 'sn_min', '最小号码'],
                ['text', 'sn_max', '最大号码'],
            ])
            ->setFormData($info)
            ->fetch();
    }

}
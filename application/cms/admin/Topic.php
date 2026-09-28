<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/24
 * Time: 11:49
 */

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\index\model\Topic as DoModel;

class Topic extends Admin
{

    public function index()
    {
        $map = $this->getMap();
        $list = DoModel::where($map)->order('sort desc, id desc')->paginate();

        $pay_types = ['禁用', '正常'];

        return ZBuilder::make('table')
            ->hideCheckbox(true)
            ->setColumnWidth([
                'id'=>50,
                'topic_name'=>100,
                'status'=>100,
                'create_time'=>180,
            ])
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['topic_name', '主题'],
                ['sort', '排序'],
                ['descr', '内容'],
                ['status', '状态', 'status', '', $pay_types],
                ['create_time', '添加时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->setRowList($list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'Topic');
            if (true !== $result) $this->error($result);
            $data['create_time'] = time();
            if ($advert = DoModel::create($data)) {
                // 记录行为
                action_log('topic_add', 'cms_topic', $advert['id'], UID, $data['topic_name']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        // 显示添加页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['text', 'topic_name', '主题'],
                ['text', 'sort', '排序', '越大越显示前'],
                ['textarea', 'descr', '内容'],
                ['radio', 'status', '状态', '', ['禁用', '正常'], 1]
            ])->fetch();
    }

    public function edit($id = 0)
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'Topic');
            if (true !== $result) $this->error($result);
            if ($advert = DoModel::where('id', $data['id'])->update($data)) {
                // 记录行为
                action_log('topic_edit', 'cms_topic', $data['id'], UID, $data['topic_name']);
                $this->success('修改成功', 'index');
            } else {
                $this->error('修改失败');
            }
        }
        $info = DoModel::find($id);
        if (!$info) {
            $this->redirect('topic/index');
        }
        // 显示添加页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'topic_name', '主题'],
                ['text', 'sort', '排序', '越大越显示前'],
                ['textarea', 'descr', '内容'],
                ['radio', 'status', '状态', '', ['禁用', '正常'], 1]
            ]) ->setFormData($info)->fetch();
    }

}
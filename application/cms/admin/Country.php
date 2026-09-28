<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/23
 * Time: 9:28
 */

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\admin\model\Attachment;
use app\common\builder\ZBuilder;
use app\index\model\Country as LocModel;

class Country extends Admin
{

    public function index()
    {
        $map = $this->getMap();
        $list = LocModel::where('is_delete',0)->select();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->hideCheckbox(true)
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['country_name', '名称'],
                ['title', '简写'],
                ['quhao', '区号'],
                ['ico', '标志图片', 'picture'],
                ['status', '状态', 'switch'],
                ['is_teacher', '教师国家', 'switch'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->setRowList($list) // 设置表格数据
            ->addValidate('Advert', 'name')
            ->fetch(); // 渲染模板
    }

    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'Country');
            if (true !== $result) $this->error($result);
            $ico = get_file_path($data['ico']);
            $data['ico_file'] = $ico;
            if ($advert = LocModel::create($data)) {
                // 记录行为
                action_log('country_add', 'cms_country', $advert['id'], UID, $data['country_name']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        // 显示添加页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['text', 'country_name', '名称'],
                ['text', 'quhao', '区号'],
                ['text', 'title', '简写'],
                ['image', 'ico', '标志图片'],
                ['radio', 'status', '状态', '', ['禁用', '正常'], 1],
                ['radio', 'is_teacher', '教师国家', '', ['不是', '是'],0],
            ])->fetch();
    }

    public function edit($id=0)
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'Country');
            if (true !== $result) $this->error($result);
            $ico = get_file_path($data['ico']);
            $data['ico_file'] = $ico;
            if ($advert = LocModel::where('id',$id)->update($data)) {
                // 记录行为
                action_log('country_edit', 'cms_country', $id, UID, $data['country_name']);
                $this->success('修改成功', 'index');
            } else {
                $this->error('修改失败');
            }
        }
        $info = LocModel::find($id);
        if (!$info) {
            $this->redirect('index');
        }
        // 显示添加页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['text', 'country_name', '名称'],
                ['text', 'quhao', '区号'],
                ['text', 'title', '简写'],
                ['image', 'ico', '标志图片'],
                ['radio', 'status', '状态', '', ['禁用', '正常']],
                ['radio', 'is_teacher', '教师国家', '', ['不是', '是']],
            ])->setFormData($info)->fetch();
    }

}
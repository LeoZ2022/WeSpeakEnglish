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
use app\index\model\Location as LocModel;

class Location extends Admin
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
                ['location_name', '名称'],
                ['timezone', '相差小时数'],
                ['code', '时区'],
                ['status', '状态', 'switch'],
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
            date_default_timezone_set($data['code']);
            $data['timezone'] = date('Z', time()) / 3600;;

            // 验证
            $result = $this->validate($data, 'Location');
            if (true !== $result) $this->error($result);
            if ($advert = LocModel::create($data)) {
                // 记录行为
                action_log('location_add', 'cms_location', $advert['id'], UID, $data['location_name']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        // 显示添加页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['text', 'location_name', '名称'],
//                ['text', 'title', '简写'],
                ['text', 'timezone', '相差小时数', '可不填，系统自动计算'],
                ['text', 'code', '时区', 'PHP设置时区对应的英文名称，可查看：https://www.php.net/manual/zh/timezones.php'],
                ['radio', 'status', '状态', '', ['禁用', '正常'], 1]
            ])->fetch();
    }

    public function edit($id=0)
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'Location');
            if (true !== $result) $this->error($result);
            if ($advert = LocModel::where('id',$id)->update($data)) {
                // 记录行为
                action_log('location_edit', 'cms_location', $id, UID, $data['location_name']);
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
                ['text', 'location_name', '名称'],
//                ['text', 'title', '简写'],
                ['text', 'timezone', '相差小时数'],
                ['text', 'code', '时区', 'PHP设置时区对应的英文名称，可查看：https://www.php.net/manual/zh/timezones.php'],
                ['radio', 'status', '状态', '', ['禁用', '正常']]
            ])->setFormData($info)->fetch();
    }

}
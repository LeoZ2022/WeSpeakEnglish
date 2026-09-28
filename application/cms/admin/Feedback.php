<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/25
 * Time: 21:57
 */

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\index\model\Feedback as FeedModel;

class Feedback extends Admin
{

    public function index()
    {
        $map = $this->getMap();
        $data_list = FeedModel::where($map)->order('id desc')->paginate(20);

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
//            ->setSearch(['name' => 'Name', 'email' => 'Email']) // 设置搜索框
            ->setColumnWidth([
                'id' => 30,
                'name'  => 100,
                'email'  => 200,
                'body'  => 400,
                'create_time'    => 150,
            ])
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['name', 'Name'],
                ['email', 'Email'],
                ['body', 'Message'],
                ['create_time', '创建时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->addValidate('Advert', 'name')
            ->fetch(); // 渲染模板
    }

}
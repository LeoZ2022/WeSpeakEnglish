<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/10/13 0013
 * Time: 11:35
 */

namespace app\admin\controller;

use app\common\builder\ZBuilder;
use think\Db;

class Donwload extends Admin
{

    public function index()
    {
        // 查询
        $map = $this->getMap();
        $data_list = Db::name('cms_download')->where($map)->order('id desc')->paginate(30);
        // 分页数据
        $page = $data_list->render();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('下载记录') // 设置页面标题
            ->hideCheckbox()
            ->addColumns([ // 批量添加数据列
                ['id', '编号'],
                ['addtime', '下载日期', 'datetime', '', 'Y-m-d H:i:s'],
            ])
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染模板
    }

}

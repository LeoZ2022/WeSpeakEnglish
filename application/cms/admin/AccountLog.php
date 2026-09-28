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
use app\index\model\Account;
use app\index\model\Donation as DoModel;
use think\Db;

class AccountLog extends Admin
{

    public function index()
    {
        $map = $this->getMap();
        $user_id = input('user_id', 0);
        $is_partner = input('is_partner', 1);
        $paras = [];
        if ($user_id) {
            $paras['user_id'] = $user_id;
            $map[] = ['o.user_id', 'eq', $user_id];
        }
        if ($is_partner) {
            $map[] = ['is_partner', 'eq', $is_partner -1];
        }
        $list = Account::alias('o')->field('o.*, username')->join('cms_users u', 'user_id = u.id')->where($map)->order('o.id desc')->paginate();

        $account_types = Db::name('cms_account_type')->cache(true)->column('id,type_name');
        $this->assign('account_types', $account_types);
        $this->assign('data_list', $list);
        $tab_list   = [];
        $paras['is_partner'] = 1;
        $tab_list[1] = ['title' => '普通会员', 'url' => url('index', $paras)];
        $paras['is_partner'] = 2;
        $tab_list[2] = ['title' => '机构会员', 'url' => url('index', $paras)];

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->addTimeFilter('o.create_time')
            ->hideCheckbox(true)
            ->setTabNav($tab_list, $is_partner) // 设置tab分页
            ->setSearch(['username' => '用户']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['username', 'User'],
                ['type_id', 'Type', 'status', '', $account_types],
                ['money', 'Amount'],
                ['create_time', 'Create time', 'datetime'],
            ])
            ->setRowList($list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

}
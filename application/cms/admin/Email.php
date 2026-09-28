<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/5
 * Time: 21:43
 */

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use think\Db;

class Email extends Admin
{

    public function index()
    {
        if (input('submit')) {
            $map = ['status'=>1];
            $map2 = [];
            $user_type = input('user_type');
            $days = input('days');
            $is_active = input('is_active');
            $is_paid = input('is_paid');
            $is_success = input('is_success');
            $country = input('country');
            if ($user_type) {
                $map['user_type'] = $user_type;
            }
            if ($days) {
                $time = strtotime('-'. $days.' day');
                $map2[] = ['create_time', 'egt', $time];
            }
            if ($is_active) {
                $map['has_active'] = $is_active-1;
            }
            if ($is_paid) {
                $map2[] = ['paid_num', 'gt', 1];
            }
            if ($is_success == 1) {
                //买了还没上的
                $map2[] = ['class_num', 'eq', 0];
            } elseif ($is_success == 2) {
                //上过的
                $map2[] = ['class_num', 'gt', 0];
            }
            if ($country) {
                $country_ids = \app\index\model\Country::where([ ['country_name', 'like', "%$country%"], ['status', 'eq', 1] ])->column('id');
                $map2[] = ['country_id', 'in', $country_ids];
            }
            $list = \app\index\model\Users::where($map)->where($map2)->column('id, email');
            session('email_list', $list);
//            echo \app\index\model\Users::getlastsql();print_r($list);
            $this->assign('email_list', $list);
        }
        if (input('export')) {
            //导出
            /*
             */
            Header( "Content-type:   application/octet-stream ");
            Header( "Accept-Ranges:   bytes ");
            header( "Content-Disposition:   attachment;   filename=emails.txt ");
            header( "Expires:   0 ");
            header( "Cache-Control:   must-revalidate,   post-check=0,   pre-check=0 ");
            header( "Pragma:   public ");

            $list = session('email_list');
            $no = 1;
            foreach ($list as $email) {
                if ($no%10==1 && $no>1) {
                    echo "\n";
                }
                echo $email.';';
                $no++;
            }
            exit;
        }
        return $this->fetch();
    }

    //邮件发送统计
    public function counts()
    {
        $data_list = Db::name('cms_email')->order('id desc')->paginate();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->hideCheckbox(true)
            ->setColumnWidth([
                'id' => 50,
                'date'  => 100,
                'cnt'    => 150,
            ])
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['date', '日期'],
                ['cnt', '发送数量'],
            ])
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

}
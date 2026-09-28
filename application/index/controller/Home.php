<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------

namespace app\index\controller;

use app\cms\model\Column;
use app\common\controller\Common;
use app\cms\model\Document;
use app\index\logic\Order;
use app\index\model\Notice;
use ipip\db\City;

/**
 * 前台公共控制器
 * @package app\index\controller
 */
class Home extends Common
{
    /**
     * 初始化方法*/
    protected function initialize()
    {
        // 系统开关
        if (!config('web_site_status')) {
            $this->error('站点已经关闭，请稍后访问~');
        }

        $this->assign('show_lang', 0);

        $user_id = session('?user_id') ? session('user_id') : 0;
        $this->user_id = $user_id;
        if ($user_id >0 && !$this->request->isAjax()) {
            $cnt = Notice::where(['user_id' => $user_id, 'is_read' => 0])->count();
            $this->assign('has_wait_read', $cnt);
            $this->assign('user_type', session('user_type'));
            $this->assign('is_partner', session('is_partner'));
        } else {
            $this->assign('is_partner', 0);
            $this->assign('user_type', 0);
        }
        $this->assign('user_id', $user_id);

        $this->is_wap = check_wap() ? 1 : 0;
        $this->assign('is_wap', $this->is_wap);
        $webcom = session('webcom') ?? 'chat';
        if (!session('location_ip') && !$this->request->isAjax()) {
            $city = new City('./ipipfree.ipdb');
            $ip = get_client_ip();
//            $ip = '67.220.90.13';
            $areas = $city->find($ip, 'CN');

            if ($areas[0] == '中国' || $areas[0] == '本机地址') {
                $webcom = 'chat';
                $country = 'china';
            } else {
                $webcom = 'chat';
                $country = 'out';
            }
            session('location_ip', $ip);
            session('country', $country);
            session('webcom', $webcom);
        }
        $this->assign('country', session('country'));
        $this->assign('webcom', $webcom);


//        Order::crontab();
    }

    public function chk_login($user_id)
    {
        $user_type = cookie('user_type');
        if (!$user_id) {
            if ($user_type == 2) {
                $this->redirect('sign/tutor');
            } else {
                $this->redirect('sign/learner');
            }
        }
    }

    /**
     * 检测是否使用手机访问
     * @access public
     * @return bool
     */
    public function isMobile()
    {
        if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap")) {
            return true;
        } elseif (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER['HTTP_ACCEPT']), "VND.WAP.WML")) {
            return true;
        } elseif (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
            return true;
        } elseif (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        } else {
            return false;
        }
    }

}

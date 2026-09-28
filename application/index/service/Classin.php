<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/10
 * Time: 15:43
 */

namespace app\index\service;

use app\index\model\Orderitems;

class Classin
{

    private $api_url = 'https://api.eeo.cn/';
    //注册账号
    private $register_url = 'partner/api/course.api.php?action=register';
    //添加老师
    private $addTeacher_url = 'partner/api/course.api.php?action=addTeacher';
    //添加学生
    private $addSchoolStudent_url = 'partner/api/course.api.php?action=addSchoolStudent';
    //添加课程
    private $addCourse_url = 'partner/api/course.api.php?action=addCourse';
    //课程下添加学生
    private $addCourseStudent_url = 'partner/api/course.api.php?action=addCourseStudent';
    //添加课节
    private $addClass_url = 'partner/api/course.api.php?action=addCourseClass';
    //修改课节
    private $editClass_url = 'partner/api/course.api.php?action=editCourseClass';
    //删除课节
    private $delClass_url = 'partner/api/course.api.php?action=delCourseClass';
    //进入教室
    private $getLoginLinked = 'partner/api/course.api.php?action=getLoginLinked';

    //注册教师或学生
    public function register($data)
    {
        $rdata = $data;
        $data = $this->createKey($data);

        $url = $this->api_url.$this->register_url;
        $result = $this->curl($url, $data);
        $this->logs('register', $data, $result);
        $addToSchoolMember = $data['addToSchoolMember'];
        $data = json_decode($result, true);
        if (isset($data['error_info']) && ($data['error_info']['errno'] == 1 || $data['error_info']['errno'] == 135)) {
            if ($data['error_info']['errno'] == 135) {
                //原来注册的，需要添加到机构下
                $res = $this->addUser($addToSchoolMember, $rdata['telephone'], $rdata['nickname']);
                if ($res['code'] != 200) {
                    return ['code'=>201, 'msg'=> $res['msg']];
                }
            }
            return ['code'=>200, 'user_id' => $data['data']];
        } else {
            return ['code'=>201, 'msg'=>$data['error_info']['error']];
        }
    }

    /**
     * 添加之前注册的到机构
     * @param $TorS，1学生，2老师
     * @param $account，手机号
     * @param $name，名称
     */
    public function addUser($TorS, $account, $name)
    {
        if ($TorS == 1) {
            //学生
            $data = ['studentAccount' => $account, 'studentName' => $name];
            $url = $this->addSchoolStudent_url;
        } else {
            //老师
            $data = ['teacherAccount' => $account, 'teacherName' => $name];
            $url = $this->addTeacher_url;
        }
        $data = $this->createKey($data);
        $url = $this->api_url.$url;
        $result = $this->curl($url, $data);
        $this->logs($url, $data, $result);
        $data = json_decode($result, true);
        if (isset($data['error_info']) && ($data['error_info']['errno'] == 1 || $data['error_info']['errno'] == 133)) {
            return ['code'=>200, 'msg' => 'Success'];
        } else {
            return ['code'=>201, 'msg'=>$data['error_info']['error']];
        }
    }

    //创建课程
    public function addCourse($courseName, $classid_uid, $teacher)
    {
        $data = ['courseName' => $courseName. '——'. $teacher['username']];
        $data = $this->createKey($data);

        $url = $this->api_url.$this->addCourse_url;
        $result = $this->curl($url, $data);
        $this->logs('addCourse', $data, $result);
        $data = json_decode($result, true);
        if (isset($data['error_info']) && $data['error_info']['errno'] == 1) {
            //课程里面添加学生
            $this->addCourseStudent($data['data'], $classid_uid);
            return ['code'=>200, 'id' => $data['data']];
        } else {
            return ['code'=>201, 'msg'=>$data['error_info']['error']];
        }
    }

    //课程下添加学生
    public function addCourseStudent($courseId, $studentUid)
    {
        $data = ['courseId' => $courseId, 'identity' => 1, 'studentUid' => $studentUid];
        $data = $this->createKey($data);
        $url = $this->api_url.$this->addCourseStudent_url;
        $result = $this->curl($url, $data);
        $this->logs('addCourseStudent', $data, $result);
        $data = json_decode($result, true);
        if (isset($data['error_info']) && $data['error_info']['errno'] == 1) {
            return ['code'=>200, 'msg' => 'success'];
        } else {
            return ['code'=>201, 'msg'=>$data['error_info']['error']];
        }
    }

    //创建课节
    public function createClass($data)
    {
        $data['seatNum'] = 1;
        $data = $this->createKey($data);
        $url = $this->api_url.$this->addClass_url;
        $result = $this->curl($url, $data);
        $this->logs('createClass', $data, $result);
        $data = json_decode($result, true);
        if (isset($data['error_info']) && $data['error_info']['errno'] == 1) {
            return ['code'=>200, 'data' => $data];
        } else {
            return ['code'=>201, 'msg'=>$data['error_info']['error']];
        }
    }

    //修改课节
    public function editCourseClass($courseId, $classId, $beginTime = '', $endTime = '', $teacherUid = 0)
    {
        return ['code'=>200, 'msg' => 'success'];
        $data = [];
        if ($beginTime && $endTime) {
            $data['beginTime'] = $beginTime;
            $data['endTime'] = $endTime;
        }
        if ($teacherUid) {
            $data['teacherUid'] = $teacherUid;
        }
        if ($data) {
            $data['courseId'] = $courseId;
            $data['classId'] = $classId;
            $data = $this->createKey($data);
            $url = $this->api_url.$this->editClass_url;
            $result = $this->curl($url, $data);
            $this->logs('editCourseClass', $data, $result);
            $data = json_decode($result, true);
            if (isset($data['error_info']) && $data['error_info']['errno'] == 1) {
                return ['code'=>200, 'msg' => 'success'];
            } else {
                return ['code'=>201, 'msg'=>$data['error_info']['error']];
            }
        } else {
            return ['code'=>201, 'msg'=>'Missing data'];
        }
    }

    //取消课程，删除课节
    public function delCourseClass($courseId, $classId)
    {
        return true;
        $data = ['courseId' => $courseId, 'classId' => $classId];
        $data = $this->createKey($data);
        $url = $this->api_url.$this->delClass_url;
        $result = $this->curl($url, $data);
        $this->logs('delCourseClass', $data, $result);
        $data = json_decode($result, true);
        if (isset($data['error_info']) && $data['error_info']['errno'] == 1) {
            return ['code'=>200, 'msg' => 'success'];
        } else {
            return ['code'=>201, 'msg'=>$data['error_info']['error']];
        }
    }

    /**
     * 进入课程
     * @param $uid：用户id
     * @param $courseId：课程id
     * @param $classId：课节id
     */
    public function getLoginLinked($uid, $courseId, $classId)
    {
        $data = ['uid' => $uid, 'courseId' => $courseId, 'classId' => $classId];
        $data['deviceType'] = get_device_type();
        $data = $this->createKey($data);
        $url = $this->api_url.$this->getLoginLinked;
        $result = $this->curl($url, $data);
        $this->logs('getLoginLinked', $data, $result);
        $data = json_decode($result, true);
        if (isset($data['error_info']) && $data['error_info']['errno'] == 1) {
            return ['code'=>200, 'msg' => 'success', 'data' => $data['data']];
        } else {
            return ['code'=>201, 'msg'=>$data['error_info']['error']];
        }
    }

    //生成safeKey
    public function createKey($data)
    {
        $cfg = config('custom.classin');
//        $data['timeStamp'] = get_time_gmt();
        $data['timeStamp'] = time();
        $data['SID'] = $cfg['classin_sid'];
        $data['safeKey'] = md5($cfg['classin_secret']. $data['timeStamp']);
        return $data;
    }

    public function logs($act, $data, $result)
    {
//        var_dump($result);
        $fp = fopen('public/classin/log_'.date('Ymd').'.txt', 'a+');
        fwrite($fp, date('Y-m-d H:i:s').' '.$act."\r\n");
        if (is_array($data)) {
            fwrite($fp, json_encode($data) . "\r\n");
        } else {
            fwrite($fp, $data . "\r\n");
        }
        fwrite($fp, $result."\r\n");
        fclose($fp);
    }

    //回调
    public function callback()
    {
        $res = @file_get_contents('php://input');
        $this->logs('callback', $res, '');
        $data = json_decode($res, true);
        if (isset($data['Cmd'])) {
            if ($data['Cmd'] == 'End') {
                //课节结束
                $ret = Orderitems::success_notify($data);
            } elseif ($data['Cmd'] == 'Check') {
                //教室内设备检测报告
                $ret = Orderitems::device_notify($data);
            } elseif ($data['Cmd'] == 'Rating') {
                //评价
                $ret = Orderitems::comment_notify($data);
            }
            $ret = ['error_info' => ['errno' => 1, 'error' => '程序正常执行']];
        } else {
            $this->logs('callback', '数据错误', '');
            $ret = ['error_info' => ['errno' => 1, 'error' => '程序正常执行']];
        }
        return $ret;
    }

    //curl POST请求
    public function curl($url, $post_data = '')
    {
        //发送post请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if ($post_data) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        return $result;
    }
}
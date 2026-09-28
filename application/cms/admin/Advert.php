<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\admin\model\Attachment;
use app\common\builder\ZBuilder;
use app\cms\model\Advert as AdvertModel;
use app\cms\model\AdvertType as AdvertTypeModel;
use think\facade\Validate;

/**
 * 广告控制器
 * @package app\cms\admin
 */
class Advert extends Admin
{
    /**
     * 广告列表* @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function index()
    {
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder('update_time desc');
        // 数据列表
        $data_list = AdvertModel::where($map)->order($order)->paginate();

        $btnType = [
            'class' => 'btn btn-info',
            'title' => '广告分类',
            'icon'  => 'fa fa-fw fa-sitemap',
            'href'  => url('advert_type/index')
        ];

        $list_type = AdvertTypeModel::where('status', 1)->column('id,name');
        array_unshift($list_type, '默认分类');

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['title' => '标题']) // 设置搜索框
                ->setColumnWidth([
                    'id' => 50,
                    'name'  => 300,
                'create_time'    => 150,
                'typeid'    => 150,
                'status'    => 80
            ])
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['name', '广告名称'],
                ['typeid', '分类', 'select', $list_type],
                ['create_time', '创建时间', 'datetime'],
                ['status', '状态', 'switch'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
//            ->addTopButton('custom', $btnType) // 添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,name,typeid,timeset,ad_type,create_time,update_time')
            ->setRowList($data_list) // 设置表格数据
            ->addValidate('Advert', 'name')
            ->fetch(); // 渲染模板
    }

    /**
     * 新增* @return mixed
     * @throws \think\Exception
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'Advert');
            if (true !== $result) $this->error($result);
            if ($data['ad_type'] != 0) {
//                $data['link'] == '' && $this->error('链接不能为空');
//                Validate::is($data['link'], 'url') === false && $this->error('链接不是有效的url地址'); // true
            }

            $thumb = Attachment::find($data['src']);
            $data['thumb'] = $thumb['path'];

            if ($advert = AdvertModel::create($data)) {
                // 记录行为
                action_log('advert_add', 'cms_advert', $advert['id'], UID, $data['name']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }

        $list_type = AdvertTypeModel::where('status', 1)->column('id,name');
        array_unshift($list_type, '默认分类');

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'ad_type', 2],
                ['hidden', 'timeset', 0],
                ['select', 'typeid', '广告分类', '', $list_type, 0],
                ['text', 'tagname', '广告位标识', '由小写字母、数字或下划线组成，不能以数字开头'],
                ['text', 'name', '广告位名称'],
                ['text', 'sort', '排序'],
                ['image', 'src', 'PC图片'],
                ['image', 'src_wap', '手机图片'],
                ['text', 'link', '链接'],
                ['textarea', 'content', 'PC内容'],
                ['textarea', 'content2', '手机内容'],
                ['radio', 'status', '立即启用', '', ['否', '是'], 1]
            ])
            ->setTrigger('ad_type', '0', 'code')
            ->setTrigger('ad_type', '1', 'title,color,size')
            ->setTrigger('ad_type', '2', 'src,alt')
            ->setTrigger('ad_type', '2,3', 'width,height')
            ->setTrigger('ad_type', '1,2,3', 'link')
            ->setTrigger('timeset', '1', 'start_time')
            ->fetch();
    }

    /**
     * 编辑
     * @param null $id 广告id* @return mixed
     * @throws \think\Exception
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'Advert');
            if (true !== $result) $this->error($result);

            $thumb = Attachment::find($data['src']);
            $data['thumb'] = $thumb['path'];
            if (AdvertModel::update($data)) {
                // 记录行为
                action_log('advert_edit', 'cms_advert', $id, UID, $data['name']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }

        $list_type = AdvertTypeModel::where('status', 1)->column('id,name');
        array_unshift($list_type, '默认分类');

        $info = AdvertModel::get($id);
        $info['ad_type'] = ['代码', '文字', '图片', 'flash'][$info['ad_type']];

        // 显示编辑页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'id'],
                ['hidden', 'tagname'],
                ['static', 'tagname', '广告位标识'],
                ['static', 'ad_type', '广告类型'],
                ['text', 'name', '广告位名称'],
                ['select', 'typeid', '广告分类', '', $list_type],
                ['text', 'sort', '排序'],
                ['image', 'src', 'PC图片'],
                ['image', 'src_wap', '手机图片'],
                ['text', 'link', '链接'],
                ['textarea', 'content', 'PC内容'],
                ['textarea', 'content2', '手机内容'],
                ['radio', 'status', '立即启用', '', ['否', '是']]
            ])
            ->setTrigger('timeset', '1', 'start_time')
            ->setFormData($info)
            ->fetch();
    }

    /**
     * 删除广告
     * @param array $record 行为日志* @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete($record = [])
    {
        return $this->setStatus('delete');
    }

    /**
     * 启用广告
     * @param array $record 行为日志* @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    /**
     * 禁用广告
     * @param array $record 行为日志* @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }

    /**
     * 设置广告状态：删除、禁用、启用
     * @param string $type 类型：delete/enable/disable
     * @param array $record* @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function setStatus($type = '', $record = [])
    {
        $ids         = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $advert_name = AdvertModel::where('id', 'in', $ids)->column('name');
        return parent::setStatus($type, ['advert_'.$type, 'cms_advert', 0, UID, implode('、', $advert_name)]);
    }

    /**
     * 快速编辑
     * @param array $record 行为日志* @return mixed
     */
    public function quickEdit($record = [])
    {
        $id      = input('post.pk', '');
        $field   = input('post.name', '');
        $value   = input('post.value', '');
        $advert  = AdvertModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $advert . ')，新值：(' . $value . ')';
        return parent::quickEdit(['advert_edit', 'cms_advert', $id, UID, $details]);
    }
}

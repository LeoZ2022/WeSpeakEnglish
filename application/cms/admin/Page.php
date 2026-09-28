<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\cms\model\Page as PageModel;

/**
 * 单页控制器
 * @package app\cms\admin
 */
class Page extends Admin
{
    /**
     * 单页列表* @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function index()
    {
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder();
        // 数据列表
        $data_list = PageModel::where($map)->order($order)->paginate();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['title' => '标题']) // 设置搜索框
                ->hidecheckbox(true)
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['title', '标题'],
                ['create_time', '创建时间', 'datetime'],
                ['update_time', '更新时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])
//            ->addTopButtons('add') // 批量添加顶部按钮
//            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addRightButtons(['edit']) // 批量添加右侧按钮
            ->addOrder('id,title,create_time,update_time')
            ->setRowList($data_list) // 设置表格数据
            ->addValidate('Page', 'title')
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
            $result = $this->validate($data, 'Page');
            if(true !== $result) $this->error($result);

            if ($page = PageModel::create($data)) {
                // 记录行为
                action_log('page_add', 'cms_page', $page['id'], UID, $data['title']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }

        // 显示添加页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['text', 'title', '页面标题'],
//                ['tags', 'keywords', '页面关键词', '关键字之间用英文逗号隔开'],
                ['image', 'thumb', '标题图片'],
                ['image', 'thumb_wap', '手机标题图片'],
                ['image', 'cover', '顶部图片'],
                ['image', 'cover', '顶部图片'],
                ['image', 'cover_wap', '手机顶部图片'],
                ['textarea', 'description', '简介', ''],
                ['ckeditor', 'content', '页面内容'],
            ])
            ->fetch();
    }

    /**
     * 编辑
     * @param null $id 单页id* @return mixed
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
            $result = $this->validate($data, 'Page');
            if(true !== $result) $this->error($result);

            if (PageModel::update($data)) {
                // 记录行为
                action_log('page_edit', 'cms_page', $id, UID, $data['title']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }

        $info = PageModel::get($id);

        // 显示编辑页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'title', '页面标题'],
//                ['tags', 'keywords', '页面关键词', '关键字之间用英文逗号隔开'],
                ['image', 'thumb', '标题图片'],
                ['image', 'thumb_wap', '手机标题图片'],
                ['image', 'cover', '顶部图片'],
                ['image', 'cover_wap', '手机顶部图片'],
                ['textarea', 'description', '简介', ''],
                ['ckeditor', 'content', '页面内容'],
            ])
            ->setFormdata($info)
            ->fetch();
    }

    /**
     * 删除单页
     * @param array $record 行为日志* @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete($record = [])
    {
        return $this->setStatus('delete');
    }

    /**
     * 启用单页
     * @param array $record 行为日志* @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    /**
     * 禁用单页
     * @param array $record 行为日志* @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }

    /**
     * 设置单页状态：删除、禁用、启用
     * @param string $type 类型：delete/enable/disable
     * @param array $record* @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function setStatus($type = '', $record = [])
    {
        $ids        = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $page_title = PageModel::where('id', 'in', $ids)->column('title');
        return parent::setStatus($type, ['page_'.$type, 'cms_page', 0, UID, implode('、', $page_title)]);
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
        $page    = PageModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $page . ')，新值：(' . $value . ')';
        return parent::quickEdit(['page_edit', 'cms_page', $id, UID, $details]);
    }
}

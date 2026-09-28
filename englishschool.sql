-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-11-14 20:45:36
-- 服务器版本： 5.7.25-log
-- PHP 版本： 7.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `englishschool`
--

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_access`
--

CREATE TABLE `dp_admin_access` (
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '模型名称',
  `group` varchar(16) NOT NULL DEFAULT '' COMMENT '权限分组标识',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id',
  `nid` varchar(16) NOT NULL DEFAULT '' COMMENT '授权节点id',
  `tag` varchar(16) NOT NULL DEFAULT '' COMMENT '分组标签'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='统一授权表';

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_action`
--

CREATE TABLE `dp_admin_action` (
  `id` int(11) UNSIGNED NOT NULL,
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '所属模块名',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` varchar(80) NOT NULL DEFAULT '' COMMENT '行为标题',
  `remark` varchar(128) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `log` text NOT NULL COMMENT '日志规则',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统行为表';

--
-- 转存表中的数据 `dp_admin_action`
--

INSERT INTO `dp_admin_action` (`id`, `module`, `name`, `title`, `remark`, `rule`, `log`, `status`, `create_time`, `update_time`) VALUES
(1, 'user', 'user_add', '添加用户', '添加用户', '', '[user|get_nickname] 添加了用户：[record|get_nickname]', 1, 1480156399, 1480163853),
(2, 'user', 'user_edit', '编辑用户', '编辑用户', '', '[user|get_nickname] 编辑了用户：[details]', 1, 1480164578, 1480297748),
(3, 'user', 'user_delete', '删除用户', '删除用户', '', '[user|get_nickname] 删除了用户：[details]', 1, 1480168582, 1480168616),
(4, 'user', 'user_enable', '启用用户', '启用用户', '', '[user|get_nickname] 启用了用户：[details]', 1, 1480169185, 1480169185),
(5, 'user', 'user_disable', '禁用用户', '禁用用户', '', '[user|get_nickname] 禁用了用户：[details]', 1, 1480169214, 1480170581),
(6, 'user', 'user_access', '用户授权', '用户授权', '', '[user|get_nickname] 对用户：[record|get_nickname] 进行了授权操作。详情：[details]', 1, 1480221441, 1480221563),
(7, 'user', 'role_add', '添加角色', '添加角色', '', '[user|get_nickname] 添加了角色：[details]', 1, 1480251473, 1480251473),
(8, 'user', 'role_edit', '编辑角色', '编辑角色', '', '[user|get_nickname] 编辑了角色：[details]', 1, 1480252369, 1480252369),
(9, 'user', 'role_delete', '删除角色', '删除角色', '', '[user|get_nickname] 删除了角色：[details]', 1, 1480252580, 1480252580),
(10, 'user', 'role_enable', '启用角色', '启用角色', '', '[user|get_nickname] 启用了角色：[details]', 1, 1480252620, 1480252620),
(11, 'user', 'role_disable', '禁用角色', '禁用角色', '', '[user|get_nickname] 禁用了角色：[details]', 1, 1480252651, 1480252651),
(12, 'user', 'attachment_enable', '启用附件', '启用附件', '', '[user|get_nickname] 启用了附件：附件ID([details])', 1, 1480253226, 1480253332),
(13, 'user', 'attachment_disable', '禁用附件', '禁用附件', '', '[user|get_nickname] 禁用了附件：附件ID([details])', 1, 1480253267, 1480253340),
(14, 'user', 'attachment_delete', '删除附件', '删除附件', '', '[user|get_nickname] 删除了附件：附件ID([details])', 1, 1480253323, 1480253323),
(15, 'admin', 'config_add', '添加配置', '添加配置', '', '[user|get_nickname] 添加了配置，[details]', 1, 1480296196, 1480296196),
(16, 'admin', 'config_edit', '编辑配置', '编辑配置', '', '[user|get_nickname] 编辑了配置：[details]', 1, 1480296960, 1480296960),
(17, 'admin', 'config_enable', '启用配置', '启用配置', '', '[user|get_nickname] 启用了配置：[details]', 1, 1480298479, 1480298479),
(18, 'admin', 'config_disable', '禁用配置', '禁用配置', '', '[user|get_nickname] 禁用了配置：[details]', 1, 1480298506, 1480298506),
(19, 'admin', 'config_delete', '删除配置', '删除配置', '', '[user|get_nickname] 删除了配置：[details]', 1, 1480298532, 1480298532),
(20, 'admin', 'database_export', '备份数据库', '备份数据库', '', '[user|get_nickname] 备份了数据库：[details]', 1, 1480298946, 1480298946),
(21, 'admin', 'database_import', '还原数据库', '还原数据库', '', '[user|get_nickname] 还原了数据库：[details]', 1, 1480301990, 1480302022),
(22, 'admin', 'database_optimize', '优化数据表', '优化数据表', '', '[user|get_nickname] 优化了数据表：[details]', 1, 1480302616, 1480302616),
(23, 'admin', 'database_repair', '修复数据表', '修复数据表', '', '[user|get_nickname] 修复了数据表：[details]', 1, 1480302798, 1480302798),
(24, 'admin', 'database_backup_delete', '删除数据库备份', '删除数据库备份', '', '[user|get_nickname] 删除了数据库备份：[details]', 1, 1480302870, 1480302870),
(25, 'admin', 'hook_add', '添加钩子', '添加钩子', '', '[user|get_nickname] 添加了钩子：[details]', 1, 1480303198, 1480303198),
(26, 'admin', 'hook_edit', '编辑钩子', '编辑钩子', '', '[user|get_nickname] 编辑了钩子：[details]', 1, 1480303229, 1480303229),
(27, 'admin', 'hook_delete', '删除钩子', '删除钩子', '', '[user|get_nickname] 删除了钩子：[details]', 1, 1480303264, 1480303264),
(28, 'admin', 'hook_enable', '启用钩子', '启用钩子', '', '[user|get_nickname] 启用了钩子：[details]', 1, 1480303294, 1480303294),
(29, 'admin', 'hook_disable', '禁用钩子', '禁用钩子', '', '[user|get_nickname] 禁用了钩子：[details]', 1, 1480303409, 1480303409),
(30, 'admin', 'menu_add', '添加节点', '添加节点', '', '[user|get_nickname] 添加了节点：[details]', 1, 1480305468, 1480305468),
(31, 'admin', 'menu_edit', '编辑节点', '编辑节点', '', '[user|get_nickname] 编辑了节点：[details]', 1, 1480305513, 1480305513),
(32, 'admin', 'menu_delete', '删除节点', '删除节点', '', '[user|get_nickname] 删除了节点：[details]', 1, 1480305562, 1480305562),
(33, 'admin', 'menu_enable', '启用节点', '启用节点', '', '[user|get_nickname] 启用了节点：[details]', 1, 1480305630, 1480305630),
(34, 'admin', 'menu_disable', '禁用节点', '禁用节点', '', '[user|get_nickname] 禁用了节点：[details]', 1, 1480305659, 1480305659),
(35, 'admin', 'module_install', '安装模块', '安装模块', '', '[user|get_nickname] 安装了模块：[details]', 1, 1480307558, 1480307558),
(36, 'admin', 'module_uninstall', '卸载模块', '卸载模块', '', '[user|get_nickname] 卸载了模块：[details]', 1, 1480307588, 1480307588),
(37, 'admin', 'module_enable', '启用模块', '启用模块', '', '[user|get_nickname] 启用了模块：[details]', 1, 1480307618, 1480307618),
(38, 'admin', 'module_disable', '禁用模块', '禁用模块', '', '[user|get_nickname] 禁用了模块：[details]', 1, 1480307653, 1480307653),
(39, 'admin', 'module_export', '导出模块', '导出模块', '', '[user|get_nickname] 导出了模块：[details]', 1, 1480307682, 1480307682),
(40, 'admin', 'packet_install', '安装数据包', '安装数据包', '', '[user|get_nickname] 安装了数据包：[details]', 1, 1480308342, 1480308342),
(41, 'admin', 'packet_uninstall', '卸载数据包', '卸载数据包', '', '[user|get_nickname] 卸载了数据包：[details]', 1, 1480308372, 1480308372),
(42, 'admin', 'system_config_update', '更新系统设置', '更新系统设置', '', '[user|get_nickname] 更新了系统设置：[details]', 1, 1480309555, 1480309642),
(43, 'cms', 'slider_delete', '删除滚动图片', '删除滚动图片', '', '[user|get_nickname] 删除了滚动图片：[details]', 1, 1598021962, 1598021962),
(44, 'cms', 'slider_edit', '编辑滚动图片', '编辑滚动图片', '', '[user|get_nickname] 编辑了滚动图片：[details]', 1, 1598021962, 1598021962),
(45, 'cms', 'slider_add', '添加滚动图片', '添加滚动图片', '', '[user|get_nickname] 添加了滚动图片：[details]', 1, 1598021962, 1598021962),
(46, 'cms', 'document_delete', '删除文档', '删除文档', '', '[user|get_nickname] 删除了文档：[details]', 1, 1598021962, 1598021962),
(47, 'cms', 'document_restore', '还原文档', '还原文档', '', '[user|get_nickname] 还原了文档：[details]', 1, 1598021962, 1598021962),
(48, 'cms', 'nav_disable', '禁用导航', '禁用导航', '', '[user|get_nickname] 禁用了导航：[details]', 1, 1598021962, 1598021962),
(49, 'cms', 'nav_enable', '启用导航', '启用导航', '', '[user|get_nickname] 启用了导航：[details]', 1, 1598021962, 1598021962),
(50, 'cms', 'nav_delete', '删除导航', '删除导航', '', '[user|get_nickname] 删除了导航：[details]', 1, 1598021962, 1598021962),
(51, 'cms', 'nav_edit', '编辑导航', '编辑导航', '', '[user|get_nickname] 编辑了导航：[details]', 1, 1598021962, 1598021962),
(52, 'cms', 'nav_add', '添加导航', '添加导航', '', '[user|get_nickname] 添加了导航：[details]', 1, 1598021962, 1598021962),
(53, 'cms', 'model_disable', '禁用内容模型', '禁用内容模型', '', '[user|get_nickname] 禁用了内容模型：[details]', 1, 1598021962, 1598021962),
(54, 'cms', 'model_enable', '启用内容模型', '启用内容模型', '', '[user|get_nickname] 启用了内容模型：[details]', 1, 1598021962, 1598021962),
(55, 'cms', 'model_delete', '删除内容模型', '删除内容模型', '', '[user|get_nickname] 删除了内容模型：[details]', 1, 1598021962, 1598021962),
(56, 'cms', 'model_edit', '编辑内容模型', '编辑内容模型', '', '[user|get_nickname] 编辑了内容模型：[details]', 1, 1598021962, 1598021962),
(57, 'cms', 'model_add', '添加内容模型', '添加内容模型', '', '[user|get_nickname] 添加了内容模型：[details]', 1, 1598021962, 1598021962),
(58, 'cms', 'menu_disable', '禁用导航菜单', '禁用导航菜单', '', '[user|get_nickname] 禁用了导航菜单：[details]', 1, 1598021962, 1598021962),
(59, 'cms', 'menu_enable', '启用导航菜单', '启用导航菜单', '', '[user|get_nickname] 启用了导航菜单：[details]', 1, 1598021962, 1598021962),
(60, 'cms', 'menu_delete', '删除导航菜单', '删除导航菜单', '', '[user|get_nickname] 删除了导航菜单：[details]', 1, 1598021962, 1598021962),
(61, 'cms', 'menu_edit', '编辑导航菜单', '编辑导航菜单', '', '[user|get_nickname] 编辑了导航菜单：[details]', 1, 1598021962, 1598021962),
(62, 'cms', 'menu_add', '添加导航菜单', '添加导航菜单', '', '[user|get_nickname] 添加了导航菜单：[details]', 1, 1598021962, 1598021962),
(63, 'cms', 'link_disable', '禁用友情链接', '禁用友情链接', '', '[user|get_nickname] 禁用了友情链接：[details]', 1, 1598021962, 1598021962),
(64, 'cms', 'link_enable', '启用友情链接', '启用友情链接', '', '[user|get_nickname] 启用了友情链接：[details]', 1, 1598021962, 1598021962),
(65, 'cms', 'link_delete', '删除友情链接', '删除友情链接', '', '[user|get_nickname] 删除了友情链接：[details]', 1, 1598021962, 1598021962),
(66, 'cms', 'link_edit', '编辑友情链接', '编辑友情链接', '', '[user|get_nickname] 编辑了友情链接：[details]', 1, 1598021962, 1598021962),
(67, 'cms', 'link_add', '添加友情链接', '添加友情链接', '', '[user|get_nickname] 添加了友情链接：[details]', 1, 1598021962, 1598021962),
(68, 'cms', 'field_disable', '禁用模型字段', '禁用模型字段', '', '[user|get_nickname] 禁用了模型字段：[details]', 1, 1598021962, 1598021962),
(69, 'cms', 'field_enable', '启用模型字段', '启用模型字段', '', '[user|get_nickname] 启用了模型字段：[details]', 1, 1598021962, 1598021962),
(70, 'cms', 'field_delete', '删除模型字段', '删除模型字段', '', '[user|get_nickname] 删除了模型字段：[details]', 1, 1598021962, 1598021962),
(71, 'cms', 'field_edit', '编辑模型字段', '编辑模型字段', '', '[user|get_nickname] 编辑了模型字段：[details]', 1, 1598021962, 1598021962),
(72, 'cms', 'field_add', '添加模型字段', '添加模型字段', '', '[user|get_nickname] 添加了模型字段：[details]', 1, 1598021962, 1598021962),
(73, 'cms', 'column_disable', '禁用栏目', '禁用栏目', '', '[user|get_nickname] 禁用了栏目：[details]', 1, 1598021962, 1598021962),
(74, 'cms', 'column_enable', '启用栏目', '启用栏目', '', '[user|get_nickname] 启用了栏目：[details]', 1, 1598021962, 1598021962),
(75, 'cms', 'column_delete', '删除栏目', '删除栏目', '', '[user|get_nickname] 删除了栏目：[details]', 1, 1598021962, 1598021962),
(76, 'cms', 'column_edit', '编辑栏目', '编辑栏目', '', '[user|get_nickname] 编辑了栏目：[details]', 1, 1598021962, 1598021962),
(77, 'cms', 'column_add', '添加栏目', '添加栏目', '', '[user|get_nickname] 添加了栏目：[details]', 1, 1598021962, 1598021962),
(78, 'cms', 'advert_type_disable', '禁用广告分类', '禁用广告分类', '', '[user|get_nickname] 禁用了广告分类：[details]', 1, 1598021962, 1598021962),
(79, 'cms', 'advert_type_enable', '启用广告分类', '启用广告分类', '', '[user|get_nickname] 启用了广告分类：[details]', 1, 1598021962, 1598021962),
(80, 'cms', 'advert_type_delete', '删除广告分类', '删除广告分类', '', '[user|get_nickname] 删除了广告分类：[details]', 1, 1598021962, 1598021962),
(81, 'cms', 'advert_type_edit', '编辑广告分类', '编辑广告分类', '', '[user|get_nickname] 编辑了广告分类：[details]', 1, 1598021962, 1598021962),
(82, 'cms', 'advert_type_add', '添加广告分类', '添加广告分类', '', '[user|get_nickname] 添加了广告分类：[details]', 1, 1598021962, 1598021962),
(83, 'cms', 'advert_disable', '禁用广告', '禁用广告', '', '[user|get_nickname] 禁用了广告：[details]', 1, 1598021962, 1598021962),
(84, 'cms', 'advert_enable', '启用广告', '启用广告', '', '[user|get_nickname] 启用了广告：[details]', 1, 1598021962, 1598021962),
(85, 'cms', 'advert_delete', '删除广告', '删除广告', '', '[user|get_nickname] 删除了广告：[details]', 1, 1598021962, 1598021962),
(86, 'cms', 'advert_edit', '编辑广告', '编辑广告', '', '[user|get_nickname] 编辑了广告：[details]', 1, 1598021962, 1598021962),
(87, 'cms', 'advert_add', '添加广告', '添加广告', '', '[user|get_nickname] 添加了广告：[details]', 1, 1598021962, 1598021962),
(88, 'cms', 'document_disable', '禁用文档', '禁用文档', '', '[user|get_nickname] 禁用了文档：[details]', 1, 1598021962, 1598021962),
(89, 'cms', 'document_enable', '启用文档', '启用文档', '', '[user|get_nickname] 启用了文档：[details]', 1, 1598021962, 1598021962),
(90, 'cms', 'document_trash', '回收文档', '回收文档', '', '[user|get_nickname] 回收了文档：[details]', 1, 1598021962, 1598021962),
(91, 'cms', 'document_edit', '编辑文档', '编辑文档', '', '[user|get_nickname] 编辑了文档：[details]', 1, 1598021962, 1598021962),
(92, 'cms', 'document_add', '添加文档', '添加文档', '', '[user|get_nickname] 添加了文档：[details]', 1, 1598021962, 1598021962),
(93, 'cms', 'slider_enable', '启用滚动图片', '启用滚动图片', '', '[user|get_nickname] 启用了滚动图片：[details]', 1, 1598021962, 1598021962),
(94, 'cms', 'slider_disable', '禁用滚动图片', '禁用滚动图片', '', '[user|get_nickname] 禁用了滚动图片：[details]', 1, 1598021962, 1598021962),
(95, 'cms', 'support_add', '添加客服', '添加客服', '', '[user|get_nickname] 添加了客服：[details]', 1, 1598021962, 1598021962),
(96, 'cms', 'support_edit', '编辑客服', '编辑客服', '', '[user|get_nickname] 编辑了客服：[details]', 1, 1598021962, 1598021962),
(97, 'cms', 'support_delete', '删除客服', '删除客服', '', '[user|get_nickname] 删除了客服：[details]', 1, 1598021962, 1598021962),
(98, 'cms', 'support_enable', '启用客服', '启用客服', '', '[user|get_nickname] 启用了客服：[details]', 1, 1598021962, 1598021962),
(99, 'cms', 'support_disable', '禁用客服', '禁用客服', '', '[user|get_nickname] 禁用了客服：[details]', 1, 1598021962, 1598021962),
(100, 'cms', 'location_add', '增加时区', '增加时区', '', '[user|get_nickname] 增加了时区：[details]', 1, 1603418349, 1603418349),
(101, 'cms', 'location_edit', '修改时区', '修改时区', '', '[user|get_nickname] 修改了时区：[details]', 1, 1603418381, 1603418381),
(102, 'cms', 'location_delete', '删除时区', '删除时区', '', '[user|get_nickname] 删除了时区：[details]', 1, 1603418417, 1603418417),
(103, 'cms', 'topic_add', '添加主题', '添加主题', '', '[user|get_nickname] 增加了主题：[details]', 1, 1603633462, 1603633462),
(104, 'cms', 'topic_edit', '修改主题', '修改主题', '', '[user|get_nickname] 修改了主题：[details]', 1, 1603633485, 1603633485),
(105, 'cms', 'topic_delete', '删除主题', '删除主题', '', '[user|get_nickname] 删除了主题：[details]', 1, 1603633505, 1603633505),
(106, 'cms', 'withdraw_check', '审核提现', '审核提现', '', '[user|get_nickname] 审核了提现：[details]', 1, 1604066014, 1604066014),
(107, 'cms', 'edit_user', '修改用户', '修改用户', '', '[user|get_nickname] 修改了用户：[details]', 1, 1604066067, 1604066067);

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_attachment`
--

CREATE TABLE `dp_admin_attachment` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `module` varchar(32) NOT NULL DEFAULT '' COMMENT '模块名，由哪个模块上传的',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件链接',
  `mime` varchar(128) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `ext` char(8) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT 'sha1 散列值',
  `driver` varchar(16) NOT NULL DEFAULT 'local' COMMENT '上传驱动',
  `download` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '下载次数',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `width` int(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '图片宽度',
  `height` int(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '图片高度'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';

--
-- 转存表中的数据 `dp_admin_attachment`
--

INSERT INTO `dp_admin_attachment` (`id`, `uid`, `name`, `module`, `path`, `thumb`, `url`, `mime`, `ext`, `size`, `md5`, `sha1`, `driver`, `download`, `create_time`, `update_time`, `sort`, `status`, `width`, `height`) VALUES
(1, 1, 'd0b273b6bdb4305827270fc2154f49ea.jpg', 'cms', 'uploads/images/20200822/2624882a62c78bdbb44f92f91fbd2b2a.jpg', '', '', 'image/jpeg', 'jpg', 418561, 'f68580abf376d6d2aff786387bb07e62', 'ae99b0c157178f097944147d7113664a74820490', 'local', 0, 1598066478, 1598066478, 100, 1, 1080, 432),
(2, 1, '201907011119051064.jpg', 'cms', 'uploads/images/20200823/0a40ffde4da82c636e7f80f6b003ef90.jpg', '', '', 'image/jpeg', 'jpg', 200088, 'c047419a19e61906a0ec67b2bb48e77b', '8ec84fc55eff57d81bd07bc926291b3e695e4449', 'local', 0, 1598151026, 1598151026, 100, 1, 800, 600),
(3, 1, 'u=3298449030,1016977366&fm=26&gp=0.jpg', 'cms', 'uploads/images/20200823/8bcd1e5b71818f03570c40cdc614a285.jpg', '', '', 'image/jpeg', 'jpg', 23525, '627a0c3689675a700639c9b5ee2745de', '7b26b5ab9ba3391aaa8d6649b4874f20fa374a06', 'local', 0, 1598155641, 1598155641, 100, 1, 500, 500),
(4, 1, 'wKgBEFtI7HiAITJMAAjZ8e_UITI96.jpeg', 'cms', 'uploads/images/20200823/1aa944b1fd1b5b491016005814f28062.jpeg', '', '', 'image/jpeg', 'jpeg', 130932, '8b59d324b1b26aa5feff9e1ee25a3d81', '046bfce7f4b140787a77dc9ba17cae320b6866fe', 'local', 0, 1598157954, 1598157954, 100, 1, 690, 450),
(5, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(6, 1, '/uploads/2018/06/191652496571.jpg', 'cms', '/uploads/2018/06/191652496571.jpg', '', '', 'image/jpeg', 'jpg', 0, 'dcb34f5b1ec99930c3a09a8f02f5f74d', 'dcb34f5b1ec99930c3a09a8f02f5f74d', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(7, 1, '/uploads/2018/06/191653279833.jpg', 'cms', '/uploads/2018/06/191653279833.jpg', '', '', 'image/jpeg', 'jpg', 0, '2245ec722951510375aee3db53e5950e', '2245ec722951510375aee3db53e5950e', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(8, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(9, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(10, 1, '/uploads/2018/06/191655581837.jpg', 'cms', '/uploads/2018/06/191655581837.jpg', '', '', 'image/jpeg', 'jpg', 0, '70aa806a6afee879a5792fe003a922b0', '70aa806a6afee879a5792fe003a922b0', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(11, 1, '/uploads/2018/06/191656473155.jpg', 'cms', '/uploads/2018/06/191656473155.jpg', '', '', 'image/jpeg', 'jpg', 0, '1b0278bfd55762420afaa735c95958c2', '1b0278bfd55762420afaa735c95958c2', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(12, 1, '/uploads/2018/06/191657166791.jpg', 'cms', '/uploads/2018/06/191657166791.jpg', '', '', 'image/jpeg', 'jpg', 0, '192c502ea9232b9a0e35fbed177a3756', '192c502ea9232b9a0e35fbed177a3756', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(13, 1, '/uploads/2018/06/191657384214.jpg', 'cms', '/uploads/2018/06/191657384214.jpg', '', '', 'image/jpeg', 'jpg', 0, '181e20d7dd7f7422aed840e713db893f', '181e20d7dd7f7422aed840e713db893f', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(14, 1, '/uploads/2018/06/191658073095.jpg', 'cms', '/uploads/2018/06/191658073095.jpg', '', '', 'image/jpeg', 'jpg', 0, '1592c9b2c5a84eed33820cac07f38d14', '1592c9b2c5a84eed33820cac07f38d14', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(15, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(16, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(17, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(18, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(19, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(20, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(21, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(22, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(23, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(24, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(25, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(26, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(27, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(28, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(29, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(30, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(31, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(32, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(33, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(34, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(35, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(36, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164204, 1598164204, 100, 1, 0, 0),
(37, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(38, 1, '/uploads/2018/06/191652496571.jpg', 'cms', '/uploads/2018/06/191652496571.jpg', '', '', 'image/jpeg', 'jpg', 0, 'dcb34f5b1ec99930c3a09a8f02f5f74d', 'dcb34f5b1ec99930c3a09a8f02f5f74d', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(39, 1, '/uploads/2018/06/191653279833.jpg', 'cms', '/uploads/2018/06/191653279833.jpg', '', '', 'image/jpeg', 'jpg', 0, '2245ec722951510375aee3db53e5950e', '2245ec722951510375aee3db53e5950e', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(40, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(41, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(42, 1, '/uploads/2018/06/191655581837.jpg', 'cms', '/uploads/2018/06/191655581837.jpg', '', '', 'image/jpeg', 'jpg', 0, '70aa806a6afee879a5792fe003a922b0', '70aa806a6afee879a5792fe003a922b0', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(43, 1, '/uploads/2018/06/191656473155.jpg', 'cms', '/uploads/2018/06/191656473155.jpg', '', '', 'image/jpeg', 'jpg', 0, '1b0278bfd55762420afaa735c95958c2', '1b0278bfd55762420afaa735c95958c2', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(44, 1, '/uploads/2018/06/191657166791.jpg', 'cms', '/uploads/2018/06/191657166791.jpg', '', '', 'image/jpeg', 'jpg', 0, '192c502ea9232b9a0e35fbed177a3756', '192c502ea9232b9a0e35fbed177a3756', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(45, 1, '/uploads/2018/06/191657384214.jpg', 'cms', '/uploads/2018/06/191657384214.jpg', '', '', 'image/jpeg', 'jpg', 0, '181e20d7dd7f7422aed840e713db893f', '181e20d7dd7f7422aed840e713db893f', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(46, 1, '/uploads/2018/06/191658073095.jpg', 'cms', '/uploads/2018/06/191658073095.jpg', '', '', 'image/jpeg', 'jpg', 0, '1592c9b2c5a84eed33820cac07f38d14', '1592c9b2c5a84eed33820cac07f38d14', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(47, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(48, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(49, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(50, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(51, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(52, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(53, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(54, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(55, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(56, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(57, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(58, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(59, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(60, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(61, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(62, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(63, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(64, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(65, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(66, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(67, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(68, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164258, 1598164258, 100, 1, 0, 0),
(69, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(70, 1, '/uploads/2018/06/191652496571.jpg', 'cms', '/uploads/2018/06/191652496571.jpg', '', '', 'image/jpeg', 'jpg', 0, 'dcb34f5b1ec99930c3a09a8f02f5f74d', 'dcb34f5b1ec99930c3a09a8f02f5f74d', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(71, 1, '/uploads/2018/06/191653279833.jpg', 'cms', '/uploads/2018/06/191653279833.jpg', '', '', 'image/jpeg', 'jpg', 0, '2245ec722951510375aee3db53e5950e', '2245ec722951510375aee3db53e5950e', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(72, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(73, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(74, 1, '/uploads/2018/06/191655581837.jpg', 'cms', '/uploads/2018/06/191655581837.jpg', '', '', 'image/jpeg', 'jpg', 0, '70aa806a6afee879a5792fe003a922b0', '70aa806a6afee879a5792fe003a922b0', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(75, 1, '/uploads/2018/06/191656473155.jpg', 'cms', '/uploads/2018/06/191656473155.jpg', '', '', 'image/jpeg', 'jpg', 0, '1b0278bfd55762420afaa735c95958c2', '1b0278bfd55762420afaa735c95958c2', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(76, 1, '/uploads/2018/06/191657166791.jpg', 'cms', '/uploads/2018/06/191657166791.jpg', '', '', 'image/jpeg', 'jpg', 0, '192c502ea9232b9a0e35fbed177a3756', '192c502ea9232b9a0e35fbed177a3756', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(77, 1, '/uploads/2018/06/191657384214.jpg', 'cms', '/uploads/2018/06/191657384214.jpg', '', '', 'image/jpeg', 'jpg', 0, '181e20d7dd7f7422aed840e713db893f', '181e20d7dd7f7422aed840e713db893f', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(78, 1, '/uploads/2018/06/191658073095.jpg', 'cms', '/uploads/2018/06/191658073095.jpg', '', '', 'image/jpeg', 'jpg', 0, '1592c9b2c5a84eed33820cac07f38d14', '1592c9b2c5a84eed33820cac07f38d14', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(79, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(80, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(81, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(82, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(83, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(84, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(85, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(86, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(87, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(88, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(89, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(90, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(91, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(92, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(93, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(94, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(95, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(96, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(97, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(98, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(99, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(100, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164270, 1598164270, 100, 1, 0, 0),
(101, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(102, 1, '/uploads/2018/06/191652496571.jpg', 'cms', '/uploads/2018/06/191652496571.jpg', '', '', 'image/jpeg', 'jpg', 0, 'dcb34f5b1ec99930c3a09a8f02f5f74d', 'dcb34f5b1ec99930c3a09a8f02f5f74d', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(103, 1, '/uploads/2018/06/191653279833.jpg', 'cms', '/uploads/2018/06/191653279833.jpg', '', '', 'image/jpeg', 'jpg', 0, '2245ec722951510375aee3db53e5950e', '2245ec722951510375aee3db53e5950e', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(104, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(105, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(106, 1, '/uploads/2018/06/191655581837.jpg', 'cms', '/uploads/2018/06/191655581837.jpg', '', '', 'image/jpeg', 'jpg', 0, '70aa806a6afee879a5792fe003a922b0', '70aa806a6afee879a5792fe003a922b0', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(107, 1, '/uploads/2018/06/191656473155.jpg', 'cms', '/uploads/2018/06/191656473155.jpg', '', '', 'image/jpeg', 'jpg', 0, '1b0278bfd55762420afaa735c95958c2', '1b0278bfd55762420afaa735c95958c2', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(108, 1, '/uploads/2018/06/191657166791.jpg', 'cms', '/uploads/2018/06/191657166791.jpg', '', '', 'image/jpeg', 'jpg', 0, '192c502ea9232b9a0e35fbed177a3756', '192c502ea9232b9a0e35fbed177a3756', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(109, 1, '/uploads/2018/06/191657384214.jpg', 'cms', '/uploads/2018/06/191657384214.jpg', '', '', 'image/jpeg', 'jpg', 0, '181e20d7dd7f7422aed840e713db893f', '181e20d7dd7f7422aed840e713db893f', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(110, 1, '/uploads/2018/06/191658073095.jpg', 'cms', '/uploads/2018/06/191658073095.jpg', '', '', 'image/jpeg', 'jpg', 0, '1592c9b2c5a84eed33820cac07f38d14', '1592c9b2c5a84eed33820cac07f38d14', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(111, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(112, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(113, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(114, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(115, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(116, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(117, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(118, 1, 'http://www.syyyy.cn/pic/2013811134522.jpg', 'cms', 'http://www.syyyy.cn/pic/2013811134522.jpg', '', '', 'image/jpeg', 'syyyy', 0, '904531a0baefee1edf5af709fedc00d1', '904531a0baefee1edf5af709fedc00d1', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(119, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(120, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(121, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(122, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(123, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(124, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(125, 1, '/uploads/2018/06/191654224411.jpg', 'cms', '/uploads/2018/06/191654224411.jpg', '', '', 'image/jpeg', 'jpg', 0, '16e16f8bedd10f0254f17e726cbb6374', '16e16f8bedd10f0254f17e726cbb6374', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(126, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(127, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(128, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(129, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(130, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(131, 1, '/uploads/2018/06/191658454445.jpg', 'cms', '/uploads/2018/06/191658454445.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cfd553298ccbc58805f4e199b5cf5aac', 'cfd553298ccbc58805f4e199b5cf5aac', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(132, 1, '/uploads/2018/06/191653591345.jpg', 'cms', '/uploads/2018/06/191653591345.jpg', '', '', 'image/jpeg', 'jpg', 0, '75df750c13327e3603ff56a05b4a7d8c', '75df750c13327e3603ff56a05b4a7d8c', 'local', 0, 1598164311, 1598164311, 100, 1, 0, 0),
(133, 1, '/uploads/2018/06/071505067372.jpg', 'cms', '/uploads/2018/06/071505067372.jpg', '', '', 'image/jpeg', 'jpg', 0, 'f66d2661c3b0f8473735f22802b8f388', 'f66d2661c3b0f8473735f22802b8f388', 'local', 0, 1598164805, 1598164805, 100, 1, 0, 0),
(134, 1, '/uploads/2018/06/071505067372.jpg', 'cms', '/uploads/2018/06/071505067372.jpg', '', '', 'image/jpeg', 'jpg', 0, 'f66d2661c3b0f8473735f22802b8f388', 'f66d2661c3b0f8473735f22802b8f388', 'local', 0, 1598164846, 1598164846, 100, 1, 0, 0),
(135, 1, '/uploads/2018/06/071505067372.jpg', 'cms', '/uploads/2018/06/071505067372.jpg', '', '', 'image/jpeg', 'jpg', 0, 'f66d2661c3b0f8473735f22802b8f388', 'f66d2661c3b0f8473735f22802b8f388', 'local', 0, 1598164856, 1598164856, 100, 1, 0, 0),
(136, 1, '/uploads/2018/06/071505067372.jpg', 'cms', '/uploads/2018/06/071505067372.jpg', '', '', 'image/jpeg', 'jpg', 0, 'f66d2661c3b0f8473735f22802b8f388', 'f66d2661c3b0f8473735f22802b8f388', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(137, 1, '/uploads/2018/06/081618098654.jpg', 'cms', '/uploads/2018/06/081618098654.jpg', '', '', 'image/jpeg', 'jpg', 0, '52f1ea21eca7a3468729901700743e42', '52f1ea21eca7a3468729901700743e42', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(138, 1, '/uploads/2018/06/081428334727.jpg', 'cms', '/uploads/2018/06/081428334727.jpg', '', '', 'image/jpeg', 'jpg', 0, 'b95d2d58c462ce5e01d06bf5ab20dd5c', 'b95d2d58c462ce5e01d06bf5ab20dd5c', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(139, 1, '/uploads/2018/06/081430454031.jpg', 'cms', '/uploads/2018/06/081430454031.jpg', '', '', 'image/jpeg', 'jpg', 0, '229d891bc3ef0630f7277879a8eb1d16', '229d891bc3ef0630f7277879a8eb1d16', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(140, 1, '/uploads/2018/06/130943354441.jpg', 'cms', '/uploads/2018/06/130943354441.jpg', '', '', 'image/jpeg', 'jpg', 0, '371b1ffcf72ce74b33cf0134233d965b', '371b1ffcf72ce74b33cf0134233d965b', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(141, 1, '/uploads/2018/06/130944214180.jpg', 'cms', '/uploads/2018/06/130944214180.jpg', '', '', 'image/jpeg', 'jpg', 0, '84993ce67452b750039f2acdb8cdeb3c', '84993ce67452b750039f2acdb8cdeb3c', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(142, 1, '/uploads/2019/11/251511241496.jpg', 'cms', '/uploads/2019/11/251511241496.jpg', '', '', 'image/jpeg', 'jpg', 0, '7aa0d0629e0ada6c74c84eaacb01d757', '7aa0d0629e0ada6c74c84eaacb01d757', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(143, 1, '/uploads/2019/11/251511036980.jpg', 'cms', '/uploads/2019/11/251511036980.jpg', '', '', 'image/jpeg', 'jpg', 0, '714d80e88db1f7abb9ab9697687bd66b', '714d80e88db1f7abb9ab9697687bd66b', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(144, 1, '/uploads/2019/11/251510128534.jpg', 'cms', '/uploads/2019/11/251510128534.jpg', '', '', 'image/jpeg', 'jpg', 0, '9ee683938249526e33e72d88e6a14734', '9ee683938249526e33e72d88e6a14734', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(145, 1, '/uploads/2019/11/251509503093.jpg', 'cms', '/uploads/2019/11/251509503093.jpg', '', '', 'image/jpeg', 'jpg', 0, 'ae747c97485104768acaa51602b374d2', 'ae747c97485104768acaa51602b374d2', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(146, 1, '/uploads/2019/11/251509315828.jpg', 'cms', '/uploads/2019/11/251509315828.jpg', '', '', 'image/jpeg', 'jpg', 0, 'a564a569ce07a07a9802e24a70983c0e', 'a564a569ce07a07a9802e24a70983c0e', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(147, 1, '/uploads/2019/11/251509116323.jpg', 'cms', '/uploads/2019/11/251509116323.jpg', '', '', 'image/jpeg', 'jpg', 0, '63813376c210a1f6b12c45486f746470', '63813376c210a1f6b12c45486f746470', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(148, 1, '/uploads/2019/11/251508254531.jpg', 'cms', '/uploads/2019/11/251508254531.jpg', '', '', 'image/jpeg', 'jpg', 0, 'c625056e4013a72e17fd53c090b0f4ce', 'c625056e4013a72e17fd53c090b0f4ce', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(149, 1, '/uploads/2018/06/130950334839.jpg', 'cms', '/uploads/2018/06/130950334839.jpg', '', '', 'image/jpeg', 'jpg', 0, '3a2f2de0fd317322b926b0384a5f4371', '3a2f2de0fd317322b926b0384a5f4371', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(150, 1, '/uploads/2018/06/130953078152.jpg', 'cms', '/uploads/2018/06/130953078152.jpg', '', '', 'image/jpeg', 'jpg', 0, '1dd8385089e3e7bafb8d1efdee2b63f6', '1dd8385089e3e7bafb8d1efdee2b63f6', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(151, 1, '/uploads/2018/06/130954011334.jpg', 'cms', '/uploads/2018/06/130954011334.jpg', '', '', 'image/jpeg', 'jpg', 0, 'caa82d443ec11f89872cd4c1c85e57b9', 'caa82d443ec11f89872cd4c1c85e57b9', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(152, 1, '/uploads/2018/06/130954411653.jpg', 'cms', '/uploads/2018/06/130954411653.jpg', '', '', 'image/jpeg', 'jpg', 0, 'a1445b8718ae8d8df2cf9abc39c1804c', 'a1445b8718ae8d8df2cf9abc39c1804c', 'local', 0, 1598164866, 1598164866, 100, 1, 0, 0),
(153, 1, '/uploads/2018/06/071505067372.jpg', 'cms', '/uploads/2018/06/071505067372.jpg', '', '', 'image/jpeg', 'jpg', 0, 'f66d2661c3b0f8473735f22802b8f388', 'f66d2661c3b0f8473735f22802b8f388', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(154, 1, '/uploads/2018/06/081618098654.jpg', 'cms', '/uploads/2018/06/081618098654.jpg', '', '', 'image/jpeg', 'jpg', 0, '52f1ea21eca7a3468729901700743e42', '52f1ea21eca7a3468729901700743e42', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(155, 1, '/uploads/2018/06/081428334727.jpg', 'cms', '/uploads/2018/06/081428334727.jpg', '', '', 'image/jpeg', 'jpg', 0, 'b95d2d58c462ce5e01d06bf5ab20dd5c', 'b95d2d58c462ce5e01d06bf5ab20dd5c', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(156, 1, '/uploads/2018/06/081430454031.jpg', 'cms', '/uploads/2018/06/081430454031.jpg', '', '', 'image/jpeg', 'jpg', 0, '229d891bc3ef0630f7277879a8eb1d16', '229d891bc3ef0630f7277879a8eb1d16', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(157, 1, '/uploads/2018/06/130943354441.jpg', 'cms', '/uploads/2018/06/130943354441.jpg', '', '', 'image/jpeg', 'jpg', 0, '371b1ffcf72ce74b33cf0134233d965b', '371b1ffcf72ce74b33cf0134233d965b', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(158, 1, '/uploads/2018/06/130944214180.jpg', 'cms', '/uploads/2018/06/130944214180.jpg', '', '', 'image/jpeg', 'jpg', 0, '84993ce67452b750039f2acdb8cdeb3c', '84993ce67452b750039f2acdb8cdeb3c', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(159, 1, '/uploads/2019/11/251511241496.jpg', 'cms', '/uploads/2019/11/251511241496.jpg', '', '', 'image/jpeg', 'jpg', 0, '7aa0d0629e0ada6c74c84eaacb01d757', '7aa0d0629e0ada6c74c84eaacb01d757', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(160, 1, '/uploads/2019/11/251511036980.jpg', 'cms', '/uploads/2019/11/251511036980.jpg', '', '', 'image/jpeg', 'jpg', 0, '714d80e88db1f7abb9ab9697687bd66b', '714d80e88db1f7abb9ab9697687bd66b', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(161, 1, '/uploads/2019/11/251510128534.jpg', 'cms', '/uploads/2019/11/251510128534.jpg', '', '', 'image/jpeg', 'jpg', 0, '9ee683938249526e33e72d88e6a14734', '9ee683938249526e33e72d88e6a14734', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(162, 1, '/uploads/2019/11/251509503093.jpg', 'cms', '/uploads/2019/11/251509503093.jpg', '', '', 'image/jpeg', 'jpg', 0, 'ae747c97485104768acaa51602b374d2', 'ae747c97485104768acaa51602b374d2', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(163, 1, '/uploads/2019/11/251509315828.jpg', 'cms', '/uploads/2019/11/251509315828.jpg', '', '', 'image/jpeg', 'jpg', 0, 'a564a569ce07a07a9802e24a70983c0e', 'a564a569ce07a07a9802e24a70983c0e', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(164, 1, '/uploads/2019/11/251509116323.jpg', 'cms', '/uploads/2019/11/251509116323.jpg', '', '', 'image/jpeg', 'jpg', 0, '63813376c210a1f6b12c45486f746470', '63813376c210a1f6b12c45486f746470', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(165, 1, '/uploads/2019/11/251508254531.jpg', 'cms', '/uploads/2019/11/251508254531.jpg', '', '', 'image/jpeg', 'jpg', 0, 'c625056e4013a72e17fd53c090b0f4ce', 'c625056e4013a72e17fd53c090b0f4ce', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(166, 1, '/uploads/2018/06/130950334839.jpg', 'cms', '/uploads/2018/06/130950334839.jpg', '', '', 'image/jpeg', 'jpg', 0, '3a2f2de0fd317322b926b0384a5f4371', '3a2f2de0fd317322b926b0384a5f4371', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(167, 1, '/uploads/2018/06/130953078152.jpg', 'cms', '/uploads/2018/06/130953078152.jpg', '', '', 'image/jpeg', 'jpg', 0, '1dd8385089e3e7bafb8d1efdee2b63f6', '1dd8385089e3e7bafb8d1efdee2b63f6', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(168, 1, '/uploads/2018/06/130954011334.jpg', 'cms', '/uploads/2018/06/130954011334.jpg', '', '', 'image/jpeg', 'jpg', 0, 'caa82d443ec11f89872cd4c1c85e57b9', 'caa82d443ec11f89872cd4c1c85e57b9', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(169, 1, '/uploads/2018/06/130954411653.jpg', 'cms', '/uploads/2018/06/130954411653.jpg', '', '', 'image/jpeg', 'jpg', 0, 'a1445b8718ae8d8df2cf9abc39c1804c', 'a1445b8718ae8d8df2cf9abc39c1804c', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(170, 1, '/uploads/2018/08/231005374148.jpg', 'cms', '/uploads/2018/08/231005374148.jpg', '', '', 'image/jpeg', 'jpg', 0, 'ae9865659c058ba6370e146b5ca5c537', 'ae9865659c058ba6370e146b5ca5c537', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(171, 1, '/uploads/2018/08/231018183254.jpg', 'cms', '/uploads/2018/08/231018183254.jpg', '', '', 'image/jpeg', 'jpg', 0, 'e310557505428d7a828a64ec77c12457', 'e310557505428d7a828a64ec77c12457', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(172, 1, '/uploads/2018/08/231034503101.jpg', 'cms', '/uploads/2018/08/231034503101.jpg', '', '', 'image/jpeg', 'jpg', 0, 'd05e44a846555de515ad7beca9d0e62f', 'd05e44a846555de515ad7beca9d0e62f', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(173, 1, '/uploads/2018/08/232037555253.jpg', 'cms', '/uploads/2018/08/232037555253.jpg', '', '', 'image/jpeg', 'jpg', 0, '03b1fa43914587be984aa0cd9d464311', '03b1fa43914587be984aa0cd9d464311', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(174, 1, '/uploads/2018/08/232041096244.jpg', 'cms', '/uploads/2018/08/232041096244.jpg', '', '', 'image/jpeg', 'jpg', 0, '713c48d1ba449807aec19f48b81b0504', '713c48d1ba449807aec19f48b81b0504', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(175, 1, '/uploads/2018/08/232042064919.jpg', 'cms', '/uploads/2018/08/232042064919.jpg', '', '', 'image/jpeg', 'jpg', 0, '5eccea3f991248d7f4eb57c7955ba2ff', '5eccea3f991248d7f4eb57c7955ba2ff', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(176, 1, '/uploads/2018/08/232045051997.jpg', 'cms', '/uploads/2018/08/232045051997.jpg', '', '', 'image/jpeg', 'jpg', 0, '0bc09086039d5daa80b78f93a8ad274b', '0bc09086039d5daa80b78f93a8ad274b', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(177, 1, '/uploads/2018/08/232045286072.jpg', 'cms', '/uploads/2018/08/232045286072.jpg', '', '', 'image/jpeg', 'jpg', 0, '852b8c9ba82e2e07003b690605838106', '852b8c9ba82e2e07003b690605838106', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(178, 1, '/uploads/2018/08/232046169212.jpg', 'cms', '/uploads/2018/08/232046169212.jpg', '', '', 'image/jpeg', 'jpg', 0, '054f76d4fa732f5760e8631630330ba2', '054f76d4fa732f5760e8631630330ba2', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(179, 1, '/uploads/2019/12/121629174654.jpg', 'cms', '/uploads/2019/12/121629174654.jpg', '', '', 'image/jpeg', 'jpg', 0, 'f32203aec58ffe0768ac8dd3b2cf155c', 'f32203aec58ffe0768ac8dd3b2cf155c', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(180, 1, '/uploads/2018/08/232052406207.jpg', 'cms', '/uploads/2018/08/232052406207.jpg', '', '', 'image/jpeg', 'jpg', 0, '17872322edf3a1fc810405238a2ca479', '17872322edf3a1fc810405238a2ca479', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(181, 1, '/uploads/2018/08/232053111070.jpg', 'cms', '/uploads/2018/08/232053111070.jpg', '', '', 'image/jpeg', 'jpg', 0, '4f1879a4de256441ad47347591d472a9', '4f1879a4de256441ad47347591d472a9', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(182, 1, '/uploads/2019/12/121631068099.jpg', 'cms', '/uploads/2019/12/121631068099.jpg', '', '', 'image/jpeg', 'jpg', 0, '90ef0341dabfe4b3a49cc787c6094993', '90ef0341dabfe4b3a49cc787c6094993', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(183, 1, '/uploads/2018/08/232054578745.jpg', 'cms', '/uploads/2018/08/232054578745.jpg', '', '', 'image/jpeg', 'jpg', 0, 'ea4cf6562a1c4eb7ebf006146a5d9bdf', 'ea4cf6562a1c4eb7ebf006146a5d9bdf', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(184, 1, '/uploads/2018/08/232056462179.jpg', 'cms', '/uploads/2018/08/232056462179.jpg', '', '', 'image/jpeg', 'jpg', 0, '3af867b47ae7ba69b6872ccefacbafad', '3af867b47ae7ba69b6872ccefacbafad', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(185, 1, '/uploads/2018/08/232057199611.jpg', 'cms', '/uploads/2018/08/232057199611.jpg', '', '', 'image/jpeg', 'jpg', 0, 'c025098f8d822c7e4db01f4a56a7887e', 'c025098f8d822c7e4db01f4a56a7887e', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(186, 1, '/uploads/2018/08/232110197356.jpg', 'cms', '/uploads/2018/08/232110197356.jpg', '', '', 'image/jpeg', 'jpg', 0, 'ada79f3258480821febabf16c32f775e', 'ada79f3258480821febabf16c32f775e', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(187, 1, '/uploads/2018/08/232115222438.jpg', 'cms', '/uploads/2018/08/232115222438.jpg', '', '', 'image/jpeg', 'jpg', 0, 'ed05cc0ae7594e5bfe99838c82e50a01', 'ed05cc0ae7594e5bfe99838c82e50a01', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(188, 1, '/uploads/2018/08/232115547588.jpg', 'cms', '/uploads/2018/08/232115547588.jpg', '', '', 'image/jpeg', 'jpg', 0, '06663ad031398fabfb409e884f687a7e', '06663ad031398fabfb409e884f687a7e', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(189, 1, '/uploads/2018/08/232120409638.jpg', 'cms', '/uploads/2018/08/232120409638.jpg', '', '', 'image/jpeg', 'jpg', 0, '53b2d292c29f77d336b070d01ad0e2e9', '53b2d292c29f77d336b070d01ad0e2e9', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(190, 1, '/uploads/2018/08/232117416221.jpg', 'cms', '/uploads/2018/08/232117416221.jpg', '', '', 'image/jpeg', 'jpg', 0, '613d2daa544a22d3bff9cbc7a6fd691f', '613d2daa544a22d3bff9cbc7a6fd691f', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(191, 1, '/uploads/2018/08/232118581991.jpg', 'cms', '/uploads/2018/08/232118581991.jpg', '', '', 'image/jpeg', 'jpg', 0, 'dbd23874104df0d2b7b952c64808a5ad', 'dbd23874104df0d2b7b952c64808a5ad', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(192, 1, '/uploads/2018/08/232123237046.jpg', 'cms', '/uploads/2018/08/232123237046.jpg', '', '', 'image/jpeg', 'jpg', 0, 'eac8bf608d6a5c2a756b57b28c92d2e2', 'eac8bf608d6a5c2a756b57b28c92d2e2', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(193, 1, '/uploads/2018/08/232122203246.jpg', 'cms', '/uploads/2018/08/232122203246.jpg', '', '', 'image/jpeg', 'jpg', 0, '730d7a5d36ae1d0928cb3f842a0fa167', '730d7a5d36ae1d0928cb3f842a0fa167', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(194, 1, '/uploads/2018/08/232122559404.png', 'cms', '/uploads/2018/08/232122559404.png', '', '', 'image/jpeg', 'png', 0, 'c5abc9c654400a6c3191c72d9f0865ed', 'c5abc9c654400a6c3191c72d9f0865ed', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(195, 1, '/uploads/2018/08/232130197094.jpg', 'cms', '/uploads/2018/08/232130197094.jpg', '', '', 'image/jpeg', 'jpg', 0, 'b5a41d9815aa9ea39b837b90747fcc0f', 'b5a41d9815aa9ea39b837b90747fcc0f', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(196, 1, '/uploads/2018/08/232130516387.jpg', 'cms', '/uploads/2018/08/232130516387.jpg', '', '', 'image/jpeg', 'jpg', 0, '4951a590e58ec3cc77550297cb50b218', '4951a590e58ec3cc77550297cb50b218', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(197, 1, '/uploads/2018/08/232131296980.jpg', 'cms', '/uploads/2018/08/232131296980.jpg', '', '', 'image/jpeg', 'jpg', 0, '8e317cf41ca5705032c6e2ce786d71a9', '8e317cf41ca5705032c6e2ce786d71a9', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(198, 1, '/uploads/2018/08/232132403252.jpg', 'cms', '/uploads/2018/08/232132403252.jpg', '', '', 'image/jpeg', 'jpg', 0, '9c6845f953621b23bbb56b088c15dab9', '9c6845f953621b23bbb56b088c15dab9', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(199, 1, '/uploads/2018/08/240021135696.jpg', 'cms', '/uploads/2018/08/240021135696.jpg', '', '', 'image/jpeg', 'jpg', 0, '3bcbffe2d9f850cbe08bcd57561f84d0', '3bcbffe2d9f850cbe08bcd57561f84d0', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(200, 1, '/uploads/2018/08/232133142603.jpg', 'cms', '/uploads/2018/08/232133142603.jpg', '', '', 'image/jpeg', 'jpg', 0, '27fa38f85f0fa7af2338a869f08b0af6', '27fa38f85f0fa7af2338a869f08b0af6', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(201, 1, '/uploads/2018/08/232133573954.jpg', 'cms', '/uploads/2018/08/232133573954.jpg', '', '', 'image/jpeg', 'jpg', 0, '92be1744702e94e100c8af2243d96ea8', '92be1744702e94e100c8af2243d96ea8', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(202, 1, '/uploads/2018/08/232134328851.jpg', 'cms', '/uploads/2018/08/232134328851.jpg', '', '', 'image/jpeg', 'jpg', 0, '37db23b7ea15106435cacf7e6444f32d', '37db23b7ea15106435cacf7e6444f32d', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0);
INSERT INTO `dp_admin_attachment` (`id`, `uid`, `name`, `module`, `path`, `thumb`, `url`, `mime`, `ext`, `size`, `md5`, `sha1`, `driver`, `download`, `create_time`, `update_time`, `sort`, `status`, `width`, `height`) VALUES
(203, 1, '/uploads/2018/08/232135562513.jpg', 'cms', '/uploads/2018/08/232135562513.jpg', '', '', 'image/jpeg', 'jpg', 0, '506338d4da51ea967f6e698bb16df312', '506338d4da51ea967f6e698bb16df312', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(204, 1, '/uploads/2018/08/232136344706.jpg', 'cms', '/uploads/2018/08/232136344706.jpg', '', '', 'image/jpeg', 'jpg', 0, '86dca23c4850c208e9c6ebbc907783f9', '86dca23c4850c208e9c6ebbc907783f9', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(205, 1, '/uploads/2018/08/232137081812.jpg', 'cms', '/uploads/2018/08/232137081812.jpg', '', '', 'image/jpeg', 'jpg', 0, 'c87e94804c64194e5464e435ee83615a', 'c87e94804c64194e5464e435ee83615a', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(206, 1, '/uploads/2018/08/232137383326.jpg', 'cms', '/uploads/2018/08/232137383326.jpg', '', '', 'image/jpeg', 'jpg', 0, '9d217842459ea313ec0e13221c18f7b6', '9d217842459ea313ec0e13221c18f7b6', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(207, 1, '/uploads/2018/08/232138126774.jpg', 'cms', '/uploads/2018/08/232138126774.jpg', '', '', 'image/jpeg', 'jpg', 0, 'b0148459a4e091b56e16652b3d9b2df6', 'b0148459a4e091b56e16652b3d9b2df6', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(208, 1, '/uploads/2018/08/232138572236.jpg', 'cms', '/uploads/2018/08/232138572236.jpg', '', '', 'image/jpeg', 'jpg', 0, '0b96fea521e3cedd7f15f84fd7d827f1', '0b96fea521e3cedd7f15f84fd7d827f1', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(209, 1, '/uploads/2018/08/232141221943.jpg', 'cms', '/uploads/2018/08/232141221943.jpg', '', '', 'image/jpeg', 'jpg', 0, '11660fe069b994954cae9bce4a4ee1c9', '11660fe069b994954cae9bce4a4ee1c9', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(210, 1, '/uploads/2018/08/232142546600.jpg', 'cms', '/uploads/2018/08/232142546600.jpg', '', '', 'image/jpeg', 'jpg', 0, '248cc8625f25de3aeca753b9751abcea', '248cc8625f25de3aeca753b9751abcea', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(211, 1, '/uploads/2018/08/232146146648.jpg', 'cms', '/uploads/2018/08/232146146648.jpg', '', '', 'image/jpeg', 'jpg', 0, '755d756a33fc9a82c02ec0fed76c8775', '755d756a33fc9a82c02ec0fed76c8775', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(212, 1, '/uploads/2018/08/232147013371.jpg', 'cms', '/uploads/2018/08/232147013371.jpg', '', '', 'image/jpeg', 'jpg', 0, 'e6bca319a370f5d1fafb2187d73901ca', 'e6bca319a370f5d1fafb2187d73901ca', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(213, 1, '/uploads/2018/08/232147404807.jpg', 'cms', '/uploads/2018/08/232147404807.jpg', '', '', 'image/jpeg', 'jpg', 0, '67e8b6d547f90381e9cf6a33394d8334', '67e8b6d547f90381e9cf6a33394d8334', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(214, 1, '/uploads/2018/08/232148192396.jpg', 'cms', '/uploads/2018/08/232148192396.jpg', '', '', 'image/jpeg', 'jpg', 0, '393c2d173f9c089913623f84a066d4cb', '393c2d173f9c089913623f84a066d4cb', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(215, 1, '/uploads/2018/08/232149078356.jpg', 'cms', '/uploads/2018/08/232149078356.jpg', '', '', 'image/jpeg', 'jpg', 0, 'bede81033741209e42908e2692cf5ba7', 'bede81033741209e42908e2692cf5ba7', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(216, 1, '/uploads/2019/12/111638439458.jpg', 'cms', '/uploads/2019/12/111638439458.jpg', '', '', 'image/jpeg', 'jpg', 0, '8aaa5121eff4d34fa2c4c06199d4794b', '8aaa5121eff4d34fa2c4c06199d4794b', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(217, 1, '/uploads/2018/08/232200586609.jpg', 'cms', '/uploads/2018/08/232200586609.jpg', '', '', 'image/jpeg', 'jpg', 0, 'afd1d97a99a306f2d7a5a8a85389c9dc', 'afd1d97a99a306f2d7a5a8a85389c9dc', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(218, 1, '/uploads/2018/08/232201451896.jpg', 'cms', '/uploads/2018/08/232201451896.jpg', '', '', 'image/jpeg', 'jpg', 0, '1688ded7e018ea0bf19853c902242392', '1688ded7e018ea0bf19853c902242392', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(219, 1, '/uploads/2018/08/232202307620.jpg', 'cms', '/uploads/2018/08/232202307620.jpg', '', '', 'image/jpeg', 'jpg', 0, '99b3161e874c22812b9349da184b21be', '99b3161e874c22812b9349da184b21be', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(220, 1, '/uploads/2018/08/232202536029.jpg', 'cms', '/uploads/2018/08/232202536029.jpg', '', '', 'image/jpeg', 'jpg', 0, '6b9f55bcbadd97a44861fed8ee76ed91', '6b9f55bcbadd97a44861fed8ee76ed91', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(221, 1, '/uploads/2018/08/232203532624.jpg', 'cms', '/uploads/2018/08/232203532624.jpg', '', '', 'image/jpeg', 'jpg', 0, 'fe94fcb9d7487ff33cd0dbf8b6cae35d', 'fe94fcb9d7487ff33cd0dbf8b6cae35d', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(222, 1, '/uploads/2019/12/111607532963.jpg', 'cms', '/uploads/2019/12/111607532963.jpg', '', '', 'image/jpeg', 'jpg', 0, '11ff8daf4a085bc36b08aa55138d4884', '11ff8daf4a085bc36b08aa55138d4884', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(223, 1, '/uploads/2018/08/232205412711.jpg', 'cms', '/uploads/2018/08/232205412711.jpg', '', '', 'image/jpeg', 'jpg', 0, '6acc84dbf614799d7d7406ef0d1c2568', '6acc84dbf614799d7d7406ef0d1c2568', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(224, 1, '/uploads/2018/08/232206193131.jpg', 'cms', '/uploads/2018/08/232206193131.jpg', '', '', 'image/jpeg', 'jpg', 0, '52505693d8fd72097e6286896f1ae5e3', '52505693d8fd72097e6286896f1ae5e3', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(225, 1, '/uploads/2018/08/232208162757.jpg', 'cms', '/uploads/2018/08/232208162757.jpg', '', '', 'image/jpeg', 'jpg', 0, '2bedfb44f38c322b5648b0b0830275d7', '2bedfb44f38c322b5648b0b0830275d7', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(226, 1, '/uploads/2018/08/232209303214.jpg', 'cms', '/uploads/2018/08/232209303214.jpg', '', '', 'image/jpeg', 'jpg', 0, 'b63b28d7fadc9bb0b9a92c36b229ea17', 'b63b28d7fadc9bb0b9a92c36b229ea17', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(227, 1, '/uploads/2018/08/232209594093.jpg', 'cms', '/uploads/2018/08/232209594093.jpg', '', '', 'image/jpeg', 'jpg', 0, 'adcfe2935aae594a6f8a96ae2358c99a', 'adcfe2935aae594a6f8a96ae2358c99a', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(228, 1, '/uploads/2019/12/111601226850.jpg', 'cms', '/uploads/2019/12/111601226850.jpg', '', '', 'image/jpeg', 'jpg', 0, '81d7b0ce7491ef8cb53caaeacd1d1e78', '81d7b0ce7491ef8cb53caaeacd1d1e78', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(229, 1, '/uploads/2019/12/121602535694.jpg', 'cms', '/uploads/2019/12/121602535694.jpg', '', '', 'image/jpeg', 'jpg', 0, 'c1d0c9c02c2b47418a5340956e3bbdbf', 'c1d0c9c02c2b47418a5340956e3bbdbf', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(230, 1, '/uploads/2018/08/232211248718.jpg', 'cms', '/uploads/2018/08/232211248718.jpg', '', '', 'image/jpeg', 'jpg', 0, '6891d227554774ac67243fc7b228b609', '6891d227554774ac67243fc7b228b609', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(231, 1, '/uploads/2018/08/232212532119.jpg', 'cms', '/uploads/2018/08/232212532119.jpg', '', '', 'image/jpeg', 'jpg', 0, '0e61dddfc45859183f997441ef370037', '0e61dddfc45859183f997441ef370037', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(232, 1, '/uploads/2019/12/111555195589.jpg', 'cms', '/uploads/2019/12/111555195589.jpg', '', '', 'image/jpeg', 'jpg', 0, '53968321086b075790b796733d6643a9', '53968321086b075790b796733d6643a9', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(233, 1, '/uploads/2018/08/232314551912.jpg', 'cms', '/uploads/2018/08/232314551912.jpg', '', '', 'image/jpeg', 'jpg', 0, '0fe9c28c5d3b94828ba48183142c3507', '0fe9c28c5d3b94828ba48183142c3507', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(234, 1, '/uploads/2018/08/232315317891.jpg', 'cms', '/uploads/2018/08/232315317891.jpg', '', '', 'image/jpeg', 'jpg', 0, '091c3ecb14b307e461a3871587a9ed99', '091c3ecb14b307e461a3871587a9ed99', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(235, 1, '/uploads/2018/08/232316031221.jpg', 'cms', '/uploads/2018/08/232316031221.jpg', '', '', 'image/jpeg', 'jpg', 0, '7ed1ba92236723c738da4ba04767c1c5', '7ed1ba92236723c738da4ba04767c1c5', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(236, 1, '/uploads/2018/08/232316508490.jpg', 'cms', '/uploads/2018/08/232316508490.jpg', '', '', 'image/jpeg', 'jpg', 0, 'f5a6447cd960223cb7c72174bf86c337', 'f5a6447cd960223cb7c72174bf86c337', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(237, 1, '/uploads/2018/08/232317558024.jpg', 'cms', '/uploads/2018/08/232317558024.jpg', '', '', 'image/jpeg', 'jpg', 0, '49e214512e1412f11f3448dd8e8c4798', '49e214512e1412f11f3448dd8e8c4798', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(238, 1, '/uploads/2018/08/232319516716.jpg', 'cms', '/uploads/2018/08/232319516716.jpg', '', '', 'image/jpeg', 'jpg', 0, '49f20a740776bd4b753f8748f63d840d', '49f20a740776bd4b753f8748f63d840d', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(239, 1, '/uploads/2018/08/232320278159.jpg', 'cms', '/uploads/2018/08/232320278159.jpg', '', '', 'image/jpeg', 'jpg', 0, '4098f3f187e7e92246c6094feafeb55b', '4098f3f187e7e92246c6094feafeb55b', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(240, 1, '/uploads/2018/08/232321075820.jpg', 'cms', '/uploads/2018/08/232321075820.jpg', '', '', 'image/jpeg', 'jpg', 0, 'dfa806060302b75a0eeae7b58c892608', 'dfa806060302b75a0eeae7b58c892608', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(241, 1, '/uploads/2018/08/232321441097.jpg', 'cms', '/uploads/2018/08/232321441097.jpg', '', '', 'image/jpeg', 'jpg', 0, '2f528f07c436350db6cc430022522f48', '2f528f07c436350db6cc430022522f48', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(242, 1, '/uploads/2018/08/232322025784.jpg', 'cms', '/uploads/2018/08/232322025784.jpg', '', '', 'image/jpeg', 'jpg', 0, '42015301648c41afeb5d786b4d878c2c', '42015301648c41afeb5d786b4d878c2c', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(243, 1, '/uploads/2018/08/232322413320.jpg', 'cms', '/uploads/2018/08/232322413320.jpg', '', '', 'image/jpeg', 'jpg', 0, 'a3a7fb34c150b2d11af948947c74f87e', 'a3a7fb34c150b2d11af948947c74f87e', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(244, 1, '/uploads/2018/08/232323047839.jpg', 'cms', '/uploads/2018/08/232323047839.jpg', '', '', 'image/jpeg', 'jpg', 0, 'a4ebb4f0383b60e38569d129f8093e6f', 'a4ebb4f0383b60e38569d129f8093e6f', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(245, 1, '/uploads/2018/08/232323348598.jpg', 'cms', '/uploads/2018/08/232323348598.jpg', '', '', 'image/jpeg', 'jpg', 0, '4d7d62a20ed81c82327fc457fc12a569', '4d7d62a20ed81c82327fc457fc12a569', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(246, 1, '/uploads/2018/08/232324008361.jpg', 'cms', '/uploads/2018/08/232324008361.jpg', '', '', 'image/jpeg', 'jpg', 0, '110e7d362407cf9f93cfa25603345fa3', '110e7d362407cf9f93cfa25603345fa3', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(247, 1, '/uploads/2018/08/232324198375.jpg', 'cms', '/uploads/2018/08/232324198375.jpg', '', '', 'image/jpeg', 'jpg', 0, 'dade4dad6f8967892d8a77d582c3efa4', 'dade4dad6f8967892d8a77d582c3efa4', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(248, 1, '/uploads/2018/08/232333183917.jpg', 'cms', '/uploads/2018/08/232333183917.jpg', '', '', 'image/jpeg', 'jpg', 0, 'e45443a9fa6acb832629ef8946137d09', 'e45443a9fa6acb832629ef8946137d09', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(249, 1, '/uploads/2018/08/232334035754.jpg', 'cms', '/uploads/2018/08/232334035754.jpg', '', '', 'image/jpeg', 'jpg', 0, 'c02fee072c753be8233433e097ae37f7', 'c02fee072c753be8233433e097ae37f7', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(250, 1, '/uploads/2018/08/232337075182.jpg', 'cms', '/uploads/2018/08/232337075182.jpg', '', '', 'image/jpeg', 'jpg', 0, '62d911b20dea0f2cce6d0c3fd247e82d', '62d911b20dea0f2cce6d0c3fd247e82d', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(251, 1, '/uploads/2018/08/232340109653.jpg', 'cms', '/uploads/2018/08/232340109653.jpg', '', '', 'image/jpeg', 'jpg', 0, '8761b62d3fc968a96dde82f21f986f9c', '8761b62d3fc968a96dde82f21f986f9c', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(252, 1, '/uploads/2018/08/232340505871.jpg', 'cms', '/uploads/2018/08/232340505871.jpg', '', '', 'image/jpeg', 'jpg', 0, '364374246a311bd3194d42bee69e0a14', '364374246a311bd3194d42bee69e0a14', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(253, 1, '/uploads/2018/08/232341169450.jpg', 'cms', '/uploads/2018/08/232341169450.jpg', '', '', 'image/jpeg', 'jpg', 0, '840c0ba3abe0ac75e7f4aaf8f4e56628', '840c0ba3abe0ac75e7f4aaf8f4e56628', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(254, 1, '/uploads/2018/08/232344333510.jpg', 'cms', '/uploads/2018/08/232344333510.jpg', '', '', 'image/jpeg', 'jpg', 0, '834af24b7c6075b5026a6cf7ccf49825', '834af24b7c6075b5026a6cf7ccf49825', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(255, 1, '/uploads/2019/12/111550133948.jpg', 'cms', '/uploads/2019/12/111550133948.jpg', '', '', 'image/jpeg', 'jpg', 0, 'ca33842a629266225d60b33e960dc8ab', 'ca33842a629266225d60b33e960dc8ab', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(256, 1, '/uploads/2018/08/240013257242.jpg', 'cms', '/uploads/2018/08/240013257242.jpg', '', '', 'image/jpeg', 'jpg', 0, '042b3158fdd06f1b165ecc22c891d8b4', '042b3158fdd06f1b165ecc22c891d8b4', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(257, 1, '/uploads/2018/08/240014229549.jpg', 'cms', '/uploads/2018/08/240014229549.jpg', '', '', 'image/jpeg', 'jpg', 0, 'c0006f922f1be07c4eeabca83108dcca', 'c0006f922f1be07c4eeabca83108dcca', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(258, 1, '/uploads/2018/08/240015038211.jpg', 'cms', '/uploads/2018/08/240015038211.jpg', '', '', 'image/jpeg', 'jpg', 0, 'aefd134de2d6c6436628fcd8ac07f6cc', 'aefd134de2d6c6436628fcd8ac07f6cc', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(259, 1, '/uploads/2018/08/240015207119.jpg', 'cms', '/uploads/2018/08/240015207119.jpg', '', '', 'image/jpeg', 'jpg', 0, 'a7f20e3574a13e520d91dc1d79d01bd8', 'a7f20e3574a13e520d91dc1d79d01bd8', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(260, 1, '/uploads/2018/08/240015525721.jpg', 'cms', '/uploads/2018/08/240015525721.jpg', '', '', 'image/jpeg', 'jpg', 0, '7bde13ac17735a493c8165929cff6109', '7bde13ac17735a493c8165929cff6109', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(261, 1, '/uploads/2018/08/240017355321.jpg', 'cms', '/uploads/2018/08/240017355321.jpg', '', '', 'image/jpeg', 'jpg', 0, '1ad43ea41abb0f09bc8f06de20cf7e2c', '1ad43ea41abb0f09bc8f06de20cf7e2c', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(262, 1, '/uploads/2018/08/240017506748.jpg', 'cms', '/uploads/2018/08/240017506748.jpg', '', '', 'image/jpeg', 'jpg', 0, '1f09b5399bf63c60b36b09249fa95cb2', '1f09b5399bf63c60b36b09249fa95cb2', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(263, 1, '/uploads/2018/08/240018303081.jpg', 'cms', '/uploads/2018/08/240018303081.jpg', '', '', 'image/jpeg', 'jpg', 0, '2e636ee47b19db64c404693df410e87f', '2e636ee47b19db64c404693df410e87f', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(264, 1, '/uploads/2018/08/240019003487.jpg', 'cms', '/uploads/2018/08/240019003487.jpg', '', '', 'image/jpeg', 'jpg', 0, '72dde4c8c52c48e3b2bc3e28c26b88d8', '72dde4c8c52c48e3b2bc3e28c26b88d8', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(265, 1, '/uploads/2018/08/240019151490.jpg', 'cms', '/uploads/2018/08/240019151490.jpg', '', '', 'image/jpeg', 'jpg', 0, '4d1090334b9ad263e288ab932df384aa', '4d1090334b9ad263e288ab932df384aa', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(266, 1, '/uploads/2018/08/240022574151.jpg', 'cms', '/uploads/2018/08/240022574151.jpg', '', '', 'image/jpeg', 'jpg', 0, '4f954099b0439c931edac5ff568e669f', '4f954099b0439c931edac5ff568e669f', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(267, 1, '/uploads/2018/08/240023304533.jpg', 'cms', '/uploads/2018/08/240023304533.jpg', '', '', 'image/jpeg', 'jpg', 0, 'f209222262f7bb1713650e131ebd9a6e', 'f209222262f7bb1713650e131ebd9a6e', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(268, 1, '/uploads/2018/08/240023471301.jpg', 'cms', '/uploads/2018/08/240023471301.jpg', '', '', 'image/jpeg', 'jpg', 0, 'e5afcff5647b58da494695af27b54d20', 'e5afcff5647b58da494695af27b54d20', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(269, 1, '/uploads/2018/08/240024236860.jpg', 'cms', '/uploads/2018/08/240024236860.jpg', '', '', 'image/jpeg', 'jpg', 0, 'fb1c4496ec476c21d0fce8a74d6dec48', 'fb1c4496ec476c21d0fce8a74d6dec48', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(270, 1, '/uploads/2018/08/240024458575.jpg', 'cms', '/uploads/2018/08/240024458575.jpg', '', '', 'image/jpeg', 'jpg', 0, '11dc4b9396610d09ea208898318597ad', '11dc4b9396610d09ea208898318597ad', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(271, 1, '/uploads/2018/08/240025163019.jpg', 'cms', '/uploads/2018/08/240025163019.jpg', '', '', 'image/jpeg', 'jpg', 0, '2a02f954942624c5adbee679aa55ae2d', '2a02f954942624c5adbee679aa55ae2d', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(272, 1, '/uploads/2018/08/240026376081.jpg', 'cms', '/uploads/2018/08/240026376081.jpg', '', '', 'image/jpeg', 'jpg', 0, 'c62d129205ea0ba0fa579428fc39d4ab', 'c62d129205ea0ba0fa579428fc39d4ab', 'local', 0, 1598164885, 1598164885, 100, 1, 0, 0),
(273, 1, '/uploads/2018/10/111632452973.jpg', 'cms', '/uploads/2018/10/111632452973.jpg', '', '', 'image/jpeg', 'jpg', 0, '3493b4563de58dd74a16657170ae0bf5', '3493b4563de58dd74a16657170ae0bf5', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(274, 1, '/uploads/2018/10/111652205980.jpg', 'cms', '/uploads/2018/10/111652205980.jpg', '', '', 'image/jpeg', 'jpg', 0, 'd51f886ecb3b4df0c7d508ce2143c4f9', 'd51f886ecb3b4df0c7d508ce2143c4f9', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(275, 1, '/uploads/2018/10/111702219392.jpg', 'cms', '/uploads/2018/10/111702219392.jpg', '', '', 'image/jpeg', 'jpg', 0, '8b5ed0e4000acca20f2fe96c28e6b5d2', '8b5ed0e4000acca20f2fe96c28e6b5d2', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(276, 1, '/uploads/2018/10/111711337120.jpg', 'cms', '/uploads/2018/10/111711337120.jpg', '', '', 'image/jpeg', 'jpg', 0, '7579ab763b7096f97bcf613cb328d4e0', '7579ab763b7096f97bcf613cb328d4e0', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(277, 1, '/uploads/2018/10/111718352069.jpg', 'cms', '/uploads/2018/10/111718352069.jpg', '', '', 'image/jpeg', 'jpg', 0, '17ac1656987c491299b20d584f871d1b', '17ac1656987c491299b20d584f871d1b', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(278, 1, '/uploads/2019/06/111046329177.jpg', 'cms', '/uploads/2019/06/111046329177.jpg', '', '', 'image/jpeg', 'jpg', 0, 'd62b1609492a2b55939cb16f1e34de0e', 'd62b1609492a2b55939cb16f1e34de0e', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(279, 1, '/uploads/2019/05/101005129286.jpg', 'cms', '/uploads/2019/05/101005129286.jpg', '', '', 'image/jpeg', 'jpg', 0, 'dd6df7ab204da193b45d0d3d065a5cea', 'dd6df7ab204da193b45d0d3d065a5cea', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(280, 1, '/uploads/2019/07/301116219455.jpg', 'cms', '/uploads/2019/07/301116219455.jpg', '', '', 'image/jpeg', 'jpg', 0, '3e3dd8d7085a9006a08d6bc7234b6c44', '3e3dd8d7085a9006a08d6bc7234b6c44', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(281, 1, '/uploads/2019/09/241130416963.jpg', 'cms', '/uploads/2019/09/241130416963.jpg', '', '', 'image/jpeg', 'jpg', 0, '255bc7ad53fe633a952c6a443f62798a', '255bc7ad53fe633a952c6a443f62798a', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(282, 1, '/uploads/2019/09/301052362922.jpg', 'cms', '/uploads/2019/09/301052362922.jpg', '', '', 'image/jpeg', 'jpg', 0, 'e63498359127336514bf656f883747ff', 'e63498359127336514bf656f883747ff', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(283, 1, '/uploads/2019/10/301607517134.jpg', 'cms', '/uploads/2019/10/301607517134.jpg', '', '', 'image/jpeg', 'jpg', 0, '09d4f1939e95ccf3dda7237c65aca6fb', '09d4f1939e95ccf3dda7237c65aca6fb', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(284, 1, '/uploads/2019/10/301632462001.jpg', 'cms', '/uploads/2019/10/301632462001.jpg', '', '', 'image/jpeg', 'jpg', 0, 'c33cd636f52dfa982bbfb6e11240b753', 'c33cd636f52dfa982bbfb6e11240b753', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(285, 1, '/uploads/2019/11/141548187809.jpg', 'cms', '/uploads/2019/11/141548187809.jpg', '', '', 'image/jpeg', 'jpg', 0, 'a3fbb407b5771acfd3a55b395caf1540', 'a3fbb407b5771acfd3a55b395caf1540', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(286, 1, '/uploads/2019/11/141555146959.jpg', 'cms', '/uploads/2019/11/141555146959.jpg', '', '', 'image/jpeg', 'jpg', 0, '838f110e4c1fd7e7530b0dcf6a50b2f8', '838f110e4c1fd7e7530b0dcf6a50b2f8', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(287, 1, '/uploads/2019/11/251500122915.jpg', 'cms', '/uploads/2019/11/251500122915.jpg', '', '', 'image/jpeg', 'jpg', 0, '844de7912c59d33e29994537869ffe0e', '844de7912c59d33e29994537869ffe0e', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(288, 1, '/uploads/2019/11/271557565699.jpg', 'cms', '/uploads/2019/11/271557565699.jpg', '', '', 'image/jpeg', 'jpg', 0, '410310e21a8ba54fecb3314f7818b973', '410310e21a8ba54fecb3314f7818b973', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(289, 1, '/uploads/2019/12/101201061058.jpg', 'cms', '/uploads/2019/12/101201061058.jpg', '', '', 'image/jpeg', 'jpg', 0, '7557fcff6ceff03cff98d5f67d9dfa82', '7557fcff6ceff03cff98d5f67d9dfa82', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(290, 1, '/uploads/2019/12/111650393491.jpg', 'cms', '/uploads/2019/12/111650393491.jpg', '', '', 'image/jpeg', 'jpg', 0, '950376e087d465d824751b9345644210', '950376e087d465d824751b9345644210', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(291, 1, '/uploads/2019/12/111652561584.jpg', 'cms', '/uploads/2019/12/111652561584.jpg', '', '', 'image/jpeg', 'jpg', 0, '5610a6cc54e227c7e8d5f301f63394f5', '5610a6cc54e227c7e8d5f301f63394f5', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(292, 1, '/uploads/2019/12/111657493477.jpg', 'cms', '/uploads/2019/12/111657493477.jpg', '', '', 'image/jpeg', 'jpg', 0, 'f2fc98a227c43869432b45503f80e8c9', 'f2fc98a227c43869432b45503f80e8c9', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(293, 1, '/uploads/2019/12/121608452446.jpg', 'cms', '/uploads/2019/12/121608452446.jpg', '', '', 'image/jpeg', 'jpg', 0, 'b142650c393860416d4b5a0599d52a5f', 'b142650c393860416d4b5a0599d52a5f', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(294, 1, '/uploads/2019/12/121616494263.jpg', 'cms', '/uploads/2019/12/121616494263.jpg', '', '', 'image/jpeg', 'jpg', 0, '6faeb542444442979833070c573499fa', '6faeb542444442979833070c573499fa', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(295, 1, '/uploads/2019/12/121621485558.jpg', 'cms', '/uploads/2019/12/121621485558.jpg', '', '', 'image/jpeg', 'jpg', 0, 'a86b43ce631f5e861f6489d060cf0541', 'a86b43ce631f5e861f6489d060cf0541', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(296, 1, '/uploads/2019/12/121658462015.jpg', 'cms', '/uploads/2019/12/121658462015.jpg', '', '', 'image/jpeg', 'jpg', 0, '3f21685056df8e7dacab59e4da4d0488', '3f21685056df8e7dacab59e4da4d0488', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(297, 1, '/uploads/2019/12/121659495449.jpg', 'cms', '/uploads/2019/12/121659495449.jpg', '', '', 'image/jpeg', 'jpg', 0, 'e20956e9846c98d4232dc6412bf3c7a3', 'e20956e9846c98d4232dc6412bf3c7a3', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(298, 1, '/uploads/2019/12/131011503264.jpg', 'cms', '/uploads/2019/12/131011503264.jpg', '', '', 'image/jpeg', 'jpg', 0, '9e43fc3ba4ba835952a1b99380d664b8', '9e43fc3ba4ba835952a1b99380d664b8', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(299, 1, '/uploads/2019/12/161630214633.jpg', 'cms', '/uploads/2019/12/161630214633.jpg', '', '', 'image/jpeg', 'jpg', 0, 'c48997a6efe6bd127ed790d4e5ae6441', 'c48997a6efe6bd127ed790d4e5ae6441', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(300, 1, '/uploads/2020/01/031706486842.jpg', 'cms', '/uploads/2020/01/031706486842.jpg', '', '', 'image/jpeg', 'jpg', 0, 'a4cdb3c113b20b4becde133f2f314571', 'a4cdb3c113b20b4becde133f2f314571', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(301, 1, '/uploads/2020/01/102006306694.jpg', 'cms', '/uploads/2020/01/102006306694.jpg', '', '', 'image/jpeg', 'jpg', 0, '3b6bc3cb13d230e4d11c430b15a3b862', '3b6bc3cb13d230e4d11c430b15a3b862', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(302, 1, '/uploads/2020/01/161628154288.jpg', 'cms', '/uploads/2020/01/161628154288.jpg', '', '', 'image/jpeg', 'jpg', 0, '1b5ae5683fb1ed8039dbeb50a0abd684', '1b5ae5683fb1ed8039dbeb50a0abd684', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(303, 1, '/uploads/2020/01/171449225596.jpg', 'cms', '/uploads/2020/01/171449225596.jpg', '', '', 'image/jpeg', 'jpg', 0, 'dce3e34c0ea38cbb3e906fdefd1000e1', 'dce3e34c0ea38cbb3e906fdefd1000e1', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(304, 1, '/uploads/2020/01/171512376939.jpg', 'cms', '/uploads/2020/01/171512376939.jpg', '', '', 'image/jpeg', 'jpg', 0, '286dabf1ed35f530016df9e9c9e48ae1', '286dabf1ed35f530016df9e9c9e48ae1', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(305, 1, '/uploads/2020/01/171518412065.jpg', 'cms', '/uploads/2020/01/171518412065.jpg', '', '', 'image/jpeg', 'jpg', 0, 'b95b54e999322b80f61109126233afcc', 'b95b54e999322b80f61109126233afcc', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(306, 1, '/uploads/2020/01/231503485546.jpg', 'cms', '/uploads/2020/01/231503485546.jpg', '', '', 'image/jpeg', 'jpg', 0, '4d261f7b084c7b463bc021772ec9df5d', '4d261f7b084c7b463bc021772ec9df5d', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(307, 1, '/uploads/2020/01/231516171332.jpg', 'cms', '/uploads/2020/01/231516171332.jpg', '', '', 'image/jpeg', 'jpg', 0, 'b526dcc4b5a4b0ac210025671ab0931f', 'b526dcc4b5a4b0ac210025671ab0931f', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(308, 1, '/uploads/2020/01/231530365832.jpg', 'cms', '/uploads/2020/01/231530365832.jpg', '', '', 'image/jpeg', 'jpg', 0, 'f3e8ba8901533aa60cbf38f79d71134b', 'f3e8ba8901533aa60cbf38f79d71134b', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(309, 1, '/uploads/2020/01/231538599184.jpg', 'cms', '/uploads/2020/01/231538599184.jpg', '', '', 'image/jpeg', 'jpg', 0, '30d63eda88ef5a1644b3151f4632391b', '30d63eda88ef5a1644b3151f4632391b', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(310, 1, '/uploads/2020/02/171943294923.jpg', 'cms', '/uploads/2020/02/171943294923.jpg', '', '', 'image/jpeg', 'jpg', 0, '98a1178ff890d473b85790d3b9cf3267', '98a1178ff890d473b85790d3b9cf3267', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(311, 1, '/uploads/2020/03/031659405018.jpg', 'cms', '/uploads/2020/03/031659405018.jpg', '', '', 'image/jpeg', 'jpg', 0, 'a9d3547a2323d9b98f9282c643a78aa5', 'a9d3547a2323d9b98f9282c643a78aa5', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(312, 1, '/uploads/2020/03/041058522813.jpg', 'cms', '/uploads/2020/03/041058522813.jpg', '', '', 'image/jpeg', 'jpg', 0, '3f87c3045fb8dee83d43815ce4384b1b', '3f87c3045fb8dee83d43815ce4384b1b', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(313, 1, '/uploads/2020/03/041107311095.jpg', 'cms', '/uploads/2020/03/041107311095.jpg', '', '', 'image/jpeg', 'jpg', 0, 'c1e1459b1ecf0e54000003131cdfa2eb', 'c1e1459b1ecf0e54000003131cdfa2eb', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(314, 1, '/uploads/2020/03/041124236134.jpg', 'cms', '/uploads/2020/03/041124236134.jpg', '', '', 'image/jpeg', 'jpg', 0, '21c9e82fa6f6b9b60b5727c3f081320f', '21c9e82fa6f6b9b60b5727c3f081320f', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(315, 1, '/uploads/2020/03/041132431075.jpg', 'cms', '/uploads/2020/03/041132431075.jpg', '', '', 'image/jpeg', 'jpg', 0, 'fa7bc995710965bbbeb34b28b059af4d', 'fa7bc995710965bbbeb34b28b059af4d', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(316, 1, '/uploads/2020/03/121659568126.jpg', 'cms', '/uploads/2020/03/121659568126.jpg', '', '', 'image/jpeg', 'jpg', 0, '9a9336088faa4377341b62f3d787ee84', '9a9336088faa4377341b62f3d787ee84', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(317, 1, '/uploads/2020/03/121706176800.jpg', 'cms', '/uploads/2020/03/121706176800.jpg', '', '', 'image/jpeg', 'jpg', 0, '55a1e697b78088d8754dc64f0b61e598', '55a1e697b78088d8754dc64f0b61e598', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(318, 1, '/uploads/2020/03/121712076566.jpg', 'cms', '/uploads/2020/03/121712076566.jpg', '', '', 'image/jpeg', 'jpg', 0, '6c3e9c194f082d7e431ff6fcf7384a27', '6c3e9c194f082d7e431ff6fcf7384a27', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(319, 1, '/uploads/2020/04/101712039170.jpg', 'cms', '/uploads/2020/04/101712039170.jpg', '', '', 'image/jpeg', 'jpg', 0, 'd7ba53ed399d7ef2ace494046cc1820a', 'd7ba53ed399d7ef2ace494046cc1820a', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(320, 1, '/uploads/2020/04/101718533024.jpg', 'cms', '/uploads/2020/04/101718533024.jpg', '', '', 'image/jpeg', 'jpg', 0, '1cda3b3a19d0ae48841360f2b7b5f3d1', '1cda3b3a19d0ae48841360f2b7b5f3d1', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(321, 1, '/uploads/2020/04/101729191861.jpg', 'cms', '/uploads/2020/04/101729191861.jpg', '', '', 'image/jpeg', 'jpg', 0, '4b1af6f014cc67ea908b083275c83ffc', '4b1af6f014cc67ea908b083275c83ffc', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(322, 1, '/uploads/2020/04/231120074034.jpg', 'cms', '/uploads/2020/04/231120074034.jpg', '', '', 'image/jpeg', 'jpg', 0, 'bef97b4485d63b82293e802568781024', 'bef97b4485d63b82293e802568781024', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(323, 1, '/uploads/2020/04/231657587977.jpg', 'cms', '/uploads/2020/04/231657587977.jpg', '', '', 'image/jpeg', 'jpg', 0, '4d9aa32ae9ee2e0933672760a3fb695c', '4d9aa32ae9ee2e0933672760a3fb695c', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(324, 1, '/uploads/2020/05/091608327475.jpg', 'cms', '/uploads/2020/05/091608327475.jpg', '', '', 'image/jpeg', 'jpg', 0, '785df7900d85d48c73587f1b6d772099', '785df7900d85d48c73587f1b6d772099', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(325, 1, '/uploads/2020/05/091619143497.jpg', 'cms', '/uploads/2020/05/091619143497.jpg', '', '', 'image/jpeg', 'jpg', 0, '6d680d24a52952b42774984850922a48', '6d680d24a52952b42774984850922a48', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(326, 1, '/uploads/2020/05/091641507967.jpg', 'cms', '/uploads/2020/05/091641507967.jpg', '', '', 'image/jpeg', 'jpg', 0, '71566e1febd1b522c21b54c3a53384ff', '71566e1febd1b522c21b54c3a53384ff', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(327, 1, '/uploads/2020/05/091721304024.jpg', 'cms', '/uploads/2020/05/091721304024.jpg', '', '', 'image/jpeg', 'jpg', 0, 'cd33dad35ee8304be6170bf9dc0fdcfa', 'cd33dad35ee8304be6170bf9dc0fdcfa', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(328, 1, '/uploads/2020/05/131641026257.jpg', 'cms', '/uploads/2020/05/131641026257.jpg', '', '', 'image/jpeg', 'jpg', 0, 'd80d89e67993fdeb33b06d572b43255a', 'd80d89e67993fdeb33b06d572b43255a', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(329, 1, '/uploads/2020/05/291641385211.jpg', 'cms', '/uploads/2020/05/291641385211.jpg', '', '', 'image/jpeg', 'jpg', 0, 'ff692324bbe9e349c3d7b800ff9827a7', 'ff692324bbe9e349c3d7b800ff9827a7', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(330, 1, '/uploads/2020/05/291649366512.jpg', 'cms', '/uploads/2020/05/291649366512.jpg', '', '', 'image/jpeg', 'jpg', 0, '8b0f392fcd10acfc89a4d7ee6ebd58dd', '8b0f392fcd10acfc89a4d7ee6ebd58dd', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(331, 1, '/uploads/2020/05/291658579953.jpg', 'cms', '/uploads/2020/05/291658579953.jpg', '', '', 'image/jpeg', 'jpg', 0, '0b291d413c6bf83531a697110cbc0a50', '0b291d413c6bf83531a697110cbc0a50', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(332, 1, '/uploads/2020/05/291711374177.jpg', 'cms', '/uploads/2020/05/291711374177.jpg', '', '', 'image/jpeg', 'jpg', 0, 'b24dfdf5cf130878fcde75ebf5547cd5', 'b24dfdf5cf130878fcde75ebf5547cd5', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(333, 1, '/uploads/2020/05/291721183548.jpg', 'cms', '/uploads/2020/05/291721183548.jpg', '', '', 'image/jpeg', 'jpg', 0, '94bad2937610e80fb15fc469ba7a3c36', '94bad2937610e80fb15fc469ba7a3c36', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(334, 1, '/uploads/2020/06/101601472773.jpg', 'cms', '/uploads/2020/06/101601472773.jpg', '', '', 'image/jpeg', 'jpg', 0, '7a37dd5762d8444794935526a66618fd', '7a37dd5762d8444794935526a66618fd', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(335, 1, '/uploads/2020/06/101618432673.jpg', 'cms', '/uploads/2020/06/101618432673.jpg', '', '', 'image/jpeg', 'jpg', 0, '4856025187a3f95c92fa26114904698a', '4856025187a3f95c92fa26114904698a', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(336, 1, '/uploads/2020/06/111142249298.jpg', 'cms', '/uploads/2020/06/111142249298.jpg', '', '', 'image/jpeg', 'jpg', 0, '1a3d1f085656158897978f7eb2a49aa7', '1a3d1f085656158897978f7eb2a49aa7', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(337, 1, '/uploads/2020/06/161737001562.jpg', 'cms', '/uploads/2020/06/161737001562.jpg', '', '', 'image/jpeg', 'jpg', 0, 'c502562ca2e852b9ca6b28edd723cec1', 'c502562ca2e852b9ca6b28edd723cec1', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(338, 1, '/uploads/2020/06/171050466916.jpg', 'cms', '/uploads/2020/06/171050466916.jpg', '', '', 'image/jpeg', 'jpg', 0, '0f89f54f338a8e687d36cc1418fc7196', '0f89f54f338a8e687d36cc1418fc7196', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(339, 1, '/uploads/2020/06/191002001967.jpg', 'cms', '/uploads/2020/06/191002001967.jpg', '', '', 'image/jpeg', 'jpg', 0, 'ad9dce74cbbc7581a55ef5c6326d131d', 'ad9dce74cbbc7581a55ef5c6326d131d', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(340, 1, '/uploads/2020/07/071659273687.jpg', 'cms', '/uploads/2020/07/071659273687.jpg', '', '', 'image/jpeg', 'jpg', 0, '817de300b663faf95ba6f846b1d1220b', '817de300b663faf95ba6f846b1d1220b', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(341, 1, '/uploads/2020/07/071715098125.jpg', 'cms', '/uploads/2020/07/071715098125.jpg', '', '', 'image/jpeg', 'jpg', 0, '29ce84d427c9c1205aeb92e5c53e7a82', '29ce84d427c9c1205aeb92e5c53e7a82', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(342, 1, '/uploads/2020/07/071751302223.jpg', 'cms', '/uploads/2020/07/071751302223.jpg', '', '', 'image/jpeg', 'jpg', 0, '0cc8129488249eb163a623c877215fcd', '0cc8129488249eb163a623c877215fcd', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(343, 1, '/uploads/2020/07/171727448504.jpg', 'cms', '/uploads/2020/07/171727448504.jpg', '', '', 'image/jpeg', 'jpg', 0, '48e93a066954c1dd1bdaadb1632326c5', '48e93a066954c1dd1bdaadb1632326c5', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(344, 1, '/uploads/2020/07/171733494390.jpg', 'cms', '/uploads/2020/07/171733494390.jpg', '', '', 'image/jpeg', 'jpg', 0, 'dad9995595734778a45b7c16438dea5d', 'dad9995595734778a45b7c16438dea5d', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(345, 1, '/uploads/2020/07/171739263313.jpg', 'cms', '/uploads/2020/07/171739263313.jpg', '', '', 'image/jpeg', 'jpg', 0, 'f875d9c2dff19be5809b96a64e58af41', 'f875d9c2dff19be5809b96a64e58af41', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(346, 1, '/uploads/2020/07/221117359594.jpg', 'cms', '/uploads/2020/07/221117359594.jpg', '', '', 'image/jpeg', 'jpg', 0, 'eb2075a09b7711e481f7d9b8fc12159e', 'eb2075a09b7711e481f7d9b8fc12159e', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(347, 1, '/uploads/2020/07/221601539097.jpg', 'cms', '/uploads/2020/07/221601539097.jpg', '', '', 'image/jpeg', 'jpg', 0, '56018006ba72e25a7ff9421433514764', '56018006ba72e25a7ff9421433514764', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(348, 1, '/uploads/2020/07/271117326863.jpg', 'cms', '/uploads/2020/07/271117326863.jpg', '', '', 'image/jpeg', 'jpg', 0, '89f79bc8e56727990b9ac58b505a1287', '89f79bc8e56727990b9ac58b505a1287', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(349, 1, '/uploads/2020/07/271553313786.jpg', 'cms', '/uploads/2020/07/271553313786.jpg', '', '', 'image/jpeg', 'jpg', 0, '90d2444d40852b5d1e801402741bcc38', '90d2444d40852b5d1e801402741bcc38', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(350, 1, '/uploads/2020/08/171622521157.jpg', 'cms', '/uploads/2020/08/171622521157.jpg', '', '', 'image/jpeg', 'jpg', 0, '020cb2e3ccef68302644dcc9eb748511', '020cb2e3ccef68302644dcc9eb748511', 'local', 0, 1598164886, 1598164886, 100, 1, 0, 0),
(351, 1, 'a_banner_02.png', 'cms', 'uploads/images/20200906/4e7cf4434154649bd27dc805abc3089e.png', '', '', 'image/png', 'png', 297427, 'f5e72a654677ff79a168f3d1fa44c902', 'c640a1d8e42cc3881d2ec4ef6169bb7ec53d369e', 'local', 0, 1599397401, 1599397401, 100, 1, 1920, 353),
(352, 1, 'banner_13.jpg', 'cms', 'uploads/images/20200906/a6a31d443a9ca0b57f406f1243d0092d.jpg', '', '', 'image/jpeg', 'jpg', 431210, 'cff23a8226f8a32b90e269a40ebe4179', 'ed22d5920149efb6e160f94f2366812c781bfda7', 'local', 0, 1599397429, 1599397429, 100, 1, 1920, 490),
(353, 1, 'bm.jpg', 'cms', 'uploads/images/20200906/8f69e04003c71015bbab28df436d26af.jpg', '', '', 'image/jpeg', 'jpg', 24707, 'f318a222e2940508dd4d1fd7f7c5aaa0', '5aac3f0598e0c645ab6b5d7b7ccc7fec57345986', 'local', 0, 1599400212, 1599400212, 100, 1, 552, 414),
(354, 1, 'index_48.jpg', 'cms', 'uploads/images/20200906/6e83048ee9f484055386878b15acaf18.jpg', '', '', 'image/jpeg', 'jpg', 3270, '21f2db91bd8481e5ac8fcda23cb9a794', '989f69294d0be9971a244a5504c20f6fe1acb608', 'local', 0, 1599400564, 1599400564, 100, 1, 62, 74),
(355, 1, 'bm5.jpg', 'cms', 'uploads/images/20200906/9a2487a6ee9c061fd054fb23be0632e7.jpg', '', '', 'image/jpeg', 'jpg', 36328, '60c06a148fcd1f711c1e4ec5a9345f89', 'ab8c99ceb7a888c858a78d1f73a44509d3757c9b', 'local', 0, 1599400938, 1599400938, 100, 1, 567, 431),
(356, 1, 'bm3.jpg', 'cms', 'uploads/images/20200906/6ee8f8a4982b4f03cea4c1dcfac325db.jpg', '', '', 'image/jpeg', 'jpg', 12770, 'e0c8df0197b7e6f36e21b8163cc92b81', 'c4e4c47be024fd4427f7ae7c214d86fa54b600cd', 'local', 0, 1599400952, 1599400952, 100, 1, 488, 339),
(357, 1, 'huan3.jpg', 'cms', 'uploads/images/20200906/dcc9b2038891d91a078bc3593074d5f1.jpg', '', '', 'image/jpeg', 'jpg', 24842, '16c191d0ec6841dc827be6920e29d152', '9d8414a5d19724aa6bd5972dbd06369af861065b', 'local', 0, 1599400982, 1599400982, 100, 1, 235, 180),
(358, 1, 'Radiation_46b.jpg', 'cms', 'uploads/images/20200906/ae676611ced6a7531aef62573f0f8dfe.jpg', '', '', 'image/jpeg', 'jpg', 23356, '5c8996086858badc14b13d07e2bb807b', 'ed80b3961200ce695f17c7a1d18e6fae17afc09f', 'local', 0, 1599401003, 1599401003, 100, 1, 350, 235),
(359, 1, 'index_50.jpg', 'cms', 'uploads/images/20200906/9a60ddf56533fc94859faf9f9ed360d0.jpg', '', '', 'image/jpeg', 'jpg', 3102, '8a320d1905c9e8dacecd7ea0de1d56e8', 'dc997f0d81e277de8d77efab893b7471e49349eb', 'local', 0, 1599403276, 1599403276, 100, 1, 62, 74),
(360, 1, 'kf_tp_17.jpg', 'cms', 'uploads/images/20200907/a4c78137a3d28a5ea9f62de71ce1f283.jpg', '', '', 'image/jpeg', 'jpg', 16044, '5d45d247ab5575d3b8b9eea653b1e311', 'fc5130be5ebd1d9a190edcddce4929cc82ed8c80', 'local', 0, 1599444083, 1599444083, 100, 1, 131, 101),
(361, 1, 'i_tsnr_11.jpg', 'cms', 'uploads/images/20200907/86aa043b95fc7bcc321311408131d60e.jpg', '', '', 'image/jpeg', 'jpg', 96354, '91cb5f5930f30d3d549f9f08bb8b9b95', '199aed235d0de4c123bd79332dfa408617e79998', 'local', 0, 1599444185, 1599444185, 100, 1, 594, 261),
(362, 1, 'wKgB6lPWH86AZxuZAADZ1SU-QnU54.jpeg', 'cms', 'uploads/images/20201025/cc40050495fa402ef5912ac482b01b41.jpeg', '', '', 'image/jpeg', 'jpeg', 92177, 'dddd14f98bf042751554241d39a872d2', '116986c3e0b7b100d3243fbb8576721efc1cbba7', 'local', 0, 1603628167, 1603628167, 100, 1, 1020, 540),
(363, 1, '002.jpg', 'cms', 'uploads/images/20201025/716d70942010832c72b7a952ac2750b3.jpg', '', '', 'image/jpeg', 'jpg', 8988, 'c871012734e387cc2509676c9ae246e1', 'c5a04920110bde70ea206e500defe8315b144533', 'local', 0, 1603634483, 1603634483, 100, 1, 171, 171),
(364, 1, '96.png', 'cms', 'uploads/images/20201025/61d0813d84cc8ddba4c317304c7ced62.png', '', '', 'image/png', 'png', 9995, '344801da217ef07e996e830fc7be2c5b', 'ed457f5da0a49e7cf968937f0bc3f74734bbe5f9', 'local', 0, 1603634491, 1603634491, 100, 1, 96, 96),
(365, 1, 'wKgBm04USFH9AR_qAARuPONkFlU17.jpeg', 'cms', 'uploads/images/20201029/22096fac8a44d3b997dd9ee57c19013f.jpeg', '', '', 'image/jpeg', 'jpeg', 62190, '6e100d9f883108245ac605e53664013c', '8bdd1de7a621def8e5a936d2f13da1bf7794f6fa', 'local', 0, 1603951590, 1603951590, 100, 1, 690, 370),
(366, 1, '2dqc.png', 'admin', 'uploads/images/20201114/da22721a38857526b33d69b99c5772c2.png', '', '', 'image/png', 'png', 6655, '78f878c6df98762991f082c2b5935865', '10a9c8f9c41096ceb91bcaee0e41ba61b6c1ba8a', 'local', 0, 1605320120, 1605320120, 100, 1, 236, 236),
(367, 1, 'indeximg_pc.jpg', 'cms', 'uploads/images/20201114/a11537e75be186595472b1585f636c7f.jpg', '', '', 'image/jpeg', 'jpg', 328129, '6aec83c50d868ec6004588fd90eb2c69', 'd0d6aad61430b4379cb7600ab7291222f873da21', 'local', 0, 1605323390, 1605323390, 100, 1, 1920, 680),
(368, 1, 'indeximg_mb.jpg', 'cms', 'uploads/images/20201114/a469bf28a78ec9aa71757c85562d468d.jpg', '', '', 'image/jpeg', 'jpg', 213776, 'a61ec147f6bbd096d901ede72c7cbbc2', '9b35643dae30997d475f32f86127bb615e359c97', 'local', 0, 1605323393, 1605323393, 100, 1, 750, 1180),
(369, 1, 'teacherimg_pc.jpg', 'cms', 'uploads/images/20201114/f7fe176638eada48bf0a1d2f2a41d47d.jpg', '', '', 'image/jpeg', 'jpg', 433125, '51556de35935e058df3e3035cf5d288c', '9efcbe76544a25614fff83fd5fdee7104e50a02f', 'local', 0, 1605323410, 1605323410, 100, 1, 1920, 828),
(370, 1, 'tutorimg_mb.jpg', 'cms', 'uploads/images/20201114/24763c015eb7e10f852b978ff9c8d61a.jpg', '', '', 'image/jpeg', 'jpg', 259533, '419b94a94f2961cd0509f2fa13a81d5b', '778ae47445ca3ca47bb8f0facf249c8e348920aa', 'local', 0, 1605323426, 1605323426, 100, 1, 750, 1260),
(371, 1, 'tutorimg_pc.jpg', 'cms', 'uploads/images/20201114/36b76798a3b419a650dadb4674b7f58e.jpg', '', '', 'image/jpeg', 'jpg', 380094, '785c60aa9dc0e3e2e7a0e66b4a04f49e', 'dba155366f94a5ee1ebb52448db3dcd6f1555f66', 'local', 0, 1605323443, 1605323443, 100, 1, 1920, 828),
(372, 1, 'teacherimg_mb.jpg', 'cms', 'uploads/images/20201114/e08be9b8651e7b5563d20ff6c70b7ab1.jpg', '', '', 'image/jpeg', 'jpg', 252017, '9b05a30fd48710103ff2684851c12654', 'ae0e95e30ab89b6280d967aaba5445ad0d23d358', 'local', 0, 1605323458, 1605323458, 100, 1, 750, 1260),
(373, 1, 'movie.mp4', 'admin', 'uploads/files/20201114/bb2dbd6adea1ef3edeac9dcb98bd5fc8.mp4', '', '', 'video/mp4', 'mp4', 318465, '3cf571d4cf2a4c4b2df823a27852a7d5', 'c90b44a96ed080c1a6c8ce8888a40a5aaaa7e7ca', 'local', 0, 1605326118, 1605326118, 100, 1, 0, 0),
(374, 1, 'btpic1.jpg', 'admin', 'uploads/images/20201114/131c85abc0f6c5b79b70eb862f6f2fc0.jpg', '', '', 'image/jpeg', 'jpg', 272688, '7b9b50547612355036a1aebe77fcc85d', '1620ec9f9db77d7039c95609f1cf17b12a09d237', 'local', 0, 1605326282, 1605326282, 100, 1, 1920, 1133),
(375, 1, 'videopic.jpg', 'admin', 'uploads/images/20201114/185619da0a05423bf73f0c1507dabf71.jpg', '', '', 'image/jpeg', 'jpg', 38035, 'cd5169d0ad43f7b88731aacfea9d14c4', 'bd5030886486f56bb40384b6db633984de91001c', 'local', 0, 1605326288, 1605326288, 100, 1, 550, 300),
(376, 1, 'avantra.png', 'cms', 'uploads/images/20201114/528379b1dda26621f975491cb3cf10cd.png', '', '', 'image/png', 'png', 77298, 'f602d14afb2d4a1fb997c101f73e7881', '7812a2fc890d0bd8fd4a28f7ac4b1037d3dad225', 'local', 0, 1605327129, 1605327129, 100, 1, 200, 200),
(377, 1, 'flag.png', 'cms', 'uploads/images/20201114/d8dd5164a19cac39b9aa683e1a028423.png', '', '', 'image/png', 'png', 4002, 'ceefda4c7c23f81193efae9e1ebe9920', '0a86e93e9d4091e6d98df64bd1dedac3693a3224', 'local', 0, 1605327133, 1605327133, 100, 1, 64, 64),
(378, 1, 'student_logpic.png', 'cms', 'uploads/images/20201114/d441cdb17655f42fcf1e5b16dc1c0096.png', '', '', 'image/png', 'png', 405042, '480a19036b1da65b4712cb22f8b6735c', '4534017832175d1acb609e8026d7d8736556f92a', 'local', 0, 1605335867, 1605335867, 100, 1, 500, 600),
(379, 1, 'aboutimg_pc.jpg', 'cms', 'uploads/images/20201114/2203e05a5c528725726751095a7b2bf6.jpg', '', '', 'image/jpeg', 'jpg', 325332, '852c50812837e551e43bdf8155af7b09', '28fa857b5a75dbace43665b55140676fc3bfdbf4', 'local', 0, 1605340186, 1605340186, 100, 1, 1920, 479),
(380, 1, 'aboutimg_mb.jpg', 'cms', 'uploads/images/20201114/012a9f18c744cffe7ed0beadfea3de71.jpg', '', '', 'image/jpeg', 'jpg', 272778, '70ae137c7045a03ee65683626ae9dfeb', '1578237beafefe2b281319738e55f1b0a81ee7c8', 'local', 0, 1605340190, 1605340190, 100, 1, 750, 980);

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_config`
--

CREATE TABLE `dp_admin_config` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '标题',
  `group` varchar(32) NOT NULL DEFAULT '' COMMENT '配置分组',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '类型',
  `value` text NOT NULL COMMENT '配置值',
  `options` text NOT NULL COMMENT '配置项',
  `tips` varchar(256) NOT NULL DEFAULT '' COMMENT '配置提示',
  `ajax_url` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框ajax地址',
  `next_items` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框的下级下拉框名，多个以逗号隔开',
  `param` varchar(32) NOT NULL DEFAULT '' COMMENT '联动下拉框请求参数名',
  `format` varchar(32) NOT NULL DEFAULT '' COMMENT '格式，用于格式文本',
  `table` varchar(32) NOT NULL DEFAULT '' COMMENT '表名，只用于快速联动类型',
  `level` tinyint(2) UNSIGNED NOT NULL DEFAULT '2' COMMENT '联动级别，只用于快速联动类型',
  `key` varchar(32) NOT NULL DEFAULT '' COMMENT '键字段，只用于快速联动类型',
  `option` varchar(32) NOT NULL DEFAULT '' COMMENT '值字段，只用于快速联动类型',
  `pid` varchar(32) NOT NULL DEFAULT '' COMMENT '父级id字段，只用于快速联动类型',
  `ak` varchar(32) NOT NULL DEFAULT '' COMMENT '百度地图appkey',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态：0禁用，1启用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统配置表';

--
-- 转存表中的数据 `dp_admin_config`
--

INSERT INTO `dp_admin_config` (`id`, `name`, `title`, `group`, `type`, `value`, `options`, `tips`, `ajax_url`, `next_items`, `param`, `format`, `table`, `level`, `key`, `option`, `pid`, `ak`, `create_time`, `update_time`, `sort`, `status`) VALUES
(1, 'web_site_status', '站点开关', 'base', 'switch', '1', '', '站点关闭后将不能访问，后台可正常登录', '', '', '', '', '', 2, '', '', '', '', 1475240395, 1477403914, 1, 1),
(2, 'web_site_title', '站点标题', 'base', 'text', '英语学习', '', '调用方式：<code>config(\'web_site_title\')</code>', '', '', '', '', '', 2, '', '', '', '', 1475240646, 1477710341, 2, 1),
(3, 'web_site_slogan', '站点标语', 'base', 'text', '英语学习', '', '站点口号，调用方式：<code>config(\'web_site_slogan\')</code>', '', '', '', '', '', 2, '', '', '', '', 1475240994, 1477710357, 3, 1),
(4, 'web_site_logo', '站点LOGO', 'base', 'image', '', '', '', '', '', '', '', '', 2, '', '', '', '', 1475241067, 1475241067, 4, 0),
(5, 'web_site_description', '站点描述', 'base', 'textarea', '英语学习', '', '网站描述，有利于搜索引擎抓取相关信息', '', '', '', '', '', 2, '', '', '', '', 1475241186, 1475241186, 6, 1),
(6, 'web_site_keywords', '站点关键词', 'base', 'text', '英语学习', '', '网站搜索引擎关键字', '', '', '', '', '', 2, '', '', '', '', 1475241328, 1475241328, 7, 1),
(7, 'web_site_copyright', '版权信息', 'base', 'text', 'Copyright © 2015-2020 英语学习 All rights reserved.', '', '调用方式：<code>config(\'web_site_copyright\')</code>', '', '', '', '', '', 2, '', '', '', '', 1475241416, 1477710383, 8, 1),
(8, 'web_site_icp', '备案信息', 'base', 'textarea', '', '', '调用方式：<code>config(\'web_site_icp\')</code>', '', '', '', '', '', 2, '', '', '', '', 1475241441, 1477710441, 9, 1),
(40, 'english_level', '英语级别', 'para', 'array', '1:Beginner\r\n2:Intermidate\r\n3:Proficient', '', '', '', '', '', '', '', 0, '', '', '', '', 1600048551, 1602379204, 10000, 1),
(41, 'native_language', '母语', 'para', 'array', '1:China\r\n2:English\r\n3:Other', '', '', '', '', '', '', '', 0, '', '', '', '', 1600048598, 1602379198, 1000, 1),
(9, 'web_site_statistics', '站点统计', 'base', 'textarea', '', '', '网站统计代码，支持百度、Google、cnzz等，调用方式：<code>config(\'web_site_statistics\')</code>', '', '', '', '', '', 2, '', '', '', '', 1475241498, 1477710455, 10, 1),
(10, 'config_group', '配置分组', 'system', 'array', 'base:基本\r\npara:参数配置\r\nsystem:系统\r\nupload:上传\r\ndevelop:开发\r\ndatabase:数据库', '', '', '', '', '', '', '', 2, '', '', '', '', 1475241716, 1477649446, 100, 1),
(11, 'form_item_type', '配置类型', 'system', 'array', 'text:单行文本\r\ntextarea:多行文本\r\nstatic:静态文本\r\npassword:密码\r\ncheckbox:复选框\r\nradio:单选按钮\r\ndate:日期\r\ndatetime:日期+时间\r\nhidden:隐藏\r\nswitch:开关\r\narray:数组\r\nselect:下拉框\r\nlinkage:普通联动下拉框\r\nlinkages:快速联动下拉框\r\nimage:单张图片\r\nimages:多张图片\r\nfile:单个文件\r\nfiles:多个文件\r\nueditor:UEditor 编辑器\r\nwangeditor:wangEditor 编辑器\r\neditormd:markdown 编辑器\r\nckeditor:ckeditor 编辑器\r\nicon:字体图标\r\ntags:标签\r\nnumber:数字\r\nbmap:百度地图\r\ncolorpicker:取色器\r\njcrop:图片裁剪\r\nmasked:格式文本\r\nrange:范围\r\ntime:时间', '', '', '', '', '', '', '', 2, '', '', '', '', 1475241835, 1495853193, 100, 1),
(12, 'upload_file_size', '文件上传大小限制', 'upload', 'text', '0', '', '0为不限制大小，单位：kb', '', '', '', '', '', 2, '', '', '', '', 1475241897, 1477663520, 100, 1),
(13, 'upload_file_ext', '允许上传的文件后缀', 'upload', 'tags', 'doc,docx,xls,xlsx,ppt,pptx,pdf,wps,txt,rar,zip,gz,bz2,7z,mp4', '', '多个后缀用逗号隔开，不填写则不限制类型', '', '', '', '', '', 2, '', '', '', '', 1475241975, 1477649489, 100, 1),
(14, 'upload_image_size', '图片上传大小限制', 'upload', 'text', '0', '', '0为不限制大小，单位：kb', '', '', '', '', '', 2, '', '', '', '', 1475242015, 1477663529, 100, 1),
(15, 'upload_image_ext', '允许上传的图片后缀', 'upload', 'tags', 'gif,jpg,jpeg,bmp,png', '', '多个后缀用逗号隔开，不填写则不限制类型', '', '', '', '', '', 2, '', '', '', '', 1475242056, 1477649506, 100, 1),
(16, 'list_rows', '分页数量', 'system', 'number', '20', '', '每页的记录数', '', '', '', '', '', 2, '', '', '', '', 1475242066, 1476074507, 101, 1),
(17, 'system_color', '后台配色方案', 'system', 'radio', 'default', 'default:Default\r\namethyst:Amethyst\r\ncity:City\r\nflat:Flat\r\nmodern:Modern\r\nsmooth:Smooth', '', '', '', '', '', '', 2, '', '', '', '', 1475250066, 1477316689, 102, 1),
(18, 'develop_mode', '开发模式', 'develop', 'radio', '1', '0:关闭\r\n1:开启', '', '', '', '', '', '', 2, '', '', '', '', 1476864205, 1476864231, 100, 1),
(19, 'app_trace', '显示页面Trace', 'develop', 'radio', '0', '0:否\r\n1:是', '', '', '', '', '', '', 2, '', '', '', '', 1476866355, 1476866355, 100, 1),
(21, 'data_backup_path', '数据库备份根路径', 'database', 'text', '../data/', '', '路径必须以 / 结尾', '', '', '', '', '', 2, '', '', '', '', 1477017745, 1477018467, 100, 1),
(22, 'data_backup_part_size', '数据库备份卷大小', 'database', 'text', '20971520', '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', '', '', '', '', '', 2, '', '', '', '', 1477017886, 1477017886, 100, 1),
(23, 'data_backup_compress', '数据库备份文件是否启用压缩', 'database', 'radio', '1', '0:否\r\n1:是', '压缩备份文件需要PHP环境支持 <code>gzopen</code>, <code>gzwrite</code>函数', '', '', '', '', '', 2, '', '', '', '', 1477017978, 1477018172, 100, 1),
(24, 'data_backup_compress_level', '数据库备份文件压缩级别', 'database', 'radio', '9', '1:最低\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', '', '', '', '', '', 2, '', '', '', '', 1477018083, 1477018083, 100, 1),
(25, 'top_menu_max', '顶部导航模块数量', 'system', 'text', '10', '', '设置顶部导航默认显示的模块数量', '', '', '', '', '', 2, '', '', '', '', 1477579289, 1477579289, 103, 1),
(26, 'web_site_logo_text', '站点LOGO文字', 'base', 'image', '', '', '', '', '', '', '', '', 2, '', '', '', '', 1477620643, 1477620643, 5, 0),
(27, 'upload_image_thumb', '缩略图尺寸', 'upload', 'text', '', '', '不填写则不生成缩略图，如需生成 <code>300x300</code> 的缩略图，则填写 <code>300,300</code> ，请注意，逗号必须是英文逗号', '', '', '', '', '', 2, '', '', '', '', 1477644150, 1477649513, 100, 1),
(28, 'upload_image_thumb_type', '缩略图裁剪类型', 'upload', 'radio', '1', '1:等比例缩放\r\n2:缩放后填充\r\n3:居中裁剪\r\n4:左上角裁剪\r\n5:右下角裁剪\r\n6:固定尺寸缩放', '该项配置只有在启用生成缩略图时才生效', '', '', '', '', '', 2, '', '', '', '', 1477646271, 1477649521, 100, 1),
(29, 'upload_thumb_water', '添加水印', 'upload', 'switch', '0', '', '', '', '', '', '', '', 2, '', '', '', '', 1477649648, 1477649648, 100, 1),
(30, 'upload_thumb_water_pic', '水印图片', 'upload', 'image', '', '', '只有开启水印功能才生效', '', '', '', '', '', 2, '', '', '', '', 1477656390, 1477656390, 100, 1),
(31, 'upload_thumb_water_position', '水印位置', 'upload', 'radio', '9', '1:左上角\r\n2:上居中\r\n3:右上角\r\n4:左居中\r\n5:居中\r\n6:右居中\r\n7:左下角\r\n8:下居中\r\n9:右下角', '只有开启水印功能才生效', '', '', '', '', '', 2, '', '', '', '', 1477656528, 1477656528, 100, 1),
(32, 'upload_thumb_water_alpha', '水印透明度', 'upload', 'text', '50', '', '请输入0~100之间的数字，数字越小，透明度越高', '', '', '', '', '', 2, '', '', '', '', 1477656714, 1477661309, 100, 1),
(33, 'wipe_cache_type', '清除缓存类型', 'system', 'checkbox', 'TEMP_PATH', 'TEMP_PATH:应用缓存\r\nLOG_PATH:应用日志\r\nCACHE_PATH:项目模板缓存', '清除缓存时，要删除的缓存类型', '', '', '', '', '', 2, '', '', '', '', 1477727305, 1477727305, 100, 1),
(34, 'captcha_signin', '后台验证码开关', 'system', 'switch', '0', '', '后台登录时是否需要验证码', '', '', '', '', '', 2, '', '', '', '', 1478771958, 1478771958, 99, 1),
(35, 'home_default_module', '前台默认模块', 'system', 'select', 'index', '', '前台默认访问的模块，该模块必须有Index控制器和index方法', '', '', '', '', '', 0, '', '', '', '', 1486714723, 1486715620, 104, 1),
(36, 'minify_status', '开启minify', 'system', 'switch', '0', '', '开启minify会压缩合并js、css文件，可以减少资源请求次数，如果不支持minify，可关闭', '', '', '', '', '', 0, '', '', '', '', 1487035843, 1487035843, 99, 1),
(37, 'upload_driver', '上传驱动', 'upload', 'radio', 'local', 'local:本地', '图片或文件上传驱动', '', '', '', '', '', 0, '', '', '', '', 1501488567, 1501490821, 100, 1),
(38, 'system_log', '系统日志', 'system', 'switch', '1', '', '是否开启系统日志功能', '', '', '', '', '', 0, '', '', '', '', 1512635391, 1512635391, 99, 1),
(39, 'asset_version', '资源版本号', 'develop', 'text', '20180327', '', '可通过修改版号强制用户更新静态文件', '', '', '', '', '', 0, '', '', '', '', 1522143239, 1522143239, 100, 1),
(42, 'video_howtochat', 'Tutor How to chat video', 'para', 'text', '/public/home/images/movie.mp4', '', '教师页面视频网址', '', '', '', '', '', 0, '', '', '', '', 1603629019, 1604416589, 1000, 1),
(43, 'video_student', 'Student how to chat video', 'para', 'text', '/public/home/images/movie.mp4', '', '学生页面视频', '', '', '', '', '', 0, '', '', '', '', 1603629080, 1604416604, 1001, 1),
(44, 'price_class_min', '课时费最低价格', 'para', 'text', '0', '', '', '', '', '', '', '', 0, '', '', '', '', 1603692889, 1604416619, 100, 1),
(45, 'price_class_max', '课时费最高价格', 'para', 'text', '18', '', '', '', '', '', '', '', 0, '', '', '', '', 1603692938, 1604416627, 100, 1),
(46, 'price_class_fee', '服务费', 'para', 'text', '2', '', '', '', '', '', '', '', 0, '', '', '', '', 1603693015, 1604416639, 100, 1),
(47, 'web_site_url', '网址', 'base', 'text', 'http://127.0.0.44', '', '', '', '', '', '', '', 0, '', '', '', '', 1603953285, 1603953285, 1, 1),
(48, 'cfg_cancel_time', '上课开始前几小时可取消上课', 'para', 'text', '3', '', '', '', '', '', '', '', 0, '', '', '', '', 1604416811, 1604416811, 100, 1),
(49, 'cfg_invitation_fee', '推荐码抵扣金额', 'para', 'text', '2', '', '', '', '', '', '', '', 0, '', '', '', '', 1604494199, 1604494199, 100, 1),
(50, 'cfg_email', 'Email', 'base', 'text', 'Manage@WeSpeakEnglish.com', '', '', '', '', '', '', '', 0, '', '', '', '', 1605320048, 1605320048, 100, 1),
(51, 'cfg_facebook', 'Facebook', 'base', 'text', 'WeSpeakEnglish', '', '', '', '', '', '', '', 0, '', '', '', '', 1605320074, 1605320074, 100, 1),
(52, 'cfg_twitter', 'Twitter', 'base', 'text', 'WeSpeakEnglish', '', '', '', '', '', '', '', 0, '', '', '', '', 1605320089, 1605320089, 100, 1),
(53, 'cfg_wechat', 'Wechat', 'base', 'image', '366', '', '', '', '', '', '', '', 0, '', '', '', '', 1605320103, 1605320103, 100, 1),
(54, 'video_howtochat_pic', '教师页面视频图片', 'para', 'image', '375', '', '', '', '', '', '', '', 0, '', '', '', '', 1605326223, 1605326223, 1000, 1),
(55, 'video_student_pic', '学生页面视频图片', 'para', 'image', '374', '', '', '', '', '', '', '', 0, '', '', '', '', 1605326245, 1605326245, 1001, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_hook`
--

CREATE TABLE `dp_admin_hook` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `plugin` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子来自哪个插件',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子描述',
  `system` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否为系统钩子',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='钩子表';

--
-- 转存表中的数据 `dp_admin_hook`
--

INSERT INTO `dp_admin_hook` (`id`, `name`, `plugin`, `description`, `system`, `create_time`, `update_time`, `status`) VALUES
(1, 'admin_index', '', '后台首页', 1, 1468174214, 1477757518, 1),
(2, 'plugin_index_tab_list', '', '插件扩展tab钩子', 1, 1468174214, 1468174214, 1),
(3, 'module_index_tab_list', '', '模块扩展tab钩子', 1, 1468174214, 1468174214, 1),
(4, 'page_tips', '', '每个页面的提示', 1, 1468174214, 1468174214, 1),
(5, 'signin_footer', '', '登录页面底部钩子', 1, 1479269315, 1479269315, 1),
(6, 'signin_captcha', '', '登录页面验证码钩子', 1, 1479269315, 1479269315, 1),
(7, 'signin', '', '登录控制器钩子', 1, 1479386875, 1479386875, 1),
(8, 'upload_attachment', '', '附件上传钩子', 1, 1501493808, 1501493808, 1),
(9, 'page_plugin_js', '', '页面插件js钩子', 1, 1503633591, 1503633591, 1),
(10, 'page_plugin_css', '', '页面插件css钩子', 1, 1503633591, 1503633591, 1),
(11, 'signin_sso', '', '单点登录钩子', 1, 1503633591, 1503633591, 1),
(12, 'signout_sso', '', '单点退出钩子', 1, 1503633591, 1503633591, 1),
(13, 'user_add', '', '添加用户钩子', 1, 1503633591, 1503633591, 1),
(14, 'user_edit', '', '编辑用户钩子', 1, 1503633591, 1503633591, 1),
(15, 'user_delete', '', '删除用户钩子', 1, 1503633591, 1503633591, 1),
(16, 'user_enable', '', '启用用户钩子', 1, 1503633591, 1503633591, 1),
(17, 'user_disable', '', '禁用用户钩子', 1, 1503633591, 1503633591, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_hook_plugin`
--

CREATE TABLE `dp_admin_hook_plugin` (
  `id` int(11) UNSIGNED NOT NULL,
  `hook` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子id',
  `plugin` varchar(32) NOT NULL DEFAULT '' COMMENT '插件标识',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='钩子-插件对应表';

--
-- 转存表中的数据 `dp_admin_hook_plugin`
--

INSERT INTO `dp_admin_hook_plugin` (`id`, `hook`, `plugin`, `create_time`, `update_time`, `sort`, `status`) VALUES
(1, 'admin_index', 'SystemInfo', 1477757503, 1477757503, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_icon`
--

CREATE TABLE `dp_admin_icon` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '图标名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图标css地址',
  `prefix` varchar(32) NOT NULL DEFAULT '' COMMENT '图标前缀',
  `font_family` varchar(32) NOT NULL DEFAULT '' COMMENT '字体名',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图标表';

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_icon_list`
--

CREATE TABLE `dp_admin_icon_list` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `icon_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属图标id',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '图标标题',
  `class` varchar(255) NOT NULL DEFAULT '' COMMENT '图标类名',
  `code` varchar(128) NOT NULL DEFAULT '' COMMENT '图标关键词'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='详细图标列表';

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_log`
--

CREATE TABLE `dp_admin_log` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '主键',
  `action_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` longtext NOT NULL COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '执行行为的时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='行为日志表' ROW_FORMAT=FIXED;

--
-- 转存表中的数据 `dp_admin_log`
--

INSERT INTO `dp_admin_log` (`id`, `action_id`, `user_id`, `action_ip`, `model`, `record_id`, `remark`, `status`, `create_time`) VALUES
(1, 35, 1, 2130706433, 'admin_module', 0, '超级管理员 安装了模块：门户', 1, 1598021959),
(2, 57, 1, 2130706433, 'cms_model', 1, '超级管理员 添加了内容模型：文章', 1, 1598022003),
(3, 77, 1, 2130706433, 'cms_column', 1, '超级管理员 添加了栏目：医院概况', 1, 1598022032),
(4, 77, 1, 2130706433, 'cms_column', 2, '超级管理员 添加了栏目：医院动态', 1, 1598022299),
(5, 77, 1, 2130706433, 'cms_column', 3, '超级管理员 添加了栏目：医疗设备', 1, 1598022310),
(6, 77, 1, 2130706433, 'cms_column', 4, '超级管理员 添加了栏目：专科建设', 1, 1598022317),
(7, 77, 1, 2130706433, 'cms_column', 5, '超级管理员 添加了栏目：名医风采', 1, 1598022327),
(8, 77, 1, 2130706433, 'cms_column', 6, '超级管理员 添加了栏目：普爱护理', 1, 1598022335),
(9, 77, 1, 2130706433, 'cms_column', 7, '超级管理员 添加了栏目：健康体检', 1, 1598022343),
(10, 77, 1, 2130706433, 'cms_column', 8, '超级管理员 添加了栏目：党群工作', 1, 1598022359),
(11, 77, 1, 2130706433, 'cms_column', 9, '超级管理员 添加了栏目：司法鉴定', 1, 1598022377),
(12, 77, 1, 2130706433, 'cms_column', 10, '超级管理员 添加了栏目：医疗服务', 1, 1598022384),
(13, 77, 1, 2130706433, 'cms_column', 11, '超级管理员 添加了栏目：科研教学', 1, 1598022391),
(14, 77, 1, 2130706433, 'cms_column', 12, '超级管理员 添加了栏目：区域医疗', 1, 1598022399),
(15, 77, 1, 2130706433, 'cms_column', 13, '超级管理员 添加了栏目：医院历史', 1, 1598022411),
(16, 77, 1, 2130706433, 'cms_column', 14, '超级管理员 添加了栏目：医院简介', 1, 1598022420),
(17, 77, 1, 2130706433, 'cms_column', 15, '超级管理员 添加了栏目：医院领导', 1, 1598022428),
(18, 77, 1, 2130706433, 'cms_column', 16, '超级管理员 添加了栏目：医院公告', 1, 1598022437),
(19, 77, 1, 2130706433, 'cms_column', 17, '超级管理员 添加了栏目：医院新闻', 1, 1598022450),
(20, 34, 1, 2130706433, 'admin_menu', 214, '超级管理员 禁用了节点：节点ID(214),节点标题(消息管理),节点链接()', 1, 1598022487),
(21, 34, 1, 2130706433, 'admin_menu', 32, '超级管理员 禁用了节点：节点ID(32),节点标题(扩展中心),节点链接()', 1, 1598022502),
(22, 34, 1, 2130706433, 'admin_menu', 185, '超级管理员 禁用了节点：节点ID(185),节点标题(行为管理),节点链接(admin/action/index)', 1, 1598022506),
(23, 34, 1, 2130706433, 'admin_menu', 7, '超级管理员 禁用了节点：节点ID(7),节点标题(配置管理),节点链接(admin/config/index)', 1, 1598022512),
(24, 34, 1, 2130706433, 'admin_menu', 13, '超级管理员 禁用了节点：节点ID(13),节点标题(节点管理),节点链接(admin/menu/index)', 1, 1598022514),
(25, 34, 1, 2130706433, 'admin_menu', 247, '超级管理员 禁用了节点：节点ID(247),节点标题(单页管理),节点链接(cms/page/index)', 1, 1598022533),
(26, 34, 1, 2130706433, 'admin_menu', 273, '超级管理员 禁用了节点：节点ID(273),节点标题(滚动图片),节点链接(cms/slider/index)', 1, 1598022538),
(27, 34, 1, 2130706433, 'admin_menu', 280, '超级管理员 禁用了节点：节点ID(280),节点标题(友情链接),节点链接(cms/link/index)', 1, 1598022546),
(28, 32, 1, 2130706433, 'admin_menu', 287, '超级管理员 删除了节点：节点ID(287),节点标题(客服管理),节点链接(cms/support/index)', 1, 1598022549),
(29, 34, 1, 2130706433, 'admin_menu', 316, '超级管理员 禁用了节点：节点ID(316),节点标题(导航管理),节点链接(cms/nav/index)', 1, 1598022560),
(30, 72, 1, 2130706433, 'cms_field', 18, '超级管理员 添加了模型字段：详情：文档模型(文章)、字段名称(thumb)、字段标题(图片)、字段类型(image)', 1, 1598022644),
(31, 72, 1, 2130706433, 'cms_field', 19, '超级管理员 添加了模型字段：详情：文档模型(文章)、字段名称(descr)、字段标题(简介)、字段类型(textarea)', 1, 1598022686),
(32, 72, 1, 2130706433, 'cms_field', 20, '超级管理员 添加了模型字段：详情：文档模型(文章)、字段名称(content)、字段标题(内容)、字段类型(ueditor)', 1, 1598022707),
(33, 34, 1, 2130706433, 'admin_menu', 302, '超级管理员 禁用了节点：节点ID(302),节点标题(内容模型),节点链接(cms/model/index)', 1, 1598022751),
(34, 76, 1, 2130706433, 'cms_column', 1, '超级管理员 编辑了栏目：医院概况', 1, 1598022772),
(35, 92, 1, 2130706433, 'cms_document', 1, '超级管理员 添加了文档：测试', 1, 1598022791),
(36, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(base)', 1, 1598022912),
(37, 20, 1, 2130706433, 'database', 0, '超级管理员 备份了数据库：dp_admin_access,dp_admin_action,dp_admin_attachment,dp_admin_config,dp_admin_hook,dp_admin_hook_plugin,dp_admin_icon,dp_admin_icon_list,dp_admin_log,dp_admin_menu,dp_admin_message,dp_admin_module,dp_admin_packet,dp_admin_plugin,dp_admin_role,dp_admin_user,dp_cms_advert,dp_cms_advert_type,dp_cms_column,dp_cms_document,dp_cms_document_article,dp_cms_field,dp_cms_link,dp_cms_menu,dp_cms_model,dp_cms_nav,dp_cms_page,dp_cms_slider,dp_cms_support', 1, 1598023275),
(38, 31, 1, 2130706433, 'admin_menu', 259, '超级管理员 编辑了节点：节点ID(259)', 1, 1598066390),
(39, 31, 1, 2130706433, 'admin_menu', 295, '超级管理员 编辑了节点：节点ID(295)', 1, 1598066406),
(40, 34, 1, 2130706433, 'admin_menu', 258, '超级管理员 禁用了节点：节点ID(258),节点标题(营销管理),节点链接()', 1, 1598066420),
(41, 34, 1, 2130706433, 'admin_menu', 294, '超级管理员 禁用了节点：节点ID(294),节点标题(门户设置),节点链接()', 1, 1598066423),
(42, 34, 1, 2130706433, 'admin_menu', 239, '超级管理员 禁用了节点：节点ID(239),节点标题(仪表盘),节点链接(cms/index/index)', 1, 1598066436),
(43, 82, 1, 2130706433, 'cms_advert_type', 1, '超级管理员 添加了广告分类：首页幻灯片', 1, 1598066455),
(44, 87, 1, 2130706433, 'cms_advert', 1, '超级管理员 添加了广告：新国家', 1, 1598066719),
(45, 77, 1, 2130706433, 'cms_column', 18, '超级管理员 添加了栏目：健康教育', 1, 1598067690),
(46, 77, 1, 2130706433, 'cms_column', 19, '超级管理员 添加了栏目：内科', 1, 1598067764),
(47, 77, 1, 2130706433, 'cms_column', 20, '超级管理员 添加了栏目：妇产科', 1, 1598067776),
(48, 72, 1, 2130706433, 'cms_field', 21, '超级管理员 添加了模型字段：详情：文档模型(文章)、字段名称(zhiwu)、字段标题(职务)、字段类型(text)', 1, 1598068196),
(49, 71, 1, 2130706433, 'cms_field', 20, '超级管理员 编辑了模型字段：字段(sort)，原值(100)，新值：(1000)', 1, 1598068234),
(50, 31, 1, 2130706433, 'admin_menu', 280, '超级管理员 编辑了节点：节点ID(280)', 1, 1598068385),
(51, 33, 1, 2130706433, 'admin_menu', 280, '超级管理员 启用了节点：节点ID(280),节点标题(友情链接),节点链接(cms/link/index)', 1, 1598068415),
(52, 67, 1, 2130706433, 'cms_link', 1, '超级管理员 添加了友情链接：邵阳市卫生局', 1, 1598068469),
(53, 67, 1, 2130706433, 'cms_link', 2, '超级管理员 添加了友情链接：湖南卫生厅', 1, 1598068479),
(54, 67, 1, 2130706433, 'cms_link', 3, '超级管理员 添加了友情链接：OA办公', 1, 1598068574),
(55, 92, 1, 2130706433, 'cms_document', 2, '超级管理员 添加了文档：宝岛眼镜店', 1, 1598151031),
(56, 91, 1, 2130706433, 'cms_document', 2, '超级管理员 编辑了文档：宝岛眼镜店宝岛眼镜店宝岛眼镜店宝岛眼镜店宝岛眼镜店', 1, 1598154376),
(57, 92, 1, 2130706433, 'cms_document', 3, '超级管理员 添加了文档：宝岛眼镜店23423', 1, 1598155646),
(58, 92, 1, 2130706433, 'cms_document', 4, '超级管理员 添加了文档：第一代试管婴儿是什么意思？', 1, 1598155905),
(59, 87, 1, 2130706433, 'cms_advert', 2, '超级管理员 添加了广告：新国家2', 1, 1598157978),
(60, 91, 1, 2130706433, 'cms_document', 3, '超级管理员 编辑了文档：宝岛眼镜店23423', 1, 1598158972),
(61, 90, 1, 2130706433, 'cms_document', 1, '超级管理员 回收了文档：测试', 1, 1598161294),
(62, 90, 1, 2130706433, 'cms_document', 1, '超级管理员 回收了文档：测试', 1, 1598161300),
(63, 91, 1, 2130706433, 'cms_document', 2, '超级管理员 编辑了文档：表名(cms_document)，字段(status)，原值(1)，新值：(false)', 1, 1598161308),
(64, 91, 1, 2130706433, 'cms_document', 2, '超级管理员 编辑了文档：表名(cms_document)，字段(status)，原值(0)，新值：(true)', 1, 1598161314),
(65, 86, 1, 2130706433, 'cms_advert', 2, '超级管理员 编辑了广告：新国家2', 1, 1598163026),
(66, 72, 1, 2130706433, 'cms_field', 22, '超级管理员 添加了模型字段：详情：文档模型(文章)、字段名称(zhichen)、字段标题(职称)、字段类型(text)', 1, 1598164749),
(67, 30, 1, 2130706433, 'admin_menu', 331, '超级管理员 添加了节点：所属模块(cms),所属节点ID(238),节点标题(区域医疗),节点链接(cms/document/index)', 1, 1598165958),
(68, 73, 1, 2130706433, 'cms_column', 0, '超级管理员 禁用了栏目：呼吸内科、康复医学科、内分泌科、神经内科、肾内科、消化内科、骨科一病区（创伤关节）、骨科二病区（脊柱骨病）、泌尿外科、普外科、医学影像中心、疼痛科、内分泌科、康复医学科、呼吸内科、2006年度、2007年度、2008年度、2009年度、2010年度、2011年度、2012年度、2013年度、研究生协会、临床教学、科研工作、网上预约、在线咨询、一卡通、医保农合、就医指南、收费标准、鉴定项目、鉴定流程、服务承诺、普爱司法鉴定所简介、党务公开、青春园地、工会、纪检监察、统战工作、思想政治教育工作专栏、党建工作、下载专区、天使风采、优质护理、护理资讯、护理概况、营养科、皮肤科、急诊科（ICU）、儿科、妇产科、五官科、外科、内科、内窥镜诊疗设备、超声设备、医学检验设备、医学影像设备、项目查询、资料下载、质控简报、医院财务预决算信息公开、医院名人、联系我们、发展愿景、医院荣誉、内分泌科、康复科、呼吸内科、血液肿瘤科、心血管内科、消化内科、肾内科、神经内科、感染科、疼痛科、医技科、皮肤科、儿科、中医科、外科、急诊科、妇产科、内科、医院信息、人才招聘、医院新闻、医院公告、健康教育、机构设置、医院领导、医院简介、医院历史、区域医疗、科研教学、医疗服务、司法鉴定、党群工作、健康体检、普爱护理、名医风采、专科建设、医疗设备、医院动态、医院概况、神经外科、心胸外科、耳鼻咽喉-头颈外科、口腔科、眼科、药学部、五官科、骨科一病区（创伤关节）、骨科二病区（脊柱骨病）、泌尿外科、普外科、神经外科、心胸外科、耳鼻咽喉-头颈外科、口腔科、眼科、产科、妇科、心血管内科、血液肿瘤科、医学检验中心、2014年度、2015年度、2016年度、2017年度、2018年度、感染科、全科医学科', 1, 1599392779),
(69, 77, 1, 2130706433, 'cms_column', 191, '超级管理员 添加了栏目：医院概况', 1, 1599394206),
(70, 77, 1, 2130706433, 'cms_column', 192, '超级管理员 添加了栏目：国家级流派传承工作室建设项目-湖南孙氏正骨流派传承工作室', 1, 1599394220),
(71, 77, 1, 2130706433, 'cms_column', 193, '超级管理员 添加了栏目：正骨新闻', 1, 1599394234),
(72, 77, 1, 2130706433, 'cms_column', 194, '超级管理员 添加了栏目：科室导航', 1, 1599394324),
(73, 77, 1, 2130706433, 'cms_column', 195, '超级管理员 添加了栏目：医院管理', 1, 1599394335),
(74, 77, 1, 2130706433, 'cms_column', 196, '超级管理员 添加了栏目：病友服务', 1, 1599394345),
(75, 77, 1, 2130706433, 'cms_column', 197, '超级管理员 添加了栏目：医学研究', 1, 1599394361),
(76, 77, 1, 2130706433, 'cms_column', 198, '超级管理员 添加了栏目：医院介绍', 1, 1599394387),
(77, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(base)', 1, 1599394420),
(78, 16, 1, 2130706433, 'admin_config', 4, '超级管理员 编辑了配置：字段(status)，原值(1)，新值：(false)', 1, 1599394432),
(79, 16, 1, 2130706433, 'admin_config', 26, '超级管理员 编辑了配置：字段(status)，原值(1)，新值：(false)', 1, 1599394433),
(80, 16, 1, 2130706433, 'admin_config', 8, '超级管理员 编辑了配置：字段(type)，原值(text)，新值：(textarea)', 1, 1599394441),
(81, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(base)', 1, 1599394447),
(82, 77, 1, 2130706433, 'cms_column', 199, '超级管理员 添加了栏目：历史沿革', 1, 1599394509),
(83, 77, 1, 2130706433, 'cms_column', 200, '超级管理员 添加了栏目：领导团队', 1, 1599394519),
(84, 77, 1, 2130706433, 'cms_column', 201, '超级管理员 添加了栏目：组织架构', 1, 1599394555),
(85, 77, 1, 2130706433, 'cms_column', 202, '超级管理员 添加了栏目：联系我们', 1, 1599394698),
(86, 76, 1, 2130706433, 'cms_column', 200, '超级管理员 编辑了栏目：领导团队', 1, 1599394716),
(87, 76, 1, 2130706433, 'cms_column', 201, '超级管理员 编辑了栏目：组织架构', 1, 1599394725),
(88, 76, 1, 2130706433, 'cms_column', 202, '超级管理员 编辑了栏目：联系我们', 1, 1599394734),
(89, 76, 1, 2130706433, 'cms_column', 198, '超级管理员 编辑了栏目：医院介绍', 1, 1599394899),
(90, 76, 1, 2130706433, 'cms_column', 191, '超级管理员 编辑了栏目：医院概况', 1, 1599394904),
(91, 76, 1, 2130706433, 'cms_column', 200, '超级管理员 编辑了栏目：领导团队', 1, 1599394926),
(92, 76, 1, 2130706433, 'cms_column', 201, '超级管理员 编辑了栏目：组织架构', 1, 1599394941),
(93, 76, 1, 2130706433, 'cms_column', 201, '超级管理员 编辑了栏目：组织架构', 1, 1599394969),
(94, 76, 1, 2130706433, 'cms_column', 192, '超级管理员 编辑了栏目：国家级流派传承工作室建设项目-湖南孙氏正骨流派传承工作室', 1, 1599394995),
(95, 76, 1, 2130706433, 'cms_column', 193, '超级管理员 编辑了栏目：正骨新闻', 1, 1599395007),
(96, 77, 1, 2130706433, 'cms_column', 203, '超级管理员 添加了栏目：流派发展历程', 1, 1599395099),
(97, 77, 1, 2130706433, 'cms_column', 204, '超级管理员 添加了栏目：特色诊疗技术', 1, 1599395117),
(98, 77, 1, 2130706433, 'cms_column', 205, '超级管理员 添加了栏目：医院要闻', 1, 1599395146),
(99, 77, 1, 2130706433, 'cms_column', 206, '超级管理员 添加了栏目：媒体聚焦', 1, 1599395152),
(100, 77, 1, 2130706433, 'cms_column', 207, '超级管理员 添加了栏目：临床科室', 1, 1599395179),
(101, 77, 1, 2130706433, 'cms_column', 208, '超级管理员 添加了栏目：医技科室', 1, 1599395186),
(102, 77, 1, 2130706433, 'cms_column', 209, '超级管理员 添加了栏目：职能部门', 1, 1599395211),
(103, 77, 1, 2130706433, 'cms_column', 210, '超级管理员 添加了栏目：医务管理', 1, 1599395220),
(104, 77, 1, 2130706433, 'cms_column', 211, '超级管理员 添加了栏目：就诊须知', 1, 1599395240),
(105, 77, 1, 2130706433, 'cms_column', 212, '超级管理员 添加了栏目：住院须知', 1, 1599395315),
(106, 77, 1, 2130706433, 'cms_column', 213, '超级管理员 添加了栏目： 医保专区', 1, 1599395326),
(107, 77, 1, 2130706433, 'cms_column', 214, '超级管理员 添加了栏目：服务专区', 1, 1599395340),
(108, 76, 1, 2130706433, 'cms_column', 198, '超级管理员 编辑了栏目：字段(type_id)，原值(1)，新值：(2)', 1, 1599395587),
(109, 76, 1, 2130706433, 'cms_column', 201, '超级管理员 编辑了栏目：字段(type_id)，原值(0)，新值：(2)', 1, 1599395604),
(110, 76, 1, 2130706433, 'cms_column', 202, '超级管理员 编辑了栏目：字段(type_id)，原值(0)，新值：(2)', 1, 1599395607),
(111, 76, 1, 2130706433, 'cms_column', 211, '超级管理员 编辑了栏目：字段(type_id)，原值(0)，新值：(2)', 1, 1599395631),
(112, 76, 1, 2130706433, 'cms_column', 212, '超级管理员 编辑了栏目：字段(type_id)，原值(0)，新值：(2)', 1, 1599395634),
(113, 76, 1, 2130706433, 'cms_column', 213, '超级管理员 编辑了栏目：字段(type_id)，原值(0)，新值：(2)', 1, 1599395637),
(114, 76, 1, 2130706433, 'cms_column', 214, '超级管理员 编辑了栏目：字段(type_id)，原值(0)，新值：(1)', 1, 1599395649),
(115, 77, 1, 2130706433, 'cms_column', 215, '超级管理员 添加了栏目：科研成果', 1, 1599395673),
(116, 77, 1, 2130706433, 'cms_column', 216, '超级管理员 添加了栏目：学科建设', 1, 1599395683),
(117, 77, 1, 2130706433, 'cms_column', 217, '超级管理员 添加了栏目：科研动态', 1, 1599395698),
(118, 77, 1, 2130706433, 'cms_column', 218, '超级管理员 添加了栏目：南方医科大学钟世镇院士工作站', 1, 1599395717),
(119, 86, 1, 2130706433, 'cms_advert', 2, '超级管理员 编辑了广告：新国家2', 1, 1599397403),
(120, 86, 1, 2130706433, 'cms_advert', 1, '超级管理员 编辑了广告：新国家', 1, 1599397430),
(121, 92, 1, 2130706433, 'cms_document', 1572, '超级管理员 添加了文档：关于举办新邵孙氏正骨术传承学习班的通知', 1, 1599398366),
(122, 71, 1, 2130706433, 'cms_field', 20, '超级管理员 编辑了模型字段：content', 1, 1599398452),
(123, 91, 1, 2130706433, 'cms_document', 1572, '超级管理员 编辑了文档：关于举办新邵孙氏正骨术传承学习班的通知', 1, 1599400217),
(124, 71, 1, 2130706433, 'cms_field', 22, '超级管理员 编辑了模型字段：zhichen', 1, 1599400516),
(125, 92, 1, 2130706433, 'cms_document', 1573, '超级管理员 添加了文档：廖怀章', 1, 1599400588),
(126, 71, 1, 2130706433, 'cms_field', 22, '超级管理员 编辑了模型字段：zhichen', 1, 1599400701),
(127, 91, 1, 2130706433, 'cms_document', 1573, '超级管理员 编辑了文档：廖怀章', 1, 1599400784),
(128, 77, 1, 2130706433, 'cms_column', 219, '超级管理员 添加了栏目：医院环境', 1, 1599400812),
(129, 76, 1, 2130706433, 'cms_column', 219, '超级管理员 编辑了栏目：医院环境', 1, 1599400823),
(130, 77, 1, 2130706433, 'cms_column', 220, '超级管理员 添加了栏目：医院荣誉', 1, 1599400843),
(131, 77, 1, 2130706433, 'cms_column', 221, '超级管理员 添加了栏目：医院设备', 1, 1599400858),
(132, 92, 1, 2130706433, 'cms_document', 1574, '超级管理员 添加了文档：环境图1', 1, 1599400940),
(133, 92, 1, 2130706433, 'cms_document', 1575, '超级管理员 添加了文档：环境图2', 1, 1599400953),
(134, 92, 1, 2130706433, 'cms_document', 1576, '超级管理员 添加了文档：荣誉1', 1, 1599400983),
(135, 92, 1, 2130706433, 'cms_document', 1577, '超级管理员 添加了文档：设备1', 1, 1599401004),
(136, 92, 1, 2130706433, 'cms_document', 1578, '超级管理员 添加了文档：新邵县中医院挂牌成立。开放骨伤科住院病床5张。', 1, 1599402655),
(137, 92, 1, 2130706433, 'cms_document', 1579, '超级管理员 添加了文档：新邵县中医院挂牌成立。开放骨伤科住院病床5张。', 1, 1599402664),
(138, 70, 1, 2130706433, 'cms_field', 22, '超级管理员 删除了模型字段：详情：文档模型(文章)、字段名称(zhichen)、字段标题(职称)、字段类型(text)', 1, 1599402924),
(139, 70, 1, 2130706433, 'cms_field', 19, '超级管理员 删除了模型字段：详情：文档模型(文章)、字段名称(descr)、字段标题(简介)、字段类型(textarea)', 1, 1599403154),
(140, 72, 1, 2130706433, 'cms_field', 23, '超级管理员 添加了模型字段：详情：文档模型(文章)、字段名称(descr)、字段标题(简介)、字段类型(textarea)', 1, 1599403196),
(141, 91, 1, 2130706433, 'cms_document', 1573, '超级管理员 编辑了文档：廖怀章', 1, 1599403228),
(142, 92, 1, 2130706433, 'cms_document', 1580, '超级管理员 添加了文档：医生 2', 1, 1599403290),
(143, 76, 1, 2130706433, 'cms_column', 201, '超级管理员 编辑了栏目：组织架构', 1, 1599403359),
(144, 92, 1, 2130706433, 'cms_document', 1581, '超级管理员 添加了文档：正骨科', 1, 1599403714),
(145, 72, 1, 2130706433, 'cms_field', 24, '超级管理员 添加了模型字段：详情：文档模型(文章)、字段名称(source)、字段标题(来源)、字段类型(text)', 1, 1599404875),
(146, 72, 1, 2130706433, 'cms_field', 25, '超级管理员 添加了模型字段：详情：文档模型(文章)、字段名称(writer)、字段标题(作者)、字段类型(text)', 1, 1599404895),
(147, 76, 1, 2130706433, 'cms_column', 211, '超级管理员 编辑了栏目：就诊须知', 1, 1599405094),
(148, 77, 1, 2130706433, 'cms_column', 222, '超级管理员 添加了栏目：其他', 1, 1599405202),
(149, 77, 1, 2130706433, 'cms_column', 223, '超级管理员 添加了栏目：预约挂号', 1, 1599405231),
(150, 77, 1, 2130706433, 'cms_column', 224, '超级管理员 添加了栏目：门诊排班', 1, 1599405244),
(151, 77, 1, 2130706433, 'cms_column', 225, '超级管理员 添加了栏目：专家咨询', 1, 1599405262),
(152, 30, 1, 2130706433, 'admin_menu', 343, '超级管理员 添加了节点：所属模块(cms),所属节点ID(238),节点标题(留言管理),节点链接(cms/feedback/index)', 1, 1599440456),
(153, 77, 1, 2130706433, 'cms_column', 226, '超级管理员 添加了栏目：康复案例', 1, 1599441074),
(154, 92, 1, 2130706433, 'cms_document', 1582, '超级管理员 添加了文档：邵东交通事故25名车祸伤员全部转危为安', 1, 1599444093),
(155, 92, 1, 2130706433, 'cms_document', 1583, '超级管理员 添加了文档：骨折桡骨背向移位', 1, 1599444212),
(156, 92, 1, 2130706433, 'cms_document', 1584, '超级管理员 添加了文档：经桡骨 Lister 结节髓', 1, 1599444247),
(157, 91, 1, 2130706433, 'cms_document', 1583, '超级管理员 编辑了文档：骨折桡骨背向移位', 1, 1599444257),
(158, 32, 1, 2130706433, 'admin_menu', 336, '超级管理员 删除了节点：节点ID(336),节点标题(医院概况),节点链接(cms/document/index)', 1, 1599530488),
(159, 32, 1, 2130706433, 'admin_menu', 337, '超级管理员 删除了节点：节点ID(337),节点标题(传承工作室),节点链接(cms/document/index)', 1, 1599530496),
(160, 32, 1, 2130706433, 'admin_menu', 338, '超级管理员 删除了节点：节点ID(338),节点标题(正骨新闻),节点链接(cms/document/index)', 1, 1599530501),
(161, 32, 1, 2130706433, 'admin_menu', 339, '超级管理员 删除了节点：节点ID(339),节点标题(科室导航),节点链接(cms/document/index)', 1, 1599530508),
(162, 32, 1, 2130706433, 'admin_menu', 340, '超级管理员 删除了节点：节点ID(340),节点标题(医院管理),节点链接(cms/document/index)', 1, 1599530514),
(163, 32, 1, 2130706433, 'admin_menu', 341, '超级管理员 删除了节点：节点ID(341),节点标题(病友服务),节点链接(cms/document/index)', 1, 1599530520),
(164, 32, 1, 2130706433, 'admin_menu', 342, '超级管理员 删除了节点：节点ID(342),节点标题(医学研究),节点链接(cms/document/index)', 1, 1599530526),
(165, 32, 1, 2130706433, 'admin_menu', 343, '超级管理员 删除了节点：节点ID(343),节点标题(留言管理),节点链接(cms/feedback/index)', 1, 1599530532),
(166, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(base)', 1, 1599531103),
(167, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(cms)', 1, 1599531120),
(168, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(system)', 1, 1600048352),
(169, 15, 1, 2130706433, 'admin_config', 40, '超级管理员 添加了配置，详情：分组(para)、类型(array)、标题(英语级别)、名称(english_level)', 1, 1600048551),
(170, 15, 1, 2130706433, 'admin_config', 41, '超级管理员 添加了配置，详情：分组(para)、类型(array)、标题(母语)、名称(native_language)', 1, 1600048597),
(171, 31, 1, 2130706433, 'admin_menu', 19, '超级管理员 编辑了节点：节点ID(19)', 1, 1601261684),
(172, 31, 1, 2130706433, 'admin_menu', 19, '超级管理员 编辑了节点：节点ID(19)', 1, 1601261714),
(173, 34, 1, 2130706433, 'admin_menu', 68, '超级管理员 禁用了节点：节点ID(68),节点标题(用户),节点链接(user/index/index)', 1, 1601261722),
(174, 30, 1, 2130706433, 'admin_menu', 344, '超级管理员 添加了节点：所属模块(cms),所属节点ID(237),节点标题(会员管理),节点链接(cms/member/index)', 1, 1601261760),
(175, 32, 1, 2130706433, 'admin_menu', 316, '超级管理员 删除了节点：节点ID(316),节点标题(导航管理),节点链接(cms/nav/index)', 1, 1601261769),
(176, 32, 1, 2130706433, 'admin_menu', 258, '超级管理员 删除了节点：节点ID(258),节点标题(营销管理),节点链接()', 1, 1601261778),
(177, 32, 1, 2130706433, 'admin_menu', 239, '超级管理员 删除了节点：节点ID(239),节点标题(仪表盘),节点链接(cms/index/index)', 1, 1601261789),
(178, 30, 1, 2130706433, 'admin_menu', 345, '超级管理员 添加了节点：所属模块(cms),所属节点ID(344),节点标题(学生管理),节点链接(cms/users/index)', 1, 1601261837),
(179, 31, 1, 2130706433, 'admin_menu', 344, '超级管理员 编辑了节点：节点ID(344)', 1, 1601261846),
(180, 30, 1, 2130706433, 'admin_menu', 346, '超级管理员 添加了节点：所属模块(cms),所属节点ID(344),节点标题(教师管理),节点链接(cms/users/teacher)', 1, 1601261883),
(181, 31, 1, 2130706433, 'admin_menu', 344, '超级管理员 编辑了节点：节点ID(344)', 1, 1601261930),
(182, 30, 1, 2130706433, 'admin_menu', 347, '超级管理员 添加了节点：所属模块(cms),所属节点ID(344),节点标题(订单管理),节点链接(cms/order/index)', 1, 1601262043),
(183, 31, 1, 2130706433, 'admin_menu', 241, '超级管理员 编辑了节点：节点ID(241)', 1, 1601278875),
(184, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(para)', 1, 1602378899),
(185, 16, 1, 2130706433, 'admin_config', 40, '超级管理员 编辑了配置：原数据：分组(para)、类型(array)、标题(英语级别)、名称(english_level)', 1, 1602378951),
(186, 16, 1, 2130706433, 'admin_config', 41, '超级管理员 编辑了配置：原数据：分组(para)、类型(array)、标题(母语)、名称(native_language)', 1, 1602378960),
(187, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(para)', 1, 1602379133),
(188, 16, 1, 2130706433, 'admin_config', 40, '超级管理员 编辑了配置：原数据：分组(para)、类型(array)、标题(英语级别)、名称(english_level)', 1, 1602379180),
(189, 16, 1, 2130706433, 'admin_config', 41, '超级管理员 编辑了配置：原数据：分组(para)、类型(array)、标题(母语)、名称(native_language)', 1, 1602379198),
(190, 16, 1, 2130706433, 'admin_config', 40, '超级管理员 编辑了配置：原数据：分组(para)、类型(array)、标题(英语级别)、名称(english_level)', 1, 1602379204),
(191, 30, 1, 2130706433, 'admin_menu', 348, '超级管理员 添加了节点：所属模块(admin),所属节点ID(4),节点标题(时区管理),节点链接(cms/location/index)', 1, 1603416431),
(192, 31, 1, 2130706433, 'admin_menu', 348, '超级管理员 编辑了节点：节点ID(348)', 1, 1603416461),
(193, 33, 1, 2130706433, 'admin_menu', 185, '超级管理员 启用了节点：节点ID(185),节点标题(行为管理),节点链接(admin/action/index)', 1, 1603418303),
(194, 33, 1, 2130706433, 'admin_menu', 13, '超级管理员 启用了节点：节点ID(13),节点标题(节点管理),节点链接(admin/menu/index)', 1, 1603418592),
(195, 33, 1, 2130706433, 'admin_menu', 7, '超级管理员 启用了节点：节点ID(7),节点标题(配置管理),节点链接(admin/config/index)', 1, 1603418596),
(196, 30, 1, 2130706433, 'admin_menu', 349, '超级管理员 添加了节点：所属模块(admin),所属节点ID(348),节点标题(增加时区),节点链接(cms/location/add)', 1, 1603418612),
(197, 30, 1, 2130706433, 'admin_menu', 350, '超级管理员 添加了节点：所属模块(admin),所属节点ID(348),节点标题(修改时区),节点链接(cms/location/edit)', 1, 1603418634),
(198, 30, 1, 2130706433, 'admin_menu', 351, '超级管理员 添加了节点：所属模块(admin),所属节点ID(348),节点标题(删除时区),节点链接(cms/location/delete)', 1, 1603418671),
(199, 100, 1, 2130706433, 'cms_location', 6, '超级管理员 增加了时区：英国', 1, 1603419289),
(200, 101, 1, 2130706433, 'cms_location', 6, '超级管理员 修改了时区：English', 1, 1603419527),
(201, 30, 1, 2130706433, 'admin_menu', 352, '超级管理员 添加了节点：所属模块(cms),所属节点ID(344),节点标题(修改会员),节点链接(cms/users/edit)', 1, 1603444512),
(202, 31, 1, 2130706433, 'admin_menu', 352, '超级管理员 编辑了节点：节点ID(352)', 1, 1603444681),
(203, 30, 1, 2130706433, 'admin_menu', 353, '超级管理员 添加了节点：所属模块(cms),所属节点ID(344),节点标题(捐赠记录),节点链接(cms/donation/index)', 1, 1603511301),
(204, 86, 1, 2130706433, 'cms_advert', 2, '超级管理员 编辑了广告：新国家2', 1, 1603627560),
(205, 86, 1, 2130706433, 'cms_advert', 1, '超级管理员 编辑了广告：新国家', 1, 1603627766),
(206, 82, 1, 2130706433, 'cms_advert_type', 2, '超级管理员 添加了广告分类：学生注册顶部广告', 1, 1603627809),
(207, 87, 1, 2130706433, 'cms_advert', 3, '超级管理员 添加了广告：$2 CHAT WITH NATIVE ENGLISTH SPEAKER', 1, 1603627976),
(208, 82, 1, 2130706433, 'cms_advert_type', 3, '超级管理员 添加了广告分类：教师页顶部广告', 1, 1603628132),
(209, 81, 1, 2130706433, 'cms_advert_type', 2, '超级管理员 编辑了广告分类：字段(name)，原值(学生注册顶部广告)，新值：(学生页顶部广告)', 1, 1603628140),
(210, 87, 1, 2130706433, 'cms_advert', 4, '超级管理员 添加了广告：TOＢＥAＴＵＴＯＲ', 1, 1603628173),
(211, 86, 1, 2130706433, 'cms_advert', 4, '超级管理员 编辑了广告：TOＢＥAＴＵＴＯＲ', 1, 1603628220),
(212, 86, 1, 2130706433, 'cms_advert', 4, '超级管理员 编辑了广告：TO BE A TUTOR', 1, 1603628561),
(213, 15, 1, 2130706433, 'admin_config', 42, '超级管理员 添加了配置，详情：分组(base)、类型(text)、标题(How to chat video)、名称(video_howtochat)', 1, 1603629018),
(214, 15, 1, 2130706433, 'admin_config', 43, '超级管理员 添加了配置，详情：分组(base)、类型(text)、标题(Student how to chat video)、名称(video_student)', 1, 1603629080),
(215, 16, 1, 2130706433, 'admin_config', 42, '超级管理员 编辑了配置：字段(title)，原值(How to chat video)，新值：(Tutor How to chat video)', 1, 1603629098),
(216, 30, 1, 2130706433, 'admin_menu', 354, '超级管理员 添加了节点：所属模块(cms),所属节点ID(238),节点标题(谈话主题),节点链接(cms/topic/index)', 1, 1603629727),
(217, 30, 1, 2130706433, 'admin_menu', 355, '超级管理员 添加了节点：所属模块(cms),所属节点ID(238),节点标题(推荐老师),节点链接(cms/teacher/index)', 1, 1603629759),
(218, 30, 1, 2130706433, 'admin_menu', 356, '超级管理员 添加了节点：所属模块(cms),所属节点ID(354),节点标题(添加主题),节点链接(cms/topic/add)', 1, 1603633293),
(219, 30, 1, 2130706433, 'admin_menu', 357, '超级管理员 添加了节点：所属模块(cms),所属节点ID(354),节点标题(修改主题),节点链接(cms/topic/edit)', 1, 1603633314),
(220, 30, 1, 2130706433, 'admin_menu', 358, '超级管理员 添加了节点：所属模块(cms),所属节点ID(354),节点标题(删除主题),节点链接(cms/topic/delete)', 1, 1603633358),
(221, 103, 1, 2130706433, 'cms_topic', 1, '超级管理员 增加了主题：FOOD', 1, 1603633589),
(222, 104, 1, 2130706433, 'cms_topic', 1, '超级管理员 修改了主题：FOOD', 1, 1603633771),
(223, 103, 1, 2130706433, 'cms_topic', 2, '超级管理员 增加了主题：FOOD', 1, 1603633819),
(224, 103, 1, 2130706433, 'cms_topic', 3, '超级管理员 增加了主题：FAMILY', 1, 1603633841),
(225, 104, 1, 2130706433, 'cms_topic', 2, '超级管理员 修改了主题：FOOD', 1, 1603633907),
(226, 30, 1, 2130706433, 'admin_menu', 359, '超级管理员 添加了节点：所属模块(cms),所属节点ID(355),节点标题(添加老师),节点链接(cms/teacher/add)', 1, 1603634302),
(227, 30, 1, 2130706433, 'admin_menu', 360, '超级管理员 添加了节点：所属模块(cms),所属节点ID(355),节点标题(修改老师),节点链接(cms/teacher/edit)', 1, 1603634333),
(228, 30, 1, 2130706433, 'admin_menu', 361, '超级管理员 添加了节点：所属模块(cms),所属节点ID(355),节点标题(删除老师),节点链接(cms/teacher/delete)', 1, 1603634351),
(229, 77, 1, 2130706433, 'cms_column', 227, '超级管理员 添加了栏目：Help', 1, 1603636172),
(230, 77, 1, 2130706433, 'cms_column', 228, '超级管理员 添加了栏目：Tutor', 1, 1603636186),
(231, 77, 1, 2130706433, 'cms_column', 229, '超级管理员 添加了栏目：LEARNER', 1, 1603636215),
(232, 76, 1, 2130706433, 'cms_column', 227, '超级管理员 编辑了栏目：Tutor', 1, 1603636242),
(233, 76, 1, 2130706433, 'cms_column', 228, '超级管理员 编辑了栏目：Registration', 1, 1603636261),
(234, 76, 1, 2130706433, 'cms_column', 229, '超级管理员 编辑了栏目：Class', 1, 1603636267),
(235, 77, 1, 2130706433, 'cms_column', 230, '超级管理员 添加了栏目：LEARNER', 1, 1603636279),
(236, 77, 1, 2130706433, 'cms_column', 231, '超级管理员 添加了栏目：Registration', 1, 1603636303),
(237, 77, 1, 2130706433, 'cms_column', 232, '超级管理员 添加了栏目：Class', 1, 1603636310),
(238, 33, 1, 2130706433, 'admin_menu', 247, '超级管理员 启用了节点：节点ID(247),节点标题(单页管理),节点链接(cms/page/index)', 1, 1603636850),
(239, 30, 1, 2130706433, 'admin_menu', 362, '超级管理员 添加了节点：所属模块(cms),所属节点ID(344),节点标题(提现申请),节点链接(cms/withdrawal/index)', 1, 1603637540),
(240, 30, 1, 2130706433, 'admin_menu', 363, '超级管理员 添加了节点：所属模块(cms),所属节点ID(362),节点标题(查看提现),节点链接(cms/withdrawal/detail)', 1, 1603637587),
(241, 30, 1, 2130706433, 'admin_menu', 364, '超级管理员 添加了节点：所属模块(cms),所属节点ID(362),节点标题(审核提现),节点链接(cms/withdrawal/check)', 1, 1603637602),
(242, 15, 1, 2130706433, 'admin_config', 44, '超级管理员 添加了配置，详情：分组(base)、类型(text)、标题(课时费最低价格)、名称(fee_class_min)', 1, 1603692889),
(243, 15, 1, 2130706433, 'admin_config', 45, '超级管理员 添加了配置，详情：分组(base)、类型(text)、标题(课时费最高价格)、名称(fee_class_max)', 1, 1603692938),
(244, 15, 1, 2130706433, 'admin_config', 46, '超级管理员 添加了配置，详情：分组(base)、类型(text)、标题(课时费费率)、名称(price_class_fee)', 1, 1603693014),
(245, 16, 1, 2130706433, 'admin_config', 44, '超级管理员 编辑了配置：字段(name)，原值(fee_class_min)，新值：(price_class_min)', 1, 1603693028),
(246, 16, 1, 2130706433, 'admin_config', 45, '超级管理员 编辑了配置：字段(name)，原值(fee_class_max)，新值：(price_class_max)', 1, 1603693035),
(247, 82, 1, 2130706433, 'cms_advert_type', 4, '超级管理员 添加了广告分类：首页顶部', 1, 1603951447),
(248, 87, 1, 2130706433, 'cms_advert', 5, '超级管理员 添加了广告：首页顶部广告', 1, 1603951605),
(249, 82, 1, 2130706433, 'cms_advert_type', 5, '超级管理员 添加了广告分类：首页Tutor介绍', 1, 1603951838),
(250, 82, 1, 2130706433, 'cms_advert_type', 6, '超级管理员 添加了广告分类：首页Learner介绍', 1, 1603951859),
(251, 87, 1, 2130706433, 'cms_advert', 6, '超级管理员 添加了广告：AS A TUTOR, YOU CAN', 1, 1603952006),
(252, 86, 1, 2130706433, 'cms_advert', 6, '超级管理员 编辑了广告：字段(typeid)，原值(0)，新值：(5)', 1, 1603952012),
(253, 86, 1, 2130706433, 'cms_advert', 5, '超级管理员 编辑了广告：字段(typeid)，原值(0)，新值：(4)', 1, 1603952015),
(254, 86, 1, 2130706433, 'cms_advert', 4, '超级管理员 编辑了广告：字段(typeid)，原值(0)，新值：(3)', 1, 1603952025),
(255, 87, 1, 2130706433, 'cms_advert', 7, '超级管理员 添加了广告：AS A LEARNER, YOU CAN', 1, 1603952102),
(256, 92, 1, 2130706433, 'cms_document', 1585, '超级管理员 添加了文档：How to Register?', 1, 1603952180),
(257, 92, 1, 2130706433, 'cms_document', 1586, '超级管理员 添加了文档：Manager', 1, 1603952207),
(258, 92, 1, 2130706433, 'cms_document', 1587, '超级管理员 添加了文档：Class content', 1, 1603952229),
(259, 92, 1, 2130706433, 'cms_document', 1588, '超级管理员 添加了文档：Class content2', 1, 1603952238),
(260, 92, 1, 2130706433, 'cms_document', 1589, '超级管理员 添加了文档：Registration content', 1, 1603952254),
(261, 92, 1, 2130706433, 'cms_document', 1590, '超级管理员 添加了文档：Learner Class', 1, 1603952293),
(262, 15, 1, 2130706433, 'admin_config', 47, '超级管理员 添加了配置，详情：分组(base)、类型(text)、标题(网址)、名称(web_site_url)', 1, 1603953285),
(263, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(base)', 1, 1603953293),
(264, 16, 1, 2130706433, 'admin_config', 47, '超级管理员 编辑了配置：字段(sort)，原值(100)，新值：(1)', 1, 1603953303),
(265, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(para)', 1, 1603963863),
(266, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(para)', 1, 1603963869),
(267, 82, 1, 2130706433, 'cms_advert_type', 7, '超级管理员 添加了广告分类：学生登录页左侧广告', 1, 1603964508),
(268, 82, 1, 2130706433, 'cms_advert_type', 8, '超级管理员 添加了广告分类：老师登录页左侧广告', 1, 1603964516),
(269, 106, 1, 2130706433, 'cms_withdrawal', 1, '超级管理员 审核了提现：xueshen1提现100.00', 1, 1604067093),
(270, 30, 1, 2130706433, 'admin_menu', 365, '超级管理员 添加了节点：所属模块(cms),所属节点ID(347),节点标题(查看订单),节点链接(cms/order/detail)', 1, 1604068832),
(271, 30, 1, 2130706433, 'admin_menu', 366, '超级管理员 添加了节点：所属模块(cms),所属节点ID(237),节点标题(数据统计),节点链接()', 1, 1604365440),
(272, 30, 1, 2130706433, 'admin_menu', 367, '超级管理员 添加了节点：所属模块(cms),所属节点ID(344),节点标题(退款管理),节点链接(cms/order/refund)', 1, 1604367239),
(273, 30, 1, 2130706433, 'admin_menu', 368, '超级管理员 添加了节点：所属模块(cms),所属节点ID(366),节点标题(注册数量),节点链接(cms/chart/registers)', 1, 1604367544),
(274, 31, 1, 2130706433, 'admin_menu', 347, '超级管理员 编辑了节点：节点ID(347)', 1, 1604367736),
(275, 30, 1, 2130706433, 'admin_menu', 369, '超级管理员 添加了节点：所属模块(cms),所属节点ID(344),节点标题(支付记录),节点链接(cms/order/pays)', 1, 1604368173),
(276, 30, 1, 2130706433, 'admin_menu', 370, '超级管理员 添加了节点：所属模块(cms),所属节点ID(366),节点标题(上课终端统计),节点链接(cms/chart/terminal)', 1, 1604373126),
(277, 106, 1, 2130706433, 'cms_withdrawal', 1, '超级管理员 审核了提现：xueshen1提现100.00', 1, 1604375129),
(278, 30, 1, 2130706433, 'admin_menu', 371, '超级管理员 添加了节点：所属模块(cms),所属节点ID(366),节点标题(注册人数),节点链接(cms/chart/rens)', 1, 1604385891),
(279, 31, 1, 2130706433, 'admin_menu', 368, '超级管理员 编辑了节点：节点ID(368)', 1, 1604402961),
(280, 31, 1, 2130706433, 'admin_menu', 369, '超级管理员 编辑了节点：节点ID(369)', 1, 1604409108),
(281, 16, 1, 2130706433, 'admin_config', 42, '超级管理员 编辑了配置：原数据：分组(base)、类型(text)、标题(Tutor How to chat video)、名称(video_howtochat)', 1, 1604416589),
(282, 16, 1, 2130706433, 'admin_config', 43, '超级管理员 编辑了配置：原数据：分组(base)、类型(text)、标题(Student how to chat video)、名称(video_student)', 1, 1604416604),
(283, 16, 1, 2130706433, 'admin_config', 44, '超级管理员 编辑了配置：原数据：分组(base)、类型(text)、标题(课时费最低价格)、名称(price_class_min)', 1, 1604416619),
(284, 16, 1, 2130706433, 'admin_config', 45, '超级管理员 编辑了配置：原数据：分组(base)、类型(text)、标题(课时费最高价格)、名称(price_class_max)', 1, 1604416627),
(285, 16, 1, 2130706433, 'admin_config', 46, '超级管理员 编辑了配置：原数据：分组(base)、类型(text)、标题(课时费费率)、名称(price_class_fee)', 1, 1604416639),
(286, 15, 1, 2130706433, 'admin_config', 48, '超级管理员 添加了配置，详情：分组(para)、类型(text)、标题(上课开始前几小时可取消上课)、名称(cfg_cancel_time)', 1, 1604416811),
(287, 30, 1, 2130706433, 'admin_menu', 372, '超级管理员 添加了节点：所属模块(cms),所属节点ID(366),节点标题(活跃查询),节点链接(cms/chart/actived)', 1, 1604458860),
(288, 16, 1, 2130706433, 'admin_config', 46, '超级管理员 编辑了配置：字段(title)，原值(课时费费率)，新值：(服务费)', 1, 1604492916),
(289, 15, 1, 2130706433, 'admin_config', 49, '超级管理员 添加了配置，详情：分组(para)、类型(text)、标题(推荐码抵扣金额)、名称(cfg_invitation_fee)', 1, 1604494199),
(290, 15, 1, 2130706433, 'admin_config', 50, '超级管理员 添加了配置，详情：分组(base)、类型(text)、标题(Email)、名称(cfg_email)', 1, 1605320047),
(291, 15, 1, 2130706433, 'admin_config', 51, '超级管理员 添加了配置，详情：分组(base)、类型(text)、标题(Facebook)、名称(cfg_facebook)', 1, 1605320074),
(292, 15, 1, 2130706433, 'admin_config', 52, '超级管理员 添加了配置，详情：分组(base)、类型(text)、标题(Twitter)、名称(cfg_twitter)', 1, 1605320089),
(293, 15, 1, 2130706433, 'admin_config', 53, '超级管理员 添加了配置，详情：分组(base)、类型(image)、标题(Wechat)、名称(cfg_wechat)', 1, 1605320103),
(294, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(base)', 1, 1605320122),
(295, 91, 1, 2130706433, 'cms_document', 1585, '超级管理员 编辑了文档：How to Register?', 1, 1605321159),
(296, 70, 1, 2130706433, 'cms_field', 21, '超级管理员 删除了模型字段：详情：文档模型(文章)、字段名称(zhiwu)、字段标题(科室)、字段类型(text)', 1, 1605321220),
(297, 91, 1, 2130706433, 'cms_document', 1589, '超级管理员 编辑了文档：Registration content', 1, 1605321521),
(298, 71, 1, 2130706433, 'cms_field', 20, '超级管理员 编辑了模型字段：字段(show)，原值(1)，新值：(false)', 1, 1605321578),
(299, 71, 1, 2130706433, 'cms_field', 23, '超级管理员 编辑了模型字段：descr', 1, 1605321586),
(300, 86, 1, 2130706433, 'cms_advert', 7, '超级管理员 编辑了广告：AS A LEARNER, YOU CAN', 1, 1605323394),
(301, 86, 1, 2130706433, 'cms_advert', 4, '超级管理员 编辑了广告：TO BE A TUTOR', 1, 1605323445),
(302, 86, 1, 2130706433, 'cms_advert', 3, '超级管理员 编辑了广告：$2 CHAT WITH NATIVE ENGLISTH SPEAKER', 1, 1605323459),
(303, 86, 1, 2130706433, 'cms_advert', 4, '超级管理员 编辑了广告：TO BE A TUTOR', 1, 1605324418),
(304, 80, 1, 2130706433, 'cms_advert_type', 0, '超级管理员 删除了广告分类：首页幻灯片', 1, 1605324857),
(305, 86, 1, 2130706433, 'cms_advert', 5, '超级管理员 编辑了广告：首页顶部广告', 1, 1605325296),
(306, 86, 1, 2130706433, 'cms_advert', 6, '超级管理员 编辑了广告：AS A TUTOR, YOU CAN', 1, 1605325331),
(307, 104, 1, 2130706433, 'cms_topic', 3, '超级管理员 修改了主题：FAMILY', 1, 1605325917),
(308, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(para)', 1, 1605325999),
(309, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(upload)', 1, 1605326108),
(310, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(para)', 1, 1605326125),
(311, 16, 1, 2130706433, 'admin_config', 42, '超级管理员 编辑了配置：字段(type)，原值(file)，新值：(text)', 1, 1605326168),
(312, 16, 1, 2130706433, 'admin_config', 43, '超级管理员 编辑了配置：字段(type)，原值(file)，新值：(text)', 1, 1605326172),
(313, 15, 1, 2130706433, 'admin_config', 54, '超级管理员 添加了配置，详情：分组(para)、类型(image)、标题(教师页面视频图片)、名称(video_howtochat_pic)', 1, 1605326223),
(314, 15, 1, 2130706433, 'admin_config', 55, '超级管理员 添加了配置，详情：分组(para)、类型(image)、标题(学生页面视频图片)、名称(video_student_pic)', 1, 1605326245),
(315, 16, 1, 2130706433, 'admin_config', 42, '超级管理员 编辑了配置：字段(sort)，原值(100)，新值：(1000)', 1, 1605326256),
(316, 16, 1, 2130706433, 'admin_config', 43, '超级管理员 编辑了配置：字段(sort)，原值(100)，新值：(1001)', 1, 1605326259),
(317, 16, 1, 2130706433, 'admin_config', 54, '超级管理员 编辑了配置：字段(sort)，原值(100)，新值：(1000)', 1, 1605326263),
(318, 16, 1, 2130706433, 'admin_config', 55, '超级管理员 编辑了配置：字段(sort)，原值(100)，新值：(1001)', 1, 1605326265),
(319, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(para)', 1, 1605326321),
(320, 16, 1, 2130706433, 'admin_config', 40, '超级管理员 编辑了配置：字段(sort)，原值(100)，新值：(10000)', 1, 1605326338),
(321, 16, 1, 2130706433, 'admin_config', 41, '超级管理员 编辑了配置：字段(sort)，原值(100)，新值：(1000)', 1, 1605326341),
(322, 85, 1, 2130706433, 'cms_advert', 0, '超级管理员 删除了广告：新国家', 1, 1605326604),
(323, 86, 1, 2130706433, 'cms_advert', 2, '超级管理员 编辑了广告：新国家2', 1, 1605326626),
(324, 86, 1, 2130706433, 'cms_advert', 2, '超级管理员 编辑了广告：$2 CHAT WITH NATIVE ENGLISH SPEAKER', 1, 1605326653),
(325, 86, 1, 2130706433, 'cms_advert', 4, '超级管理员 编辑了广告：TO BE A TUTOR', 1, 1605326749),
(326, 86, 1, 2130706433, 'cms_advert', 5, '超级管理员 编辑了广告：WE BUILD A BRIDGE', 1, 1605326804),
(327, 86, 1, 2130706433, 'cms_advert', 6, '超级管理员 编辑了广告：AS A TUTOR, YOU CAN', 1, 1605326840),
(328, 86, 1, 2130706433, 'cms_advert', 6, '超级管理员 编辑了广告：字段(typeid)，原值(4)，新值：(5)', 1, 1605326867),
(329, 86, 1, 2130706433, 'cms_advert', 6, '超级管理员 编辑了广告：字段(typeid)，原值(5)，新值：(4)', 1, 1605326924),
(330, 86, 1, 2130706433, 'cms_advert', 6, '超级管理员 编辑了广告：AS A TUTOR, YOU CAN', 1, 1605326948),
(331, 87, 1, 2130706433, 'cms_advert', 8, '超级管理员 添加了广告：AS A LEARNER, YOU CAN', 1, 1605326972),
(332, 85, 1, 2130706433, 'cms_advert', 0, '超级管理员 删除了广告：AS A LEARNER, YOU CAN', 1, 1605335793),
(333, 87, 1, 2130706433, 'cms_advert', 9, '超级管理员 添加了广告：学生登录页左侧广告', 1, 1605335884),
(334, 107, 1, 2130706433, 'cms_users', 3, '超级管理员 修改了用户：teacher1', 1, 1605337818),
(335, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(para)', 1, 1605341663),
(336, 42, 1, 2130706433, 'admin_config', 0, '超级管理员 更新了系统设置：分组(para)', 1, 1605341852);

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_menu`
--

CREATE TABLE `dp_admin_menu` (
  `id` int(11) UNSIGNED NOT NULL,
  `pid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级菜单id',
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '模块名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `params` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `url_type` varchar(16) NOT NULL DEFAULT '' COMMENT '链接类型（link：外链，module：模块）',
  `url_value` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `url_target` varchar(16) NOT NULL DEFAULT '_self' COMMENT '链接打开方式：_blank,_self',
  `online_hide` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '网站上线后是否隐藏',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `system_menu` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否为系统菜单，系统菜单不可删除',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

--
-- 转存表中的数据 `dp_admin_menu`
--

INSERT INTO `dp_admin_menu` (`id`, `pid`, `module`, `title`, `icon`, `params`, `url_type`, `url_value`, `url_target`, `online_hide`, `create_time`, `update_time`, `sort`, `system_menu`, `status`) VALUES
(1, 0, 'admin', '首页', 'fa fa-fw fa-home', '', 'module_admin', 'admin/index/index', '_self', 0, 1467617722, 1477710540, 1, 1, 1),
(2, 1, 'admin', '快捷操作', 'fa fa-fw fa-folder-open-o', '', 'module_admin', '', '_self', 0, 1467618170, 1477710695, 1, 1, 1),
(3, 2, 'admin', '清空缓存', 'fa fa-fw fa-trash-o', '', 'module_admin', 'admin/index/wipecache', '_self', 0, 1467618273, 1489049773, 3, 1, 1),
(4, 0, 'admin', '系统', 'fa fa-fw fa-gear', '', 'module_admin', 'admin/system/index', '_self', 0, 1467618361, 1477710540, 2, 1, 1),
(5, 4, 'admin', '系统功能', 'si si-wrench', '', 'module_admin', '', '_self', 0, 1467618441, 1477710695, 1, 1, 1),
(6, 5, 'admin', '系统设置', 'fa fa-fw fa-wrench', '', 'module_admin', 'admin/system/index', '_self', 0, 1467618490, 1477710695, 1, 1, 1),
(7, 5, 'admin', '配置管理', 'fa fa-fw fa-gears', '', 'module_admin', 'admin/config/index', '_self', 0, 1467618618, 1477710695, 2, 1, 1),
(8, 7, 'admin', '新增', '', '', 'module_admin', 'admin/config/add', '_self', 0, 1467618648, 1477710695, 1, 1, 1),
(9, 7, 'admin', '编辑', '', '', 'module_admin', 'admin/config/edit', '_self', 0, 1467619566, 1477710695, 2, 1, 1),
(10, 7, 'admin', '删除', '', '', 'module_admin', 'admin/config/delete', '_self', 0, 1467619583, 1477710695, 3, 1, 1),
(11, 7, 'admin', '启用', '', '', 'module_admin', 'admin/config/enable', '_self', 0, 1467619609, 1477710695, 4, 1, 1),
(12, 7, 'admin', '禁用', '', '', 'module_admin', 'admin/config/disable', '_self', 0, 1467619637, 1477710695, 5, 1, 1),
(13, 5, 'admin', '节点管理', 'fa fa-fw fa-bars', '', 'module_admin', 'admin/menu/index', '_self', 0, 1467619882, 1477710695, 3, 1, 1),
(14, 13, 'admin', '新增', '', '', 'module_admin', 'admin/menu/add', '_self', 0, 1467619902, 1477710695, 1, 1, 1),
(15, 13, 'admin', '编辑', '', '', 'module_admin', 'admin/menu/edit', '_self', 0, 1467620331, 1477710695, 2, 1, 1),
(16, 13, 'admin', '删除', '', '', 'module_admin', 'admin/menu/delete', '_self', 0, 1467620363, 1477710695, 3, 1, 1),
(17, 13, 'admin', '启用', '', '', 'module_admin', 'admin/menu/enable', '_self', 0, 1467620386, 1477710695, 4, 1, 1),
(18, 13, 'admin', '禁用', '', '', 'module_admin', 'admin/menu/disable', '_self', 0, 1467620404, 1477710695, 5, 1, 1),
(19, 4, 'admin', '权限管理', 'fa fa-fw fa-key', '', 'module_admin', '', '_self', 0, 1467688065, 1601261714, 1, 1, 1),
(20, 19, 'admin', '用户管理', 'fa fa-fw fa-user', '', 'module_admin', 'user/index/index', '_self', 0, 1467688137, 1477710702, 1, 1, 1),
(21, 20, 'admin', '新增', '', '', 'module_admin', 'user/index/add', '_self', 0, 1467688177, 1477710702, 1, 1, 1),
(22, 20, 'admin', '编辑', '', '', 'module_admin', 'user/index/edit', '_self', 0, 1467688202, 1477710702, 2, 1, 1),
(23, 20, 'admin', '删除', '', '', 'module_admin', 'user/index/delete', '_self', 0, 1467688219, 1477710702, 3, 1, 1),
(24, 20, 'admin', '启用', '', '', 'module_admin', 'user/index/enable', '_self', 0, 1467688238, 1477710702, 4, 1, 1),
(25, 20, 'admin', '禁用', '', '', 'module_admin', 'user/index/disable', '_self', 0, 1467688256, 1477710702, 5, 1, 1),
(211, 64, 'admin', '日志详情', '', '', 'module_admin', 'admin/log/details', '_self', 0, 1480299320, 1480299320, 100, 0, 1),
(32, 4, 'admin', '扩展中心', 'si si-social-dropbox', '', 'module_admin', '', '_self', 0, 1467688853, 1477710695, 2, 1, 0),
(33, 32, 'admin', '模块管理', 'fa fa-fw fa-th-large', '', 'module_admin', 'admin/module/index', '_self', 0, 1467689008, 1477710695, 1, 1, 1),
(34, 33, 'admin', '导入', '', '', 'module_admin', 'admin/module/import', '_self', 0, 1467689153, 1477710695, 1, 1, 1),
(35, 33, 'admin', '导出', '', '', 'module_admin', 'admin/module/export', '_self', 0, 1467689173, 1477710695, 2, 1, 1),
(36, 33, 'admin', '安装', '', '', 'module_admin', 'admin/module/install', '_self', 0, 1467689192, 1477710695, 3, 1, 1),
(37, 33, 'admin', '卸载', '', '', 'module_admin', 'admin/module/uninstall', '_self', 0, 1467689241, 1477710695, 4, 1, 1),
(38, 33, 'admin', '启用', '', '', 'module_admin', 'admin/module/enable', '_self', 0, 1467689294, 1477710695, 5, 1, 1),
(39, 33, 'admin', '禁用', '', '', 'module_admin', 'admin/module/disable', '_self', 0, 1467689312, 1477710695, 6, 1, 1),
(40, 33, 'admin', '更新', '', '', 'module_admin', 'admin/module/update', '_self', 0, 1467689341, 1477710695, 7, 1, 1),
(41, 32, 'admin', '插件管理', 'fa fa-fw fa-puzzle-piece', '', 'module_admin', 'admin/plugin/index', '_self', 0, 1467689527, 1477710695, 2, 1, 1),
(42, 41, 'admin', '导入', '', '', 'module_admin', 'admin/plugin/import', '_self', 0, 1467689650, 1477710695, 1, 1, 1),
(43, 41, 'admin', '导出', '', '', 'module_admin', 'admin/plugin/export', '_self', 0, 1467689665, 1477710695, 2, 1, 1),
(44, 41, 'admin', '安装', '', '', 'module_admin', 'admin/plugin/install', '_self', 0, 1467689680, 1477710695, 3, 1, 1),
(45, 41, 'admin', '卸载', '', '', 'module_admin', 'admin/plugin/uninstall', '_self', 0, 1467689700, 1477710695, 4, 1, 1),
(46, 41, 'admin', '启用', '', '', 'module_admin', 'admin/plugin/enable', '_self', 0, 1467689730, 1477710695, 5, 1, 1),
(47, 41, 'admin', '禁用', '', '', 'module_admin', 'admin/plugin/disable', '_self', 0, 1467689747, 1477710695, 6, 1, 1),
(48, 41, 'admin', '设置', '', '', 'module_admin', 'admin/plugin/config', '_self', 0, 1467689789, 1477710695, 7, 1, 1),
(49, 41, 'admin', '管理', '', '', 'module_admin', 'admin/plugin/manage', '_self', 0, 1467689846, 1477710695, 8, 1, 1),
(50, 5, 'admin', '附件管理', 'fa fa-fw fa-cloud-upload', '', 'module_admin', 'admin/attachment/index', '_self', 0, 1467690161, 1477710695, 4, 1, 1),
(51, 70, 'admin', '文件上传', '', '', 'module_admin', 'admin/attachment/upload', '_self', 0, 1467690240, 1489049773, 1, 1, 1),
(52, 50, 'admin', '下载', '', '', 'module_admin', 'admin/attachment/download', '_self', 0, 1467690334, 1477710695, 2, 1, 1),
(53, 50, 'admin', '启用', '', '', 'module_admin', 'admin/attachment/enable', '_self', 0, 1467690352, 1477710695, 3, 1, 1),
(54, 50, 'admin', '禁用', '', '', 'module_admin', 'admin/attachment/disable', '_self', 0, 1467690369, 1477710695, 4, 1, 1),
(55, 50, 'admin', '删除', '', '', 'module_admin', 'admin/attachment/delete', '_self', 0, 1467690396, 1477710695, 5, 1, 1),
(56, 41, 'admin', '删除', '', '', 'module_admin', 'admin/plugin/delete', '_self', 0, 1467858065, 1477710695, 11, 1, 1),
(57, 41, 'admin', '编辑', '', '', 'module_admin', 'admin/plugin/edit', '_self', 0, 1467858092, 1477710695, 10, 1, 1),
(60, 41, 'admin', '新增', '', '', 'module_admin', 'admin/plugin/add', '_self', 0, 1467858421, 1477710695, 9, 1, 1),
(61, 41, 'admin', '执行', '', '', 'module_admin', 'admin/plugin/execute', '_self', 0, 1467879016, 1477710695, 14, 1, 1),
(62, 13, 'admin', '保存', '', '', 'module_admin', 'admin/menu/save', '_self', 0, 1468073039, 1477710695, 6, 1, 1),
(64, 5, 'admin', '系统日志', 'fa fa-fw fa-book', '', 'module_admin', 'admin/log/index', '_self', 0, 1476111944, 1477710695, 6, 0, 1),
(65, 5, 'admin', '数据库管理', 'fa fa-fw fa-database', '', 'module_admin', 'admin/database/index', '_self', 0, 1476111992, 1477710695, 8, 0, 1),
(66, 32, 'admin', '数据包管理', 'fa fa-fw fa-database', '', 'module_admin', 'admin/packet/index', '_self', 0, 1476112326, 1477710695, 4, 0, 1),
(67, 19, 'admin', '角色管理', 'fa fa-fw fa-users', '', 'module_admin', 'user/role/index', '_self', 0, 1476113025, 1477710702, 3, 0, 1),
(68, 0, 'user', '用户', 'fa fa-fw fa-user', '', 'module_admin', 'user/index/index', '_self', 0, 1476193348, 1477710540, 3, 0, 0),
(69, 32, 'admin', '钩子管理', 'fa fa-fw fa-anchor', '', 'module_admin', 'admin/hook/index', '_self', 0, 1476236193, 1477710695, 3, 0, 1),
(70, 2, 'admin', '后台首页', 'fa fa-fw fa-tachometer', '', 'module_admin', 'admin/index/index', '_self', 0, 1476237472, 1489049773, 1, 0, 1),
(71, 67, 'admin', '新增', '', '', 'module_admin', 'user/role/add', '_self', 0, 1476256935, 1477710702, 1, 0, 1),
(72, 67, 'admin', '编辑', '', '', 'module_admin', 'user/role/edit', '_self', 0, 1476256968, 1477710702, 2, 0, 1),
(73, 67, 'admin', '删除', '', '', 'module_admin', 'user/role/delete', '_self', 0, 1476256993, 1477710702, 3, 0, 1),
(74, 67, 'admin', '启用', '', '', 'module_admin', 'user/role/enable', '_self', 0, 1476257023, 1477710702, 4, 0, 1),
(75, 67, 'admin', '禁用', '', '', 'module_admin', 'user/role/disable', '_self', 0, 1476257046, 1477710702, 5, 0, 1),
(76, 20, 'admin', '授权', '', '', 'module_admin', 'user/index/access', '_self', 0, 1476375187, 1477710702, 6, 0, 1),
(77, 69, 'admin', '新增', '', '', 'module_admin', 'admin/hook/add', '_self', 0, 1476668971, 1477710695, 1, 0, 1),
(78, 69, 'admin', '编辑', '', '', 'module_admin', 'admin/hook/edit', '_self', 0, 1476669006, 1477710695, 2, 0, 1),
(79, 69, 'admin', '删除', '', '', 'module_admin', 'admin/hook/delete', '_self', 0, 1476669375, 1477710695, 3, 0, 1),
(80, 69, 'admin', '启用', '', '', 'module_admin', 'admin/hook/enable', '_self', 0, 1476669427, 1477710695, 4, 0, 1),
(81, 69, 'admin', '禁用', '', '', 'module_admin', 'admin/hook/disable', '_self', 0, 1476669564, 1477710695, 5, 0, 1),
(183, 66, 'admin', '安装', '', '', 'module_admin', 'admin/packet/install', '_self', 0, 1476851362, 1477710695, 1, 0, 1),
(184, 66, 'admin', '卸载', '', '', 'module_admin', 'admin/packet/uninstall', '_self', 0, 1476851382, 1477710695, 2, 0, 1),
(185, 5, 'admin', '行为管理', 'fa fa-fw fa-bug', '', 'module_admin', 'admin/action/index', '_self', 0, 1476882441, 1477710695, 7, 0, 1),
(186, 185, 'admin', '新增', '', '', 'module_admin', 'admin/action/add', '_self', 0, 1476884439, 1477710695, 1, 0, 1),
(187, 185, 'admin', '编辑', '', '', 'module_admin', 'admin/action/edit', '_self', 0, 1476884464, 1477710695, 2, 0, 1),
(188, 185, 'admin', '启用', '', '', 'module_admin', 'admin/action/enable', '_self', 0, 1476884493, 1477710695, 3, 0, 1),
(189, 185, 'admin', '禁用', '', '', 'module_admin', 'admin/action/disable', '_self', 0, 1476884534, 1477710695, 4, 0, 1),
(190, 185, 'admin', '删除', '', '', 'module_admin', 'admin/action/delete', '_self', 0, 1476884551, 1477710695, 5, 0, 1),
(191, 65, 'admin', '备份数据库', '', '', 'module_admin', 'admin/database/export', '_self', 0, 1476972746, 1477710695, 1, 0, 1),
(192, 65, 'admin', '还原数据库', '', '', 'module_admin', 'admin/database/import', '_self', 0, 1476972772, 1477710695, 2, 0, 1),
(193, 65, 'admin', '优化表', '', '', 'module_admin', 'admin/database/optimize', '_self', 0, 1476972800, 1477710695, 3, 0, 1),
(194, 65, 'admin', '修复表', '', '', 'module_admin', 'admin/database/repair', '_self', 0, 1476972825, 1477710695, 4, 0, 1),
(195, 65, 'admin', '删除备份', '', '', 'module_admin', 'admin/database/delete', '_self', 0, 1476973457, 1477710695, 5, 0, 1),
(210, 41, 'admin', '快速编辑', '', '', 'module_admin', 'admin/plugin/quickedit', '_self', 0, 1477713981, 1477713981, 100, 0, 1),
(209, 185, 'admin', '快速编辑', '', '', 'module_admin', 'admin/action/quickedit', '_self', 0, 1477713939, 1477713939, 100, 0, 1),
(208, 7, 'admin', '快速编辑', '', '', 'module_admin', 'admin/config/quickedit', '_self', 0, 1477713808, 1477713808, 100, 0, 1),
(207, 69, 'admin', '快速编辑', '', '', 'module_admin', 'admin/hook/quickedit', '_self', 0, 1477713770, 1477713770, 100, 0, 1),
(212, 2, 'admin', '个人设置', 'fa fa-fw fa-user', '', 'module_admin', 'admin/index/profile', '_self', 0, 1489049767, 1489049773, 2, 0, 1),
(213, 70, 'admin', '检查版本更新', '', '', 'module_admin', 'admin/index/checkupdate', '_self', 0, 1490588610, 1490588610, 100, 0, 1),
(214, 68, 'user', '消息管理', 'fa fa-fw fa-comments-o', '', 'module_admin', '', '_self', 0, 1520492129, 1520492129, 100, 0, 0),
(215, 214, 'user', '消息列表', 'fa fa-fw fa-th-list', '', 'module_admin', 'user/message/index', '_self', 0, 1520492195, 1520492195, 100, 0, 1),
(216, 215, 'user', '新增', '', '', 'module_admin', 'user/message/add', '_self', 0, 1520492195, 1520492195, 100, 0, 1),
(217, 215, 'user', '编辑', '', '', 'module_admin', 'user/message/edit', '_self', 0, 1520492195, 1520492195, 100, 0, 1),
(218, 215, 'user', '删除', '', '', 'module_admin', 'user/message/delete', '_self', 0, 1520492195, 1520492195, 100, 0, 1),
(219, 215, 'user', '启用', '', '', 'module_admin', 'user/message/enable', '_self', 0, 1520492195, 1520492195, 100, 0, 1),
(220, 215, 'user', '禁用', '', '', 'module_admin', 'user/message/disable', '_self', 0, 1520492195, 1520492195, 100, 0, 1),
(221, 215, 'user', '快速编辑', '', '', 'module_admin', 'user/message/quickedit', '_self', 0, 1520492195, 1520492195, 100, 0, 1),
(222, 2, 'admin', '消息中心', 'fa fa-fw fa-comments-o', '', 'module_admin', 'admin/message/index', '_self', 0, 1520495992, 1520496254, 100, 0, 1),
(223, 222, 'admin', '删除', '', '', 'module_admin', 'admin/message/delete', '_self', 0, 1520495992, 1520496263, 100, 0, 1),
(224, 222, 'admin', '启用', '', '', 'module_admin', 'admin/message/enable', '_self', 0, 1520495992, 1520496270, 100, 0, 1),
(225, 32, 'admin', '图标管理', 'fa fa-fw fa-tint', '', 'module_admin', 'admin/icon/index', '_self', 0, 1520908295, 1520908295, 100, 0, 1),
(226, 225, 'admin', '新增', '', '', 'module_admin', 'admin/icon/add', '_self', 0, 1520908295, 1520908295, 100, 0, 1),
(227, 225, 'admin', '编辑', '', '', 'module_admin', 'admin/icon/edit', '_self', 0, 1520908295, 1520908295, 100, 0, 1),
(228, 225, 'admin', '删除', '', '', 'module_admin', 'admin/icon/delete', '_self', 0, 1520908295, 1520908295, 100, 0, 1),
(229, 225, 'admin', '启用', '', '', 'module_admin', 'admin/icon/enable', '_self', 0, 1520908295, 1520908295, 100, 0, 1),
(230, 225, 'admin', '禁用', '', '', 'module_admin', 'admin/icon/disable', '_self', 0, 1520908295, 1520908295, 100, 0, 1),
(231, 225, 'admin', '快速编辑', '', '', 'module_admin', 'admin/icon/quickedit', '_self', 0, 1520908295, 1520908295, 100, 0, 1),
(232, 225, 'admin', '图标列表', '', '', 'module_admin', 'admin/icon/items', '_self', 0, 1520923368, 1520923368, 100, 0, 1),
(233, 225, 'admin', '更新图标', '', '', 'module_admin', 'admin/icon/reload', '_self', 0, 1520931908, 1520931908, 100, 0, 1),
(234, 20, 'admin', '快速编辑', '', '', 'module_admin', 'user/index/quickedit', '_self', 0, 1526028258, 1526028258, 100, 0, 1),
(235, 67, 'admin', '快速编辑', '', '', 'module_admin', 'user/role/quickedit', '_self', 0, 1526028282, 1526028282, 100, 0, 1),
(236, 6, 'admin', '快速编辑', '', '', 'module_admin', 'admin/system/quickedit', '_self', 0, 1559054310, 1559054310, 100, 0, 1),
(237, 0, 'cms', '门户', 'fa fa-fw fa-newspaper-o', '', 'module_admin', 'cms/index/index', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(238, 237, 'cms', '常用操作', 'fa fa-fw fa-folder-open-o', '', 'module_admin', '', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(345, 344, 'cms', '学生管理', 'fa fa-fw fa-user', '', 'module_admin', 'cms/users/index', '_self', 0, 1601261837, 1601261837, 100, 0, 1),
(240, 238, 'cms', '发布文档', 'fa fa-fw fa-plus', '', 'module_admin', 'cms/document/add', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(241, 238, 'cms', '文档列表', 'fa fa-fw fa-list', '', 'module_admin', 'cms/document/index', '_self', 0, 1598021962, 1601278875, 1, 0, 1),
(242, 241, 'cms', '编辑', '', '', 'module_admin', 'cms/document/edit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(243, 241, 'cms', '删除', '', '', 'module_admin', 'cms/document/delete', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(244, 241, 'cms', '启用', '', '', 'module_admin', 'cms/document/enable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(245, 241, 'cms', '禁用', '', '', 'module_admin', 'cms/document/disable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(246, 241, 'cms', '快速编辑', '', '', 'module_admin', 'cms/document/quickedit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(247, 238, 'cms', '单页管理', 'fa fa-fw fa-file-word-o', '', 'module_admin', 'cms/page/index', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(248, 247, 'cms', '新增', '', '', 'module_admin', 'cms/page/add', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(249, 247, 'cms', '编辑', '', '', 'module_admin', 'cms/page/edit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(250, 247, 'cms', '删除', '', '', 'module_admin', 'cms/page/delete', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(251, 247, 'cms', '启用', '', '', 'module_admin', 'cms/page/enable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(252, 247, 'cms', '禁用', '', '', 'module_admin', 'cms/page/disable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(253, 247, 'cms', '快速编辑', '', '', 'module_admin', 'cms/page/quickedit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(254, 238, 'cms', '回收站', 'fa fa-fw fa-recycle', '', 'module_admin', 'cms/recycle/index', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(255, 254, 'cms', '删除', '', '', 'module_admin', 'cms/recycle/delete', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(256, 254, 'cms', '还原', '', '', 'module_admin', 'cms/recycle/restore', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(257, 237, 'cms', '内容管理', 'fa fa-fw fa-th-list', '', 'module_admin', '', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(351, 348, 'admin', '删除时区', '', '', 'module_admin', 'cms/location/delete', '_self', 0, 1603418671, 1603418671, 100, 0, 1),
(259, 257, 'cms', '广告管理', 'fa fa-fw fa-handshake-o', '', 'module_admin', 'cms/advert/index', '_self', 0, 1598021962, 1598066390, 100, 0, 1),
(260, 259, 'cms', '新增', '', '', 'module_admin', 'cms/advert/add', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(261, 259, 'cms', '编辑', '', '', 'module_admin', 'cms/advert/edit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(262, 259, 'cms', '删除', '', '', 'module_admin', 'cms/advert/delete', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(263, 259, 'cms', '启用', '', '', 'module_admin', 'cms/advert/enable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(264, 259, 'cms', '禁用', '', '', 'module_admin', 'cms/advert/disable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(265, 259, 'cms', '快速编辑', '', '', 'module_admin', 'cms/advert/quickedit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(266, 259, 'cms', '广告分类', '', '', 'module_admin', 'cms/advert_type/index', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(267, 266, 'cms', '新增', '', '', 'module_admin', 'cms/advert_type/add', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(268, 266, 'cms', '编辑', '', '', 'module_admin', 'cms/advert_type/edit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(269, 266, 'cms', '删除', '', '', 'module_admin', 'cms/advert_type/delete', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(270, 266, 'cms', '启用', '', '', 'module_admin', 'cms/advert_type/enable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(271, 266, 'cms', '禁用', '', '', 'module_admin', 'cms/advert_type/disable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(272, 266, 'cms', '快速编辑', '', '', 'module_admin', 'cms/advert_type/quickedit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(350, 348, 'admin', '修改时区', '', '', 'module_admin', 'cms/location/edit', '_self', 0, 1603418634, 1603418634, 100, 0, 1),
(349, 348, 'admin', '增加时区', '', '', 'module_admin', 'cms/location/add', '_self', 0, 1603418612, 1603418612, 100, 0, 1),
(348, 5, 'admin', '时区管理', 'fa fa-fw fa-cog', '', 'module_admin', 'cms/location/index', '_self', 0, 1603416431, 1603416461, 100, 0, 1),
(347, 344, 'cms', '上课管理', 'fa fa-fw fa-th-list', '', 'module_admin', 'cms/order/index', '_self', 0, 1601262043, 1604367736, 100, 0, 1),
(280, 257, 'cms', '友情链接', 'fa fa-fw fa-link', '', 'module_admin', 'cms/link/index', '_self', 0, 1598021962, 1598068385, 100, 0, 1),
(281, 280, 'cms', '新增', '', '', 'module_admin', 'cms/link/add', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(282, 280, 'cms', '编辑', '', '', 'module_admin', 'cms/link/edit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(283, 280, 'cms', '删除', '', '', 'module_admin', 'cms/link/delete', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(284, 280, 'cms', '启用', '', '', 'module_admin', 'cms/link/enable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(285, 280, 'cms', '禁用', '', '', 'module_admin', 'cms/link/disable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(286, 280, 'cms', '快速编辑', '', '', 'module_admin', 'cms/link/quickedit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(294, 237, 'cms', '门户设置', 'fa fa-fw fa-sliders', '', 'module_admin', '', '_self', 0, 1598021962, 1598021962, 100, 0, 0),
(295, 257, 'cms', '栏目分类', 'fa fa-fw fa-sitemap', '', 'module_admin', 'cms/column/index', '_self', 1, 1598021962, 1598066407, 100, 0, 1),
(296, 295, 'cms', '新增', '', '', 'module_admin', 'cms/column/add', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(297, 295, 'cms', '编辑', '', '', 'module_admin', 'cms/column/edit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(298, 295, 'cms', '删除', '', '', 'module_admin', 'cms/column/delete', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(299, 295, 'cms', '启用', '', '', 'module_admin', 'cms/column/enable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(300, 295, 'cms', '禁用', '', '', 'module_admin', 'cms/column/disable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(301, 295, 'cms', '快速编辑', '', '', 'module_admin', 'cms/column/quickedit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(302, 294, 'cms', '内容模型', 'fa fa-fw fa-th-large', '', 'module_admin', 'cms/model/index', '_self', 0, 1598021962, 1598021962, 100, 0, 0),
(303, 302, 'cms', '新增', '', '', 'module_admin', 'cms/model/add', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(304, 302, 'cms', '编辑', '', '', 'module_admin', 'cms/model/edit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(305, 302, 'cms', '删除', '', '', 'module_admin', 'cms/model/delete', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(306, 302, 'cms', '启用', '', '', 'module_admin', 'cms/model/enable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(307, 302, 'cms', '禁用', '', '', 'module_admin', 'cms/model/disable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(308, 302, 'cms', '快速编辑', '', '', 'module_admin', 'cms/model/quickedit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(309, 302, 'cms', '字段管理', '', '', 'module_admin', 'cms/field/index', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(310, 309, 'cms', '新增', '', '', 'module_admin', 'cms/field/add', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(311, 309, 'cms', '编辑', '', '', 'module_admin', 'cms/field/edit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(312, 309, 'cms', '删除', '', '', 'module_admin', 'cms/field/delete', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(313, 309, 'cms', '启用', '', '', 'module_admin', 'cms/field/enable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(314, 309, 'cms', '禁用', '', '', 'module_admin', 'cms/field/disable', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(315, 309, 'cms', '快速编辑', '', '', 'module_admin', 'cms/field/quickedit', '_self', 0, 1598021962, 1598021962, 100, 0, 1),
(360, 355, 'cms', '修改老师', '', '', 'module_admin', 'cms/teacher/edit', '_self', 0, 1603634333, 1603634333, 100, 0, 1),
(359, 355, 'cms', '添加老师', '', '', 'module_admin', 'cms/teacher/add', '_self', 0, 1603634303, 1603634303, 100, 0, 1),
(358, 354, 'cms', '删除主题', '', '', 'module_admin', 'cms/topic/delete', '_self', 0, 1603633359, 1603633359, 100, 0, 1),
(357, 354, 'cms', '修改主题', '', '', 'module_admin', 'cms/topic/edit', '_self', 0, 1603633314, 1603633314, 100, 0, 1),
(356, 354, 'cms', '添加主题', '', '', 'module_admin', 'cms/topic/add', '_self', 0, 1603633293, 1603633293, 100, 0, 1),
(355, 238, 'cms', '推荐老师', 'fa fa-fw fa-th-list', '', 'module_admin', 'cms/teacher/index', '_self', 0, 1603629759, 1603629759, 100, 0, 1),
(354, 238, 'cms', '谈话主题', 'fa fa-fw fa-th-list', '', 'module_admin', 'cms/topic/index', '_self', 0, 1603629727, 1603629727, 100, 0, 1),
(353, 344, 'cms', '捐赠记录', 'fa fa-fw fa-th-list', '', 'module_admin', 'cms/donation/index', '_self', 0, 1603511301, 1603511301, 100, 0, 1),
(352, 345, 'cms', '修改会员', '', '', 'module_admin', 'cms/users/edit', '_self', 0, 1603444512, 1603444681, 100, 0, 1),
(330, 257, 'cms', '文章', 'fa fa-fw fa-list', '', 'module_admin', 'cms/content/article', '_self', 0, 1598022004, 1598022004, 100, 0, 1),
(363, 362, 'cms', '查看提现', '', '', 'module_admin', 'cms/withdrawal/detail', '_self', 0, 1603637587, 1603637587, 100, 0, 1),
(362, 344, 'cms', '提现申请', 'fa fa-fw fa-th-list', '', 'module_admin', 'cms/withdrawal/index', '_self', 0, 1603637540, 1603637540, 100, 0, 1),
(361, 355, 'cms', '删除老师', '', '', 'module_admin', 'cms/teacher/delete', '_self', 0, 1603634351, 1603634351, 100, 0, 1),
(346, 344, 'cms', '教师管理', 'fa fa-fw fa-male', '', 'module_admin', 'cms/users/teacher', '_self', 0, 1601261883, 1601261883, 100, 0, 1),
(344, 237, 'cms', '会员管理', 'fa fa-fw fa-user', '', 'module_admin', '', '_self', 0, 1601261760, 1601261930, 100, 0, 1),
(364, 362, 'cms', '审核提现', '', '', 'module_admin', 'cms/withdrawal/check', '_self', 0, 1603637602, 1603637602, 100, 0, 1),
(365, 347, 'cms', '查看订单', '', '', 'module_admin', 'cms/order/detail', '_self', 0, 1604068832, 1604068832, 100, 0, 1),
(366, 237, 'cms', '数据统计', 'fa fa-fw fa-th-large', '', 'module_admin', '', '_self', 0, 1604365440, 1604365440, 100, 0, 1),
(367, 344, 'cms', '退款管理', 'fa fa-fw fa-th-list', '', 'module_admin', 'cms/order/refund', '_self', 0, 1604367239, 1604367239, 100, 0, 1),
(368, 366, 'cms', '支付统计', '', '', 'module_admin', 'cms/chart/moneys', '_self', 0, 1604367544, 1604402961, 100, 0, 1),
(369, 344, 'cms', '支付记录', 'fa fa-fw fa-th-list', '', 'module_admin', 'cms/order/pays', '_self', 0, 1604368173, 1604409108, 100, 0, 1),
(370, 366, 'cms', '上课终端统计', '', '', 'module_admin', 'cms/chart/terminal', '_self', 0, 1604373126, 1604373126, 100, 0, 1),
(371, 366, 'cms', '注册人数', '', '', 'module_admin', 'cms/chart/rens', '_self', 0, 1604385891, 1604385891, 100, 0, 1),
(372, 366, 'cms', '活跃查询', '', '', 'module_admin', 'cms/chart/actived', '_self', 0, 1604458860, 1604458860, 100, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_message`
--

CREATE TABLE `dp_admin_message` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uid_receive` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '接收消息的用户id',
  `uid_send` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '发送消息的用户id',
  `type` varchar(128) NOT NULL DEFAULT '' COMMENT '消息分类',
  `content` text NOT NULL COMMENT '消息内容',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `read_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '阅读时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息表';

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_module`
--

CREATE TABLE `dp_admin_module` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '模块名称（标识）',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '模块标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `description` text NOT NULL COMMENT '描述',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `config` text COMMENT '配置信息',
  `access` text COMMENT '授权配置',
  `version` varchar(16) NOT NULL DEFAULT '' COMMENT '版本号',
  `identifier` varchar(64) NOT NULL DEFAULT '' COMMENT '模块唯一标识符',
  `system_module` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否为系统模块',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模块表';

--
-- 转存表中的数据 `dp_admin_module`
--

INSERT INTO `dp_admin_module` (`id`, `name`, `title`, `icon`, `description`, `author`, `author_url`, `config`, `access`, `version`, `identifier`, `system_module`, `create_time`, `update_time`, `sort`, `status`) VALUES
(1, 'admin', '系统', 'fa fa-fw fa-gear', '系统模块，DolphinPHP的核心模块', 'DolphinPHP', 'http://www.dolphinphp.com', '', '', '1.0.0', 'admin.dolphinphp.module', 1, 1468204902, 1468204902, 100, 1),
(2, 'user', '用户', 'fa fa-fw fa-user', '用户模块，DolphinPHP自带模块', 'DolphinPHP', 'http://www.dolphinphp.com', '', '', '1.0.0', 'user.dolphinphp.module', 1, 1468204902, 1468204902, 100, 1),
(3, 'cms', '门户', 'fa fa-fw fa-newspaper-o', '门户模块', 'CaiWeiMing', 'http://www.dolphinphp.com', '{\"__token__\":\"ec7d17c5a6ab2f0965fb54ac151bb540\",\"summary\":\"0\",\"contact\":\"\",\"meta_head\":\"\",\"meta_foot\":\"\",\"support_status\":\"1\",\"support_color\":\"rgba(0,158,232,1)\",\"support_wx\":\"\",\"support_extra\":\"\"}', '{\"column\":{\"title\":\"\\u680f\\u76ee\\u6388\\u6743\",\"nodes\":{\"group\":\"column\",\"table_name\":\"cms_column\",\"primary_key\":\"id\",\"parent_id\":\"pid\",\"node_name\":\"name\"}}}', '1.0.0', 'cms.ming.module', 0, 1598021962, 1598021962, 100, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_packet`
--

CREATE TABLE `dp_admin_packet` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '数据包名',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '数据包标题',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者url',
  `version` varchar(16) NOT NULL,
  `tables` text NOT NULL COMMENT '数据表名',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='数据包表';

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_plugin`
--

CREATE TABLE `dp_admin_plugin` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '插件名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '插件标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `description` text NOT NULL COMMENT '插件描述',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `config` text NOT NULL COMMENT '配置信息',
  `version` varchar(16) NOT NULL DEFAULT '' COMMENT '版本号',
  `identifier` varchar(64) NOT NULL DEFAULT '' COMMENT '插件唯一标识符',
  `admin` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有后台管理',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';

--
-- 转存表中的数据 `dp_admin_plugin`
--

INSERT INTO `dp_admin_plugin` (`id`, `name`, `title`, `icon`, `description`, `author`, `author_url`, `config`, `version`, `identifier`, `admin`, `create_time`, `update_time`, `sort`, `status`) VALUES
(1, 'SystemInfo', '系统环境信息', 'fa fa-fw fa-info-circle', '在后台首页显示服务器信息', '蔡伟明', 'http://www.caiweiming.com', '{\"display\":\"1\",\"width\":\"6\"}', '1.0.0', 'system_info.ming.plugin', 0, 1477757503, 1477757503, 100, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_role`
--

CREATE TABLE `dp_admin_role` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '角色id',
  `pid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级角色',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '角色名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '角色描述',
  `menu_auth` text NOT NULL COMMENT '菜单权限',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `access` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否可登录后台',
  `default_module` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '默认访问模块'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色表';

--
-- 转存表中的数据 `dp_admin_role`
--

INSERT INTO `dp_admin_role` (`id`, `pid`, `name`, `description`, `menu_auth`, `sort`, `create_time`, `update_time`, `status`, `access`, `default_module`) VALUES
(1, 0, '超级管理员', '系统默认创建的角色，拥有最高权限', '', 0, 1476270000, 1468117612, 1, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `dp_admin_user`
--

CREATE TABLE `dp_admin_user` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(96) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `email_bind` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否绑定邮箱地址',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `mobile_bind` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否绑定手机号码',
  `avatar` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '头像',
  `money` decimal(11,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '积分',
  `role` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '角色ID',
  `group` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '部门id',
  `signup_ip` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '注册ip',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `last_login_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `last_login_ip` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录ip',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态：0禁用，1启用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `dp_admin_user`
--

INSERT INTO `dp_admin_user` (`id`, `username`, `nickname`, `password`, `email`, `email_bind`, `mobile`, `mobile_bind`, `avatar`, `money`, `score`, `role`, `group`, `signup_ip`, `create_time`, `update_time`, `last_login_time`, `last_login_ip`, `sort`, `status`) VALUES
(1, 'admin', '超级管理员', '$2y$10$Brw6wmuSLIIx3Yabid8/Wu5l8VQ9M/H/CG3C9RqN9dUCwZW3ljGOK', '', 0, '', 0, 0, '0.00', 0, 1, 0, 0, 1476065410, 1605357280, 1605357280, 2130706433, 100, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_account_log`
--

CREATE TABLE `dp_cms_account_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `type_id` tinyint(4) DEFAULT '0',
  `obj_id` int(11) DEFAULT '0',
  `money` decimal(10,2) DEFAULT '0.00',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='账户流水';

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_account_type`
--

CREATE TABLE `dp_cms_account_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `type_name` varchar(40) DEFAULT NULL,
  `type_name_cn` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `dp_cms_account_type`
--

INSERT INTO `dp_cms_account_type` (`id`, `type_name`, `type_name_cn`) VALUES
(1, 'Booking course', '订单支付'),
(2, 'Donation', '捐赠'),
(3, 'Refund', '退款'),
(4, 'Withdrawal', '提现'),
(5, 'Tuition fees', '课时费'),
(6, 'Invite teacher members', '邀请教师');

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_advert`
--

CREATE TABLE `dp_cms_advert` (
  `id` int(11) UNSIGNED NOT NULL,
  `typeid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分类id',
  `tagname` varchar(30) NOT NULL DEFAULT '' COMMENT '广告位标识',
  `ad_type` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '广告类型',
  `timeset` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '时间限制:0-永不过期,1-在设内时间内有效',
  `start_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '结束时间',
  `name` varchar(300) NOT NULL DEFAULT '' COMMENT '广告位名称',
  `content` text NOT NULL COMMENT '广告内容',
  `expcontent` text COMMENT '过期显示内容',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态',
  `link` varchar(255) DEFAULT NULL,
  `src` varchar(255) DEFAULT NULL,
  `src_wap` varchar(255) DEFAULT NULL,
  `sort` int(255) DEFAULT '100',
  `thumb` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告表';

--
-- 转存表中的数据 `dp_cms_advert`
--

INSERT INTO `dp_cms_advert` (`id`, `typeid`, `tagname`, `ad_type`, `timeset`, `start_time`, `end_time`, `name`, `content`, `expcontent`, `create_time`, `update_time`, `status`, `link`, `src`, `src_wap`, `sort`, `thumb`) VALUES
(3, 2, 'for_learner', 2, 0, 0, 0, '$2 CHAT WITH NATIVE ENGLISTH SPEAKER', 'Chat with native English speaker(US, UK, Canda,)\r\nImprove your speaking fase\r\nCorrect your errors that an examiner doesn\'t tel you', NULL, 1603627976, 1605323460, 1, 'http://127.0.0.35/', '369', '372', 100, 'uploads/images/20201114/f7fe176638eada48bf0a1d2f2a41d47d.jpg'),
(2, 1, 'iscover', 2, 0, 0, 0, '$2 CHAT WITH NATIVE ENGLISH SPEAKER', 'Chat with native English speaker (US, UK, Canada, Australia, New Zealand)\r\nImprove your speaking fast\r\nCorrect your errors that an examiner doesn’t tell you\r\nChat with your partner as your parent teach your language\r\nLowest spending cost, starts from $2', NULL, 1598157978, 1605326653, 1, 'http://127.0.0.35/', '369', '372', 1, 'uploads/images/20201114/f7fe176638eada48bf0a1d2f2a41d47d.jpg'),
(4, 2, 'for_tutor', 2, 0, 0, 0, 'TO BE A TUTOR', '1 on 1 chat with worldwide young people to know exotic cultures, stories\r\nTouch the world on your own way\r\nShare your precious life experience with them\r\nFind fun and enrich your life\r\nHelp your partner improve speaking English skill\r\nGet respect from your partner\r\nGet reward for your time', NULL, 1603628173, 1605326749, 1, 'http://127.0.0.35/', '371', '370', 0, 'uploads/images/20201114/36b76798a3b419a650dadb4674b7f58e.jpg'),
(5, 3, 'index_top_ad', 2, 0, 0, 0, 'WE BUILD A BRIDGE', 'Our vision is to build a bridge for worldwide people\r\nto communicate better in English.', NULL, 1603951605, 1605326804, 1, 'http://127.0.0.35/', '367', '368', 1, 'uploads/images/20201114/a11537e75be186595472b1585f636c7f.jpg'),
(6, 4, 'index_tutor', 2, 0, 0, 0, 'AS A TUTOR, YOU CAN', '1 on 1 chat with worldwide young people to know exotic cultures, stories\r\nTouch the world on your own way\r\nShare your precious life experience with them\r\nFind fun and enrich your life\r\nHelp your partner improve speaking English skill\r\nGet respect from your partner\r\nGet reward for your time', NULL, 1603952006, 1605326948, 1, '', '', '', 0, NULL),
(9, 6, 'learner_login', 2, 0, 0, 0, '学生登录页左侧广告', '', NULL, 1605335885, 1605335885, 1, '', '378', '', 0, 'uploads/images/20201114/d441cdb17655f42fcf1e5b16dc1c0096.png'),
(8, 5, 'learner_about', 2, 0, 0, 0, 'AS A LEARNER, YOU CAN', 'Chat with native English speaker (US, UK, Canada, Australia, New Zealand)\r\nImprove your speaking fast\r\nCorrect your errors that an examiner doesn’t tell you\r\nChat with your partner as your parent teach your language', NULL, 1605326972, 1605326972, 1, '', '', '', 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_advert_type`
--

CREATE TABLE `dp_cms_advert_type` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '分类名称',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告分类表';

--
-- 转存表中的数据 `dp_cms_advert_type`
--

INSERT INTO `dp_cms_advert_type` (`id`, `name`, `create_time`, `update_time`, `status`) VALUES
(1, '学生页顶部广告', 1603627809, 1603627809, 1),
(2, '教师页顶部广告', 1603628132, 1603628132, 1),
(3, '首页顶部', 1603951447, 1603951447, 1),
(4, '首页Tutor介绍', 1603951838, 1603951838, 1),
(5, '首页Learner介绍', 1603951859, 1603951859, 1),
(6, '学生登录页左侧广告', 1603964508, 1603964508, 1),
(7, '老师登录页左侧广告', 1603964516, 1603964516, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_availables`
--

CREATE TABLE `dp_cms_availables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `op_date` date DEFAULT NULL COMMENT '上课日期',
  `time_begin` time DEFAULT NULL COMMENT '开始时间',
  `time_end` time DEFAULT NULL COMMENT '结束时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='老师可上课时间';

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_cart`
--

CREATE TABLE `dp_cms_cart` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '学生id',
  `teacher_id` int(11) DEFAULT NULL COMMENT '教师id',
  `daytime_id` bigint(11) DEFAULT NULL COMMENT '教师时间段id',
  `book_date` date DEFAULT NULL COMMENT '上课日期',
  `time_begin` time DEFAULT NULL COMMENT '开始时间',
  `time_end` time DEFAULT NULL COMMENT '结束时间',
  `book_date_teacher` date DEFAULT NULL COMMENT '老师上课日期',
  `time_begin_teacher` time DEFAULT NULL COMMENT '老师开始时间',
  `time_end_teacher` time DEFAULT NULL COMMENT '老师结束时间',
  `time_zone` int(11) DEFAULT NULL COMMENT '0时区时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车';

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_column`
--

CREATE TABLE `dp_cms_column` (
  `id` int(11) UNSIGNED NOT NULL,
  `pid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级id',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `type_id` tinyint(1) DEFAULT '0',
  `model` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文档模型id',
  `url` varchar(255) DEFAULT '' COMMENT '链接',
  `target` varchar(16) DEFAULT '_self' COMMENT '链接打开方式',
  `content` text COMMENT '内容',
  `icon` varchar(64) DEFAULT '' COMMENT '字体图标',
  `index_template` varchar(32) DEFAULT '' COMMENT '封面模板',
  `list_template` varchar(32) DEFAULT '' COMMENT '列表页模板',
  `detail_template` varchar(32) DEFAULT '' COMMENT '详情页模板',
  `post_auth` tinyint(2) UNSIGNED DEFAULT '0' COMMENT '投稿权限',
  `create_time` int(11) UNSIGNED DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) UNSIGNED DEFAULT '0' COMMENT '状态',
  `hide` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `rank_auth` int(11) NOT NULL DEFAULT '0' COMMENT '浏览权限，-1待审核，0为开放浏览，大于0则为对应的用户角色id',
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '栏目属性：0-最终列表栏目，1-外部链接，2-频道封面',
  `thumb` int(11) DEFAULT NULL,
  `descr` varchar(300) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目表';

--
-- 转存表中的数据 `dp_cms_column`
--

INSERT INTO `dp_cms_column` (`id`, `pid`, `name`, `type_id`, `model`, `url`, `target`, `content`, `icon`, `index_template`, `list_template`, `detail_template`, `post_auth`, `create_time`, `update_time`, `sort`, `status`, `hide`, `rank_auth`, `type`, `thumb`, `descr`) VALUES
(227, 0, 'Tutor', 0, 1, '', '_self', '', '', '', '', '', 0, 1603636172, 1603636242, 100, 1, 0, 0, 0, 0, ''),
(228, 227, 'Registration', 0, 1, '', '_self', '', '', '', '', '', 0, 1603636186, 1603636261, 100, 1, 0, 0, 0, 0, ''),
(229, 227, 'Class', 0, 1, '', '_self', '', '', '', '', '', 0, 1603636215, 1603636268, 100, 1, 0, 0, 0, 0, ''),
(230, 0, 'LEARNER', 0, 1, '', '_self', '', '', '', '', '', 0, 1603636279, 1603636279, 100, 1, 0, 0, 0, 0, ''),
(231, 230, 'Registration', 0, 1, '', '_self', '', '', '', '', '', 0, 1603636304, 1603636304, 100, 1, 0, 0, 0, 0, ''),
(232, 230, 'Class', 0, 1, '', '_self', '', '', '', '', '', 0, 1603636311, 1603636311, 100, 1, 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_daytime`
--

CREATE TABLE `dp_cms_daytime` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL COMMENT '教师id',
  `datetime` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='上课时间';

--
-- 转存表中的数据 `dp_cms_daytime`
--

INSERT INTO `dp_cms_daytime` (`id`, `user_id`, `datetime`, `date`, `time`) VALUES
(1, 3, 1602266400, '2020-10-10', '10:00'),
(2, 3, 1602270000, '2020-10-10', '11:00'),
(3, 3, 1602280800, '2020-10-10', '14:00'),
(4, 4, 1602293400, '2020-10-10', '14:00'),
(5, 4, 1602300600, '2020-10-10', '16:00'),
(6, 4, 1602304200, '2020-10-10', '17:00');

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_device`
--

CREATE TABLE `dp_cms_device` (
  `id` int(10) UNSIGNED NOT NULL,
  `sn` varchar(20) DEFAULT NULL,
  `device` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `dp_cms_device`
--

INSERT INTO `dp_cms_device` (`id`, `sn`, `device`) VALUES
(1, '0x00', 'PC'),
(2, '0x01', 'iphone'),
(3, '0x02', 'ipad'),
(4, '0x03', 'web客户端'),
(5, '0x04', 'Android手机'),
(6, '0x05', 'Android平板'),
(7, '0x06', 'Android电视');

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_document`
--

CREATE TABLE `dp_cms_document` (
  `id` int(11) UNSIGNED NOT NULL,
  `cid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '栏目id',
  `model` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文档模型ID',
  `title` varchar(256) NOT NULL DEFAULT '' COMMENT '标题',
  `shorttitle` varchar(32) NOT NULL DEFAULT '' COMMENT '简略标题',
  `keywords` varchar(255) DEFAULT NULL,
  `descr` text,
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `flag` set('j','p','b','s','a','f','c','h') DEFAULT NULL COMMENT '自定义属性',
  `view` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '阅读量',
  `comment` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论数',
  `good` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点赞数',
  `thumb` varchar(255) DEFAULT NULL,
  `bad` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '踩数',
  `mark` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '收藏数量',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态',
  `trash` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '回收站'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档基础表';

--
-- 转存表中的数据 `dp_cms_document`
--

INSERT INTO `dp_cms_document` (`id`, `cid`, `model`, `title`, `shorttitle`, `keywords`, `descr`, `uid`, `flag`, `view`, `comment`, `good`, `thumb`, `bad`, `mark`, `create_time`, `update_time`, `sort`, `status`, `trash`) VALUES
(1585, 228, 1, 'How to Register?', '', NULL, 'Registration guide\r\nfirst step Description text Description textDescription textDescription textDescription textDescription textDescription textDescription textDescription textDescription text Second step Description textDescription textDescription textDescription textDescription textDescription textDescription text\r\nthird step Description textDescription textDescription textDescription textDescription textDescription textDescription textDescription textDescription text', 1, '', 0, 0, 0, '0', 0, 0, 1603952180, 1605321159, 100, 1, 0),
(1586, 228, 1, 'Manager', '', NULL, '', 1, '', 0, 0, 0, '', 0, 0, 1603952208, 1603952208, 100, 1, 0),
(1587, 229, 1, 'Class content', '', NULL, '', 1, '', 0, 0, 0, '', 0, 0, 1603952229, 1603952229, 100, 1, 0),
(1588, 229, 1, 'Class content2', '', NULL, '', 1, '', 0, 0, 0, '', 0, 0, 1603952238, 1603952238, 100, 1, 0),
(1589, 231, 1, 'Registration content', '', NULL, 'Registration guide22\r\nfirst step Description text Description textDescription textDescription textDescription textDescription textDescription textDescription textDescription textDescription text Second step Description textDescription textDescription textDescription textDescription textDescription textDescription text\r\nthird step Description textDescription textDescription textDescription textDescription textDescription textDescription textDescription textDescription text', 1, '', 0, 0, 0, '0', 0, 0, 1603952254, 1605321521, 100, 1, 0),
(1590, 232, 1, 'Learner Class', '', NULL, '', 1, '', 0, 0, 0, '', 0, 0, 1603952293, 1603952293, 100, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_document_article`
--

CREATE TABLE `dp_cms_document_article` (
  `aid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文档id',
  `thumb` int(11) UNSIGNED DEFAULT NULL COMMENT '图片',
  `content` text COMMENT '内容',
  `descr` varchar(500) DEFAULT NULL COMMENT '内容',
  `source` varchar(128) DEFAULT NULL COMMENT '来源',
  `writer` varchar(128) DEFAULT NULL COMMENT '作者'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章模型扩展表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `dp_cms_document_article`
--

INSERT INTO `dp_cms_document_article` (`aid`, `thumb`, `content`, `descr`, `source`, `writer`) VALUES
(1585, 0, '<p>Registration guide</p>\r\n\r\n<p>first step Description text De step Description text De step Description text De step Description text De step Description text De</p>\r\n\r\n<p>step Description text De</p>\r\n\r\n<p>step Description text De step Description text De step Description text De step Description text De</p>\r\n', 'Registration guide\r\nfirst step Description text Description textDescription textDescription textDescription textDescription textDescription textDescription textDescription textDescription text Second step Description textDescription textDescription textDescription textDescription textDescription textDescription text\r\nthird step Description textDescription textDescription textDescription textDescription textDescription textDescription textDescription textDescription text', '', ''),
(1586, 0, '<p>当地时间28日晚，法国总统马克龙宣布，包括海外领土在内，法国全境从10月30日起再度封城，以应对新冠肺炎疫情的迅猛反弹。根据马克龙宣布的内容，防疫力度小于今年春季的封城措施。(总台记者 贾延宁)</p>\r\n\r\n<p>更多资讯或合作欢迎关注中国经济网官方微信（名称：中国经济网，id：ourcecn）</p>\r\n\r\n<p>来源：央视新闻客户端</p>\r\n', '', '', ''),
(1587, 0, '<p>Class content&nbsp;Class content</p>\r\n\r\n<p>Class content</p>\r\n', '', '', ''),
(1588, 0, '<p>Class content2&nbsp;Class content2</p>\r\n\r\n<p>Class content2</p>\r\n', '', '', ''),
(1589, 0, '<p>Registration content&nbsp;Registration content</p>\r\n\r\n<p>Registration content</p>\r\n', 'Registration guide22\r\nfirst step Description text Description textDescription textDescription textDescription textDescription textDescription textDescription textDescription textDescription text Second step Description textDescription textDescription textDescription textDescription textDescription textDescription text\r\nthird step Description textDescription textDescription textDescription textDescription textDescription textDescription textDescription textDescription text', '', ''),
(1590, 0, '<p>Learner Class&nbsp;Learner Class&nbsp;Learner Class</p>\r\n\r\n<p>Learner Class</p>\r\n', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_donation`
--

CREATE TABLE `dp_cms_donation` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `order_amount` decimal(10,2) DEFAULT '0.00',
  `user_money` decimal(10,2) DEFAULT '0.00' COMMENT '账户余额支付',
  `pay_money` decimal(10,2) DEFAULT NULL COMMENT '在线支付金额',
  `pay_type` tinyint(1) DEFAULT '0' COMMENT '支付方式，1Paypal,2微信,3支付宝',
  `order_status` tinyint(1) DEFAULT '1',
  `pay_status` tinyint(1) DEFAULT '0',
  `pay_time` int(11) DEFAULT NULL,
  `pay_note` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `pay_error` tinyint(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='捐赠';

--
-- 转存表中的数据 `dp_cms_donation`
--

INSERT INTO `dp_cms_donation` (`id`, `user_id`, `order_amount`, `user_money`, `pay_money`, `pay_type`, `order_status`, `pay_status`, `pay_time`, `pay_note`, `create_time`, `update_time`, `pay_error`) VALUES
(1, 1, '10.00', '0.00', '10.00', 1, 1, 1, 1603626860, '12341341123', 1603626860, 1603626860, 0);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_feedback`
--

CREATE TABLE `dp_cms_feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `email` varchar(60) NOT NULL,
  `body` text,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `create_ip` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_field`
--

CREATE TABLE `dp_cms_field` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '字段名称',
  `name` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '字段标题',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '字段类型',
  `define` varchar(128) NOT NULL DEFAULT '' COMMENT '字段定义',
  `value` text COMMENT '默认值',
  `options` text COMMENT '额外选项',
  `tips` varchar(256) NOT NULL DEFAULT '' COMMENT '提示说明',
  `fixed` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否为固定字段',
  `show` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否显示',
  `model` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属文档模型id',
  `ajax_url` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框ajax地址',
  `next_items` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框的下级下拉框名，多个以逗号隔开',
  `param` varchar(32) NOT NULL DEFAULT '' COMMENT '联动下拉框请求参数名',
  `format` varchar(32) NOT NULL DEFAULT '' COMMENT '格式，用于格式文本',
  `table` varchar(32) NOT NULL DEFAULT '' COMMENT '表名，只用于快速联动类型',
  `level` tinyint(2) UNSIGNED NOT NULL DEFAULT '2' COMMENT '联动级别，只用于快速联动类型',
  `key` varchar(32) NOT NULL DEFAULT '' COMMENT '键字段，只用于快速联动类型',
  `option` varchar(32) NOT NULL DEFAULT '' COMMENT '值字段，只用于快速联动类型',
  `pid` varchar(32) NOT NULL DEFAULT '' COMMENT '父级id字段，只用于快速联动类型',
  `ak` varchar(32) NOT NULL DEFAULT '' COMMENT '百度地图appkey',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档字段表';

--
-- 转存表中的数据 `dp_cms_field`
--

INSERT INTO `dp_cms_field` (`id`, `name`, `title`, `type`, `define`, `value`, `options`, `tips`, `fixed`, `show`, `model`, `ajax_url`, `next_items`, `param`, `format`, `table`, `level`, `key`, `option`, `pid`, `ak`, `create_time`, `update_time`, `sort`, `status`) VALUES
(1, 'id', 'ID', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', 'ID', 0, 0, 0, '', '', '', '', '', 0, '', '', '', '', 1480562978, 1480562978, 100, 1),
(2, 'cid', '栏目', 'select', 'int(11) UNSIGNED NOT NULL', '0', '', '请选择所属栏目', 0, 0, 0, '', '', '', '', '', 0, '', '', '', '', 1480562978, 1480562978, 100, 1),
(3, 'uid', '用户ID', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', 0, 0, 0, '', '', '', '', '', 0, '', '', '', '', 1480563110, 1480563110, 100, 1),
(4, 'model', '模型ID', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', 0, 0, 0, '', '', '', '', '', 0, '', '', '', '', 1480563110, 1480563110, 100, 1),
(5, 'title', '标题', 'text', 'varchar(128) NOT NULL', '', '', '文档标题', 0, 1, 0, '', '', '', '', '', 0, '', '', '', '', 1480575844, 1480576134, 1, 1),
(6, 'shorttitle', '简略标题/职称', 'text', 'varchar(32) NOT NULL', '', '', '简略标题', 0, 1, 0, '', '', '', '', '', 0, '', '', '', '', 1480575844, 1480576134, 1, 1),
(7, 'flag', '自定义属性', 'checkbox', 'set(\'j\',\'p\',\'b\',\'s\',\'a\',\'f\',\'h\',\'c\') NULL DEFAULT NULL', '', 's:滚动\r\nf:幻灯\r\nc:推荐', '自定义属性', 0, 1, 0, '', '', '', '', '', 0, '', '', '', '', 1480671258, 1480671258, 100, 1),
(8, 'view', '阅读量', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', 0, 1, 0, '', '', '', '', '', 0, '', '', '', '', 1480563149, 1480563149, 100, 1),
(9, 'comment', '评论数', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', 0, 0, 0, '', '', '', '', '', 0, '', '', '', '', 1480563189, 1480563189, 100, 1),
(10, 'good', '点赞数', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', 0, 0, 0, '', '', '', '', '', 0, '', '', '', '', 1480563279, 1480563279, 100, 1),
(11, 'bad', '踩数', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', 0, 0, 0, '', '', '', '', '', 0, '', '', '', '', 1480563330, 1480563330, 100, 1),
(12, 'mark', '收藏数量', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', 0, 0, 0, '', '', '', '', '', 0, '', '', '', '', 1480563372, 1480563372, 100, 1),
(13, 'create_time', '创建时间', 'datetime', 'int(11) UNSIGNED NOT NULL', '0', '', '', 0, 0, 0, '', '', '', '', '', 0, '', '', '', '', 1480563406, 1480563406, 100, 1),
(14, 'update_time', '更新时间', 'datetime', 'int(11) UNSIGNED NOT NULL', '0', '', '', 0, 0, 0, '', '', '', '', '', 0, '', '', '', '', 1480563432, 1480563432, 100, 1),
(15, 'sort', '排序', 'text', 'int(11) NOT NULL', '100', '', '', 0, 1, 0, '', '', '', '', '', 0, '', '', '', '', 1480563510, 1480563510, 100, 1),
(16, 'status', '状态', 'radio', 'tinyint(2) UNSIGNED NOT NULL', '1', '0:禁用\r\n1:启用', '', 0, 1, 0, '', '', '', '', '', 0, '', '', '', '', 1480563576, 1480563576, 100, 1),
(17, 'trash', '回收站', 'text', 'tinyint(2) UNSIGNED NOT NULL', '0', '', '', 0, 0, 0, '', '', '', '', '', 0, '', '', '', '', 1480563576, 1480563576, 100, 1),
(18, 'thumb', '图片', 'image', 'int(11) UNSIGNED NULL', '', '', '', 1, 1, 1, '', '', '', '', '', 0, '', '', '', '', 1598022644, 1598022644, 100, 1),
(23, 'descr', '内容', 'textarea', 'varchar(500) NULL', '', '', '', 0, 1, 1, '', '', '', '', '', 0, '', '', '', '', 1599403196, 1605321586, 100, 1),
(20, 'content', '内容', 'ckeditor', 'text NULL', '', '', '', 0, 0, 1, '', '', '', '', '', 0, '', '', '', '', 1598022707, 1599398453, 1000, 1),
(24, 'source', '来源', 'text', 'varchar(128) NULL', '', '', '', 0, 1, 1, '', '', '', '', '', 0, '', '', '', '', 1599404875, 1599404875, 100, 1),
(25, 'writer', '作者', 'text', 'varchar(128) NULL', '', '', '', 0, 1, 1, '', '', '', '', '', 0, '', '', '', '', 1599404896, 1599404896, 100, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_link`
--

CREATE TABLE `dp_cms_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '类型：1-文字链接，2-图片链接',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '链接标题',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `logo` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '链接LOGO',
  `contact` varchar(255) NOT NULL DEFAULT '' COMMENT '联系方式',
  `sort` int(11) NOT NULL DEFAULT '100',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='有钱链接表';

--
-- 转存表中的数据 `dp_cms_link`
--

INSERT INTO `dp_cms_link` (`id`, `type`, `title`, `url`, `logo`, `contact`, `sort`, `status`, `create_time`, `update_time`) VALUES
(1, 1, '邵阳市卫生局', 'https://www.163.com/', 0, '', 100, 1, 1598068469, 1598068469),
(2, 1, '湖南卫生厅', 'http://beijing.wangzhanhr.com', 0, '', 100, 1, 1598068480, 1598068480),
(3, 2, 'OA办公', 'http://www.syyyy.cn:83/', 0, '', 100, 1, 1598068574, 1598068574);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_location`
--

CREATE TABLE `dp_cms_location` (
  `id` int(10) UNSIGNED NOT NULL,
  `location_name` varchar(30) DEFAULT NULL COMMENT '时区名称',
  `ico` int(255) DEFAULT NULL COMMENT '图标',
  `ico_file` varchar(255) DEFAULT NULL,
  `timezone` decimal(10,1) DEFAULT NULL COMMENT '和0时区相差多少小时',
  `code` varchar(20) DEFAULT NULL COMMENT '时区代码',
  `status` tinyint(1) DEFAULT '1',
  `is_delete` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='时区，国家';

--
-- 转存表中的数据 `dp_cms_location`
--

INSERT INTO `dp_cms_location` (`id`, `location_name`, `ico`, `ico_file`, `timezone`, `code`, `status`, `is_delete`) VALUES
(1, 'Caracas', NULL, NULL, '-4.0', 'America/Caracas', 1, 0),
(2, 'Samoa', NULL, NULL, '-11.0', 'Pacific/Apia', 1, 0),
(3, 'Beijing', NULL, NULL, '8.0', 'Asia/Beijing', 1, 0),
(4, 'Kabul', NULL, NULL, '4.5', 'Asia/Kabul', 1, 0),
(5, 'Cairo', NULL, NULL, '2.0', 'Africa/Cairo', 1, 0),
(6, 'English', NULL, NULL, '6.0', 'Europe/Paris', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_menu`
--

CREATE TABLE `dp_cms_menu` (
  `id` int(11) UNSIGNED NOT NULL,
  `nid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '导航id',
  `pid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级id',
  `column` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '栏目id',
  `page` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '单页id',
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型：0-栏目链接，1-单页链接，2-自定义链接',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `css` varchar(64) NOT NULL DEFAULT '' COMMENT 'css类',
  `rel` varchar(64) NOT NULL DEFAULT '' COMMENT '链接关系网',
  `target` varchar(16) NOT NULL DEFAULT '' COMMENT '打开方式',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='菜单表';

--
-- 转存表中的数据 `dp_cms_menu`
--

INSERT INTO `dp_cms_menu` (`id`, `nid`, `pid`, `column`, `page`, `type`, `title`, `url`, `css`, `rel`, `target`, `create_time`, `update_time`, `sort`, `status`) VALUES
(1, 1, 0, 0, 0, 2, '首页', 'cms/index/index', '', '', '_self', 1492345605, 1492345605, 100, 1),
(2, 2, 0, 0, 0, 2, '关于我们', 'http://www.dolphinphp.com', '', '', '_self', 1492346763, 1492346763, 100, 1),
(3, 3, 0, 0, 0, 2, '开发文档', 'http://www.kancloud.cn/ming5112/dolphinphp', '', '', '_self', 1492346812, 1492346812, 100, 1),
(4, 3, 0, 0, 0, 2, '开发者社区', 'http://bbs.dolphinphp.com/', '', '', '_self', 1492346832, 1492346832, 100, 1),
(5, 1, 0, 0, 0, 2, '二级菜单', 'http://www.dolphinphp.com', '', '', '_self', 1492347372, 1492347510, 100, 1),
(6, 1, 5, 0, 0, 2, '子菜单', 'http://www.dolphinphp.com', '', '', '_self', 1492347388, 1492347520, 100, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_model`
--

CREATE TABLE `dp_cms_model` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '模型名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '模型标题',
  `table` varchar(64) NOT NULL DEFAULT '' COMMENT '附加表名称',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '模型类别：0-系统模型，1-普通模型，2-独立模型',
  `icon` varchar(64) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `system` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否系统模型',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='内容模型表';

--
-- 转存表中的数据 `dp_cms_model`
--

INSERT INTO `dp_cms_model` (`id`, `name`, `title`, `table`, `type`, `icon`, `sort`, `system`, `create_time`, `update_time`, `status`) VALUES
(1, 'article', '文章', 'dp_cms_document_article', 0, 'fa fa-fw fa-th-list', 100, 0, 1598022003, 1598022003, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_nav`
--

CREATE TABLE `dp_cms_nav` (
  `id` int(11) UNSIGNED NOT NULL,
  `tag` varchar(32) NOT NULL DEFAULT '' COMMENT '导航标识',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='导航表';

--
-- 转存表中的数据 `dp_cms_nav`
--

INSERT INTO `dp_cms_nav` (`id`, `tag`, `title`, `create_time`, `update_time`, `status`) VALUES
(1, 'main_nav', '顶部导航', 1492345083, 1492345083, 1),
(2, 'about_nav', '底部关于', 1492346685, 1492346685, 1),
(3, 'support_nav', '服务与支持', 1492346715, 1492346715, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_notice`
--

CREATE TABLE `dp_cms_notice` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='站内消息';

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_order`
--

CREATE TABLE `dp_cms_order` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_sn` varchar(20) DEFAULT NULL COMMENT '订单编号',
  `user_id` bigint(20) DEFAULT NULL COMMENT '学生id',
  `teacher_id` bigint(20) DEFAULT NULL COMMENT '教师id',
  `order_amount` decimal(10,2) DEFAULT '0.00' COMMENT '订单总金额',
  `fees` decimal(10,2) DEFAULT NULL COMMENT '总费率',
  `class_num` int(11) DEFAULT '0' COMMENT '课时数',
  `user_money` decimal(10,2) DEFAULT '0.00' COMMENT '账户余额支付',
  `invitation_money` decimal(10,2) DEFAULT NULL COMMENT '邀请码抵扣',
  `invitation_code` varchar(20) DEFAULT NULL COMMENT '邀请码',
  `pay_money` decimal(10,2) DEFAULT NULL COMMENT '在线支付金额',
  `pay_type` tinyint(1) DEFAULT '0' COMMENT '支付方式，1paypal,2微信,3支付宝',
  `order_status` tinyint(1) DEFAULT '0' COMMENT '订单状态',
  `pay_status` tinyint(1) DEFAULT '0' COMMENT '支付状态',
  `pay_time` int(11) DEFAULT NULL COMMENT '支付时间',
  `pay_note` varchar(255) DEFAULT NULL COMMENT '支付备注',
  `create_time` int(11) DEFAULT NULL COMMENT '下单时间',
  `update_time` int(11) DEFAULT NULL,
  `pay_error` tinyint(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

--
-- 转存表中的数据 `dp_cms_order`
--

INSERT INTO `dp_cms_order` (`id`, `order_sn`, `user_id`, `teacher_id`, `order_amount`, `fees`, `class_num`, `user_money`, `invitation_money`, `invitation_code`, `pay_money`, `pay_type`, `order_status`, `pay_status`, `pay_time`, `pay_note`, `create_time`, `update_time`, `pay_error`) VALUES
(1, NULL, 1, 3, '100.00', NULL, 0, '0.00', NULL, NULL, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_orderitems`
--

CREATE TABLE `dp_cms_orderitems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '学生id',
  `teacher_id` int(11) DEFAULT NULL COMMENT '教师id',
  `order_id` bigint(20) DEFAULT NULL,
  `daytime_id` bigint(11) DEFAULT NULL COMMENT '教师时间段id',
  `money` decimal(10,2) DEFAULT NULL COMMENT '最终金额，减去邀请码金额',
  `fee` decimal(10,2) DEFAULT NULL COMMENT '服务费',
  `teacher_price` decimal(10,2) DEFAULT NULL COMMENT '老师价格',
  `invitation_fee` decimal(10,2) DEFAULT NULL COMMENT '邀请码抵扣',
  `datetime` int(11) DEFAULT NULL COMMENT '服务器时间戳',
  `book_date` date DEFAULT NULL COMMENT '上课日期',
  `time_begin` time DEFAULT NULL COMMENT '开始时间',
  `time_end` time DEFAULT NULL COMMENT '结束时间',
  `book_date_teacher` date DEFAULT NULL COMMENT '老师上课日期',
  `time_begin_teacher` time DEFAULT NULL COMMENT '老师开始时间',
  `time_end_teacher` time DEFAULT NULL COMMENT '老师结束时间',
  `order_status` tinyint(255) DEFAULT '1' COMMENT '状态',
  `create_time` int(11) DEFAULT NULL,
  `cancel_time` int(11) DEFAULT NULL COMMENT '取消时间',
  `cancel_type` tinyint(1) DEFAULT '0' COMMENT '1学生取消，2老师取消',
  `success_time` int(11) DEFAULT NULL COMMENT '完成时间',
  `classin_id` int(11) DEFAULT NULL COMMENT '课节id',
  `live_url` varchar(255) DEFAULT NULL,
  `live_rtmp` varchar(255) DEFAULT NULL,
  `live_hls` varchar(255) DEFAULT NULL,
  `live_flv` varchar(255) DEFAULT NULL,
  `teacher_device` varchar(60) DEFAULT NULL COMMENT '老师上课的终端系统',
  `student_device` varchar(60) DEFAULT NULL COMMENT '学生上课的终端系统',
  `comment_student` varchar(255) DEFAULT NULL COMMENT '学生评价',
  `score_student` tinyint(1) DEFAULT '0' COMMENT '学生评分',
  `comment_student_time` int(11) DEFAULT '0' COMMENT '学生评价时间',
  `comment_teacher` varchar(255) DEFAULT NULL COMMENT '老师评价',
  `score_teacher` tinyint(1) DEFAULT '0' COMMENT '老师评分',
  `comment_teacher_time` int(11) DEFAULT '0' COMMENT '老师评价时间',
  `pay_error` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='上课明细表';

--
-- 转存表中的数据 `dp_cms_orderitems`
--

INSERT INTO `dp_cms_orderitems` (`id`, `user_id`, `teacher_id`, `order_id`, `daytime_id`, `money`, `fee`, `teacher_price`, `invitation_fee`, `datetime`, `book_date`, `time_begin`, `time_end`, `book_date_teacher`, `time_begin_teacher`, `time_end_teacher`, `order_status`, `create_time`, `cancel_time`, `cancel_type`, `success_time`, `classin_id`, `live_url`, `live_rtmp`, `live_hls`, `live_flv`, `teacher_device`, `student_device`, `comment_student`, `score_student`, `comment_student_time`, `comment_teacher`, `score_teacher`, `comment_teacher_time`, `pay_error`) VALUES
(1, 1, 3, 1, NULL, '13.00', '3.00', '10.00', NULL, 1604064730, '2020-10-30', '11:30:00', '12:00:00', '2020-10-30', '10:30:00', '11:00:00', 1, 1601777669, 1604456069, 0, NULL, NULL, NULL, NULL, NULL, NULL, '0x00', NULL, NULL, NULL, 0, NULL, 0, 0, 0),
(2, 1, 3, 1, NULL, '13.00', '3.00', '10.00', NULL, 1603064730, '2020-11-15', '11:30:00', '12:00:00', '2020-10-30', '10:30:00', '11:00:00', 3, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '0x01', NULL, NULL, NULL, 0, NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_order_status`
--

CREATE TABLE `dp_cms_order_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `status_name` varchar(40) DEFAULT NULL,
  `status_name_cn` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单状态';

--
-- 转存表中的数据 `dp_cms_order_status`
--

INSERT INTO `dp_cms_order_status` (`id`, `status_name`, `status_name_cn`) VALUES
(1, 'Wait', '待上课'),
(2, 'Undone', '未完成'),
(3, 'Completed', '已完成'),
(4, 'Cancelled', '已取消');

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_page`
--

CREATE TABLE `dp_cms_page` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '单页标题',
  `content` mediumtext NOT NULL COMMENT '单页内容',
  `keywords` varchar(32) DEFAULT '' COMMENT '关键词',
  `description` text COMMENT '页面描述',
  `template` varchar(32) DEFAULT '' COMMENT '模板文件',
  `cover` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '单页封面',
  `cover_wap` int(255) DEFAULT NULL,
  `view` int(11) UNSIGNED DEFAULT '0' COMMENT '阅读量',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单页表';

--
-- 转存表中的数据 `dp_cms_page`
--

INSERT INTO `dp_cms_page` (`id`, `title`, `content`, `keywords`, `description`, `template`, `cover`, `cover_wap`, `view`, `create_time`, `update_time`, `status`) VALUES
(1, 'About us', '<p>This is a start-up program, founded by a group of caring Canadians to help retired seniors and overseas youth to setup a fabulous connection. We hope all seniors could find more fun here. Chat with overseas youth who has dream to study or work abroad. Share your precious life experience with them and know what their real life is. Explore the world from your own perspective by chatting with worldwide young people directly. Different culture, different background, different story&hellip; Helping overseas youth improve their speaking English skill is our second vision. Talking with these nice seniors, learn not only English as well their rich life experience.</p>\r\n', '', 'WE HOPE ALL SENIORS COULD \r\nFIND MORE FUN HERE', '', 379, 380, 0, 1603636891, 1605340192, 1),
(2, 'Terms of use', '<p>Terms of use</p>\r\n\r\n<p>Terms of use</p>\r\n', '', 'Terms of use', '', 0, 0, 0, 1605340378, 1605340378, 1),
(3, 'PRIVACY POLICY', '<p>PRIVACY POLICY</p>\r\n\r\n<p>PRIVACY POLICY</p>\r\n', '', 'PRIVACY POLICY\r\nPRIVACY POLICY', '', 0, 0, 0, 1605340394, 1605340394, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_payment`
--

CREATE TABLE `dp_cms_payment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `pay_type` tinyint(1) DEFAULT NULL COMMENT '1paypal，2微信，3支付宝',
  `type` tinyint(1) DEFAULT '1' COMMENT '1订单,2捐款',
  `obj_id` bigint(20) DEFAULT NULL COMMENT '订单号',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '支付金额',
  `money2` decimal(10,2) DEFAULT NULL COMMENT '人民币金额',
  `create_time` int(11) DEFAULT NULL,
  `pay_time` int(11) DEFAULT NULL,
  `pay_status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付记录';

--
-- 转存表中的数据 `dp_cms_payment`
--

INSERT INTO `dp_cms_payment` (`id`, `user_id`, `pay_type`, `type`, `obj_id`, `money`, `money2`, `create_time`, `pay_time`, `pay_status`) VALUES
(1, 1, 1, 1, 1, '1000.00', NULL, 1603444190, NULL, 1),
(2, 1, 2, 1, 2, '2000.00', NULL, 1604405923, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_slider`
--

CREATE TABLE `dp_cms_slider` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '标题',
  `cover` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '封面id',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='滚动图片表';

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_support`
--

CREATE TABLE `dp_cms_support` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '客服名称',
  `qq` varchar(16) NOT NULL DEFAULT '' COMMENT 'QQ',
  `msn` varchar(100) NOT NULL DEFAULT '' COMMENT 'msn',
  `taobao` varchar(100) NOT NULL DEFAULT '' COMMENT 'taobao',
  `alibaba` varchar(100) NOT NULL DEFAULT '' COMMENT 'alibaba',
  `skype` varchar(100) NOT NULL DEFAULT '' COMMENT 'skype',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态',
  `sort` int(11) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客服表';

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_teacher`
--

CREATE TABLE `dp_cms_teacher` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `headpic` int(11) NOT NULL,
  `headpic_file` varchar(255) DEFAULT NULL,
  `country_ico` int(11) NOT NULL,
  `country_file` varchar(255) DEFAULT NULL,
  `star` decimal(3,1) DEFAULT NULL,
  `sort` int(11) DEFAULT '100',
  `price` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='老师推荐';

--
-- 转存表中的数据 `dp_cms_teacher`
--

INSERT INTO `dp_cms_teacher` (`id`, `name`, `headpic`, `headpic_file`, `country_ico`, `country_file`, `star`, `sort`, `price`, `create_time`, `status`) VALUES
(1, 'Account', 376, 'uploads/images/20201114/528379b1dda26621f975491cb3cf10cd.png', 377, 'uploads/images/20201114/d8dd5164a19cac39b9aa683e1a028423.png', '5.0', 100, 10, 1605327146, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_topic`
--

CREATE TABLE `dp_cms_topic` (
  `id` int(11) UNSIGNED NOT NULL,
  `topic_name` varchar(60) DEFAULT NULL,
  `descr` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `sort` int(255) DEFAULT '100',
  `create_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐谈话主题';

--
-- 转存表中的数据 `dp_cms_topic`
--

INSERT INTO `dp_cms_topic` (`id`, `topic_name`, `descr`, `status`, `sort`, `create_time`) VALUES
(2, 'FOOD', 'Cook,Style,Falvor', 1, 1000, 1603633820),
(3, 'FAMILY', 'Members,Relatives,Connection,\r\nMarrage,Generation gap', 1, 100, 1603633841);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_users`
--

CREATE TABLE `dp_cms_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(40) DEFAULT NULL COMMENT '昵称',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机',
  `password` varchar(80) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL COMMENT '邮箱',
  `email_verify` tinyint(1) DEFAULT '0' COMMENT '邮箱验证状态',
  `email_verify_code` varchar(100) DEFAULT NULL COMMENT '邮箱验证码',
  `user_type` tinyint(1) DEFAULT '1' COMMENT '用户身份，1学生，2老师',
  `classin_uid` int(50) DEFAULT NULL COMMENT 'classin用户id',
  `course_id` int(11) DEFAULT NULL COMMENT 'classin课程id',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '单价',
  `star` decimal(4,1) DEFAULT '5.0' COMMENT '打分',
  `native_language` tinyint(4) DEFAULT NULL COMMENT '母语',
  `english_level` tinyint(4) DEFAULT NULL COMMENT '英语级别',
  `learn_years` varchar(10) DEFAULT NULL COMMENT '学习年数',
  `goals` varchar(255) DEFAULT NULL COMMENT '学习目标',
  `location` varchar(30) DEFAULT NULL COMMENT '时区',
  `moneys` decimal(10,2) DEFAULT '0.00' COMMENT '账户余额',
  `invitation_code` varchar(20) DEFAULT NULL COMMENT '推荐码',
  `invitation_code_buy` tinyint(1) DEFAULT '0' COMMENT '邀请码购买，1已使用',
  `descr` text COMMENT '自我介绍',
  `parent_id` bigint(20) DEFAULT NULL COMMENT '推荐人',
  `paypal_account` varchar(60) DEFAULT NULL COMMENT 'paypal提现账号',
  `status` tinyint(1) DEFAULT '1' COMMENT '1正常，0禁用',
  `class_num` int(11) DEFAULT '0' COMMENT '上课课时数',
  `create_time` int(255) DEFAULT NULL COMMENT '注册时间',
  `create_ip` varchar(20) DEFAULT NULL COMMENT '注册ip',
  `update_time` int(11) DEFAULT NULL,
  `login_time` int(11) DEFAULT NULL COMMENT '最后登录时间',
  `login_ip` varchar(20) DEFAULT NULL COMMENT '最后登录ip',
  `last_login_time` int(11) DEFAULT NULL COMMENT '上次登录时间',
  `last_login_ip` varchar(20) DEFAULT NULL COMMENT '上次登录ip'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表';

--
-- 转存表中的数据 `dp_cms_users`
--

INSERT INTO `dp_cms_users` (`id`, `username`, `mobile`, `password`, `email`, `email_verify`, `email_verify_code`, `user_type`, `classin_uid`, `course_id`, `price`, `star`, `native_language`, `english_level`, `learn_years`, `goals`, `location`, `moneys`, `invitation_code`, `invitation_code_buy`, `descr`, `parent_id`, `paypal_account`, `status`, `class_num`, `create_time`, `create_ip`, `update_time`, `login_time`, `login_ip`, `last_login_time`, `last_login_ip`) VALUES
(1, 'xueshen1', '15512341243', '15123412', '15512341243@qq.com', 0, NULL, 1, NULL, NULL, '0.00', NULL, 1, 2, '3', NULL, '1', '0.00', NULL, 0, NULL, NULL, NULL, 1, 0, 1603444190, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'xueshen2', '15812341243', '$2y$10$Siu08myFyEuOjpdHLmaDSegltPmfBqeyRNoFuGfr6GbpvT1OKe4Nu', '15812341243@qq.com', 0, NULL, 1, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, '2', '0.00', NULL, 0, NULL, 2, NULL, 1, 0, 1603468800, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'teacher1', '17712341234', '$2y$10$0wttnKxtpnybJOurkGB4G.I0XKO4TuamPpeHcDh.XNPC59xo5DOna', '17712341234@qq.com', 0, NULL, 2, NULL, NULL, '8.00', NULL, NULL, NULL, NULL, NULL, '3', '0.00', NULL, 0, NULL, 1, NULL, 1, 0, 1571846400, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'teacher2', '17712341235', '$2y$10$3EMju3OPG3QWbIgw4U1HR.wkQOdF/ndvG6.SBlm6WLnm5Vmpz8kT.', '17712341235@qq.com', 0, NULL, 2, NULL, NULL, '9.00', NULL, NULL, NULL, NULL, NULL, '4', '0.00', NULL, 0, NULL, NULL, NULL, 1, 0, 1603444190, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'teacher3', '17712341236', NULL, '17712341236@qq.com', 0, NULL, 2, NULL, NULL, '12.00', NULL, NULL, NULL, NULL, NULL, '5', '0.00', NULL, 0, NULL, NULL, NULL, 1, 0, 1603444190, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `dp_cms_withdrawal`
--

CREATE TABLE `dp_cms_withdrawal` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '申请会员',
  `money` decimal(10,2) DEFAULT NULL COMMENT '提现金额',
  `account` varchar(50) DEFAULT NULL COMMENT '提现账号',
  `state` tinyint(1) DEFAULT '0' COMMENT '完成状态，0待审核，1已完成，2失败',
  `create_time` int(11) DEFAULT NULL COMMENT '申请时间',
  `success_time` int(11) DEFAULT NULL COMMENT '完成时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现';

--
-- 转存表中的数据 `dp_cms_withdrawal`
--

INSERT INTO `dp_cms_withdrawal` (`id`, `user_id`, `money`, `account`, `state`, `create_time`, `success_time`) VALUES
(1, 1, '100.00', '1234124@qq.com', 1, 1604064730, 1604375129);

--
-- 转储表的索引
--

--
-- 表的索引 `dp_admin_action`
--
ALTER TABLE `dp_admin_action`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_attachment`
--
ALTER TABLE `dp_admin_attachment`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_config`
--
ALTER TABLE `dp_admin_config`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_hook`
--
ALTER TABLE `dp_admin_hook`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_hook_plugin`
--
ALTER TABLE `dp_admin_hook_plugin`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_icon`
--
ALTER TABLE `dp_admin_icon`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_icon_list`
--
ALTER TABLE `dp_admin_icon_list`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_log`
--
ALTER TABLE `dp_admin_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action_ip_ix` (`action_ip`),
  ADD KEY `action_id_ix` (`action_id`),
  ADD KEY `user_id_ix` (`user_id`);

--
-- 表的索引 `dp_admin_menu`
--
ALTER TABLE `dp_admin_menu`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_message`
--
ALTER TABLE `dp_admin_message`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_module`
--
ALTER TABLE `dp_admin_module`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_packet`
--
ALTER TABLE `dp_admin_packet`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_plugin`
--
ALTER TABLE `dp_admin_plugin`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_role`
--
ALTER TABLE `dp_admin_role`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_admin_user`
--
ALTER TABLE `dp_admin_user`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_account_log`
--
ALTER TABLE `dp_cms_account_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type_id` (`type_id`);

--
-- 表的索引 `dp_cms_account_type`
--
ALTER TABLE `dp_cms_account_type`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_advert`
--
ALTER TABLE `dp_cms_advert`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_advert_type`
--
ALTER TABLE `dp_cms_advert_type`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_availables`
--
ALTER TABLE `dp_cms_availables`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_cart`
--
ALTER TABLE `dp_cms_cart`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_column`
--
ALTER TABLE `dp_cms_column`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_daytime`
--
ALTER TABLE `dp_cms_daytime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- 表的索引 `dp_cms_device`
--
ALTER TABLE `dp_cms_device`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_document`
--
ALTER TABLE `dp_cms_document`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_document_article`
--
ALTER TABLE `dp_cms_document_article`
  ADD PRIMARY KEY (`aid`);

--
-- 表的索引 `dp_cms_donation`
--
ALTER TABLE `dp_cms_donation`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_feedback`
--
ALTER TABLE `dp_cms_feedback`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_field`
--
ALTER TABLE `dp_cms_field`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_link`
--
ALTER TABLE `dp_cms_link`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_location`
--
ALTER TABLE `dp_cms_location`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_menu`
--
ALTER TABLE `dp_cms_menu`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_model`
--
ALTER TABLE `dp_cms_model`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_nav`
--
ALTER TABLE `dp_cms_nav`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_notice`
--
ALTER TABLE `dp_cms_notice`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_order`
--
ALTER TABLE `dp_cms_order`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_orderitems`
--
ALTER TABLE `dp_cms_orderitems`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_order_status`
--
ALTER TABLE `dp_cms_order_status`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_page`
--
ALTER TABLE `dp_cms_page`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_payment`
--
ALTER TABLE `dp_cms_payment`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_slider`
--
ALTER TABLE `dp_cms_slider`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_support`
--
ALTER TABLE `dp_cms_support`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_teacher`
--
ALTER TABLE `dp_cms_teacher`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_topic`
--
ALTER TABLE `dp_cms_topic`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_users`
--
ALTER TABLE `dp_cms_users`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dp_cms_withdrawal`
--
ALTER TABLE `dp_cms_withdrawal`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `dp_admin_action`
--
ALTER TABLE `dp_admin_action`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- 使用表AUTO_INCREMENT `dp_admin_attachment`
--
ALTER TABLE `dp_admin_attachment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=381;

--
-- 使用表AUTO_INCREMENT `dp_admin_config`
--
ALTER TABLE `dp_admin_config`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- 使用表AUTO_INCREMENT `dp_admin_hook`
--
ALTER TABLE `dp_admin_hook`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- 使用表AUTO_INCREMENT `dp_admin_hook_plugin`
--
ALTER TABLE `dp_admin_hook_plugin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `dp_admin_icon`
--
ALTER TABLE `dp_admin_icon`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `dp_admin_icon_list`
--
ALTER TABLE `dp_admin_icon_list`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `dp_admin_log`
--
ALTER TABLE `dp_admin_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=337;

--
-- 使用表AUTO_INCREMENT `dp_admin_menu`
--
ALTER TABLE `dp_admin_menu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=373;

--
-- 使用表AUTO_INCREMENT `dp_admin_message`
--
ALTER TABLE `dp_admin_message`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `dp_admin_module`
--
ALTER TABLE `dp_admin_module`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `dp_admin_packet`
--
ALTER TABLE `dp_admin_packet`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `dp_admin_plugin`
--
ALTER TABLE `dp_admin_plugin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `dp_admin_role`
--
ALTER TABLE `dp_admin_role`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色id', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `dp_admin_user`
--
ALTER TABLE `dp_admin_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `dp_cms_account_log`
--
ALTER TABLE `dp_cms_account_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `dp_cms_account_type`
--
ALTER TABLE `dp_cms_account_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `dp_cms_advert`
--
ALTER TABLE `dp_cms_advert`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `dp_cms_advert_type`
--
ALTER TABLE `dp_cms_advert_type`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `dp_cms_availables`
--
ALTER TABLE `dp_cms_availables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `dp_cms_cart`
--
ALTER TABLE `dp_cms_cart`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `dp_cms_column`
--
ALTER TABLE `dp_cms_column`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;

--
-- 使用表AUTO_INCREMENT `dp_cms_daytime`
--
ALTER TABLE `dp_cms_daytime`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `dp_cms_device`
--
ALTER TABLE `dp_cms_device`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `dp_cms_document`
--
ALTER TABLE `dp_cms_document`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1591;

--
-- 使用表AUTO_INCREMENT `dp_cms_donation`
--
ALTER TABLE `dp_cms_donation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `dp_cms_feedback`
--
ALTER TABLE `dp_cms_feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `dp_cms_field`
--
ALTER TABLE `dp_cms_field`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '字段名称', AUTO_INCREMENT=26;

--
-- 使用表AUTO_INCREMENT `dp_cms_link`
--
ALTER TABLE `dp_cms_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `dp_cms_location`
--
ALTER TABLE `dp_cms_location`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `dp_cms_menu`
--
ALTER TABLE `dp_cms_menu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `dp_cms_model`
--
ALTER TABLE `dp_cms_model`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `dp_cms_nav`
--
ALTER TABLE `dp_cms_nav`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `dp_cms_notice`
--
ALTER TABLE `dp_cms_notice`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `dp_cms_order`
--
ALTER TABLE `dp_cms_order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `dp_cms_orderitems`
--
ALTER TABLE `dp_cms_orderitems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `dp_cms_order_status`
--
ALTER TABLE `dp_cms_order_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `dp_cms_page`
--
ALTER TABLE `dp_cms_page`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `dp_cms_payment`
--
ALTER TABLE `dp_cms_payment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `dp_cms_slider`
--
ALTER TABLE `dp_cms_slider`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `dp_cms_support`
--
ALTER TABLE `dp_cms_support`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `dp_cms_teacher`
--
ALTER TABLE `dp_cms_teacher`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `dp_cms_topic`
--
ALTER TABLE `dp_cms_topic`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `dp_cms_users`
--
ALTER TABLE `dp_cms_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `dp_cms_withdrawal`
--
ALTER TABLE `dp_cms_withdrawal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : tp

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-11-19 18:20:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tp_answer
-- ----------------------------
DROP TABLE IF EXISTS `tp_answer`;
CREATE TABLE `tp_answer` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `aid` int(4) NOT NULL DEFAULT '0' COMMENT '文章id',
  `uid` int(11) NOT NULL DEFAULT '0',
  `cid` int(4) NOT NULL DEFAULT '0' COMMENT '评论id',
  `pid` int(4) NOT NULL DEFAULT '0' COMMENT '回复id',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '回复内容',
  `create_time` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章回复表';

-- ----------------------------
-- Records of tp_answer
-- ----------------------------

-- ----------------------------
-- Table structure for tp_article
-- ----------------------------
DROP TABLE IF EXISTS `tp_article`;
CREATE TABLE `tp_article` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '文章标题',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `uid` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL COMMENT '文章内容',
  `views` smallint(4) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `comts` smallint(4) NOT NULL DEFAULT '0' COMMENT '评论次数',
  `create_time` int(4) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文章表';

-- ----------------------------
-- Records of tp_article
-- ----------------------------
INSERT INTO `tp_article` VALUES ('1', 'this is test article', 'this is thumb', 'this is img', '1', 'test content', '0', '0', '1542336116');

-- ----------------------------
-- Table structure for tp_article_info
-- ----------------------------
DROP TABLE IF EXISTS `tp_article_info`;
CREATE TABLE `tp_article_info` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `aid` int(4) NOT NULL DEFAULT '0' COMMENT '文章id',
  `mid` int(4) NOT NULL DEFAULT '0' COMMENT '标签id',
  `create_time` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文章辅表';

-- ----------------------------
-- Records of tp_article_info
-- ----------------------------
INSERT INTO `tp_article_info` VALUES ('1', '1', '1', '1542336116');

-- ----------------------------
-- Table structure for tp_comment
-- ----------------------------
DROP TABLE IF EXISTS `tp_comment`;
CREATE TABLE `tp_comment` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `aid` int(4) NOT NULL DEFAULT '0' COMMENT '文章id',
  `uid` int(11) NOT NULL DEFAULT '0',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '评论内容',
  `greats` int(4) NOT NULL DEFAULT '0' COMMENT '被点赞次数',
  `bads` int(4) NOT NULL DEFAULT '0' COMMENT '被差评次数',
  `create_time` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章评论表';

-- ----------------------------
-- Records of tp_comment
-- ----------------------------

-- ----------------------------
-- Table structure for tp_email
-- ----------------------------
DROP TABLE IF EXISTS `tp_email`;
CREATE TABLE `tp_email` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `email` varchar(50) NOT NULL DEFAULT '',
  `pwd` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`email`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邮箱登录方式表';

-- ----------------------------
-- Records of tp_email
-- ----------------------------
INSERT INTO `tp_email` VALUES ('1', '18016550990@163.com', '');

-- ----------------------------
-- Table structure for tp_mark
-- ----------------------------
DROP TABLE IF EXISTS `tp_mark`;
CREATE TABLE `tp_mark` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '标签名',
  `use_timer` int(4) NOT NULL DEFAULT '0' COMMENT '被使用次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='标签表';

-- ----------------------------
-- Records of tp_mark
-- ----------------------------
INSERT INTO `tp_mark` VALUES ('1', '测试标签', '1');

-- ----------------------------
-- Table structure for tp_nav
-- ----------------------------
DROP TABLE IF EXISTS `tp_nav`;
CREATE TABLE `tp_nav` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nav_name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `nav_action` varchar(50) NOT NULL DEFAULT '' COMMENT '对应的操作方法',
  `nav_control` varchar(50) NOT NULL DEFAULT '' COMMENT '对应的控制器类',
  `is_blank` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否新窗口打开',
  `sort` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='网站导航表';

-- ----------------------------
-- Records of tp_nav
-- ----------------------------
INSERT INTO `tp_nav` VALUES ('1', '首页', 'index', 'index', '0', '0', '1');
INSERT INTO `tp_nav` VALUES ('2', 'test1', 'index', 'index', '0', '1', '1');
INSERT INTO `tp_nav` VALUES ('3', 'test2', 'index', 'index', '0', '2', '1');

-- ----------------------------
-- Table structure for tp_user
-- ----------------------------
DROP TABLE IF EXISTS `tp_user`;
CREATE TABLE `tp_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `voice` int(4) NOT NULL DEFAULT '0' COMMENT '声望',
  `comts` int(4) NOT NULL DEFAULT '0' COMMENT '参与评论次数',
  `artls` int(4) NOT NULL DEFAULT '0' COMMENT '发布文章次数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of tp_user
-- ----------------------------
INSERT INTO `tp_user` VALUES ('1', 'dingwenqiang', '/logo', '100', '0', '0', '1', '1542336116');

-- ----------------------------
-- Table structure for tp_web_set
-- ----------------------------
DROP TABLE IF EXISTS `tp_web_set`;
CREATE TABLE `tp_web_set` (
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '网站logo',
  `memo` varchar(1000) NOT NULL DEFAULT '' COMMENT '简介',
  `icq` varchar(20) NOT NULL DEFAULT '' COMMENT '联系qq',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '网站状态 0关闭',
  `alow_reg` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开放注册'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站设置表';

-- ----------------------------
-- Records of tp_web_set
-- ----------------------------

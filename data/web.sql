DROP TABLE IF EXISTS `@pf_manage`;
CREATE TABLE `@pf_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '用户名',
  `pwd` varchar(255) DEFAULT NULL COMMENT '用户密码',
  `avatar` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `realName` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT '0' COMMENT '管理员类型',
  `errTice` int(11) DEFAULT '0' COMMENT '错误次数',
  `errTime` date DEFAULT NULL COMMENT '错误时间',
  `thisTime` datetime DEFAULT NULL COMMENT '本次登录时间',
  `lastTime` datetime DEFAULT NULL COMMENT '最后登录时间',
  `thisIp` varchar(255) DEFAULT NULL COMMENT '本次登录IP',
  `lastIp` varchar(255) DEFAULT NULL COMMENT '最后一次登录IP',
  `isLock` int(1) DEFAULT '0' COMMENT '是否锁定账号',
  `email` varchar(255) DEFAULT NULL COMMENT '管理员邮箱',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=@@charset;

INSERT INTO `@pf_manage` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '', 'wj008', '1', '0', '1999-01-01', '2018-12-06 21:30:31', '2018-12-06 21:30:19', '127.0.0.1', '127.0.0.1', '0', 'admin');

DROP TABLE IF EXISTS `@pf_sys_menu`;
CREATE TABLE `@pf_sys_menu` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) DEFAULT NULL COMMENT '菜单标题',
    `allow` int(1) DEFAULT '0' COMMENT '是否启用',
    `pid` varchar(255) DEFAULT NULL COMMENT '所属上级菜单',
    `show` int(1) DEFAULT '0' COMMENT '是否展开',
    `url` varchar(255) DEFAULT NULL COMMENT '栏目路径',
    `blank` int(1) DEFAULT '0' COMMENT '是否新窗口',
    `sort` int(11) DEFAULT '0' COMMENT '排序',
    `remark` text COMMENT '备注',
    `icon` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=@@charset;

INSERT INTO `@pf_sys_menu` VALUES ('1', '首页', '1', '0', '1', '', 0,'10', '', '');
INSERT INTO `@pf_sys_menu` VALUES ('2', '系统账号管理', '1', '1', '1', '', 0, '0', '', 'icofont-teacher');
INSERT INTO `@pf_sys_menu` VALUES ('3', '管理员管理', '1', '2', '1', '~/Manage', 0, '12', null, null);
INSERT INTO `@pf_sys_menu` VALUES ('4', '修改管理密码', '1', '2', '1', '~/Manage/password', 0, '0', '', '');
INSERT INTO `@pf_sys_menu` VALUES ('5', '网站信息管理', '1', '1', '1', '', 0, '20', '', 'icofont-navigation-menu');
INSERT INTO `@pf_sys_menu` VALUES ('6', '系统菜单', '1', '0', '1', null, 0, '400', null, null);
INSERT INTO `@pf_sys_menu` VALUES ('7', '工具箱', '1', '6', '1', '', 0, '0', '', 'icofont-tools-alt-2');
INSERT INTO `@pf_sys_menu` VALUES ('8', '系统菜单管理', '1', '7', '1', '~/SysMenu', 1, '50', null, null);
INSERT INTO `@pf_sys_menu` VALUES ('10', '项目管理', '1', '7', '0', '^/tool/index', 1, '1', '', '');
INSERT INTO `@pf_sys_menu` VALUES ('11', '表单模型', '1', '7', '0', '^/tool/app_form', 1, '2', '', '');
INSERT INTO `@pf_sys_menu` VALUES ('12', '列表模型', '1', '7', '0', '^/tool/app_list', 1, '3', '', '');
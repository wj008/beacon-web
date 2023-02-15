DROP TABLE IF EXISTS `@pf_manager`;
CREATE TABLE `@pf_manager`
(
    `id`       int(11) NOT NULL AUTO_INCREMENT,
    `account`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户名',
    `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户密码',
    `avatar`   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户头像',
    `realName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `type`     int(11) NULL DEFAULT 0 COMMENT '管理员类型',
    `errTice`  int(11) NULL DEFAULT 0 COMMENT '错误次数',
    `errTime`  date NULL DEFAULT NULL COMMENT '错误时间',
    `thisTime` datetime NULL DEFAULT NULL COMMENT '本次登录时间',
    `lastTime` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
    `thisIp`   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '本次登录IP',
    `lastIp`   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '最后一次登录IP',
    `isLock`   int(1) NULL DEFAULT 0 COMMENT '是否锁定账号',
    `email`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '管理员邮箱',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of @pf_manager
-- ----------------------------
INSERT INTO `@pf_manager`
VALUES (1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '/upfiles/images/16764668778116.jpg', 'wj008', 1, 0, '1999-01-01', '2023-02-15 21:56:24', '2023-02-15 21:47:37', '127.0.0.1', '127.0.0.1', 0, 'admin');

-- ----------------------------
-- Table structure for @pf_sys_menu
-- ----------------------------
DROP TABLE IF EXISTS `@pf_sys_menu`;
CREATE TABLE `@pf_sys_menu`
(
    `id`     int(11) NOT NULL AUTO_INCREMENT,
    `name`   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '菜单标题',
    `allow`  int(1) NULL DEFAULT 0 COMMENT '是否启用',
    `pid`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '所属上级菜单',
    `type`   tinyint(1) NULL DEFAULT 0,
    `show`   int(1) NULL DEFAULT 0 COMMENT '是否展开',
    `sort`   int(11) NULL DEFAULT 0 COMMENT '排序',
    `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
    `icon`   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `app`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应用，默认当前应用',
    `ctl`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '控制器',
    `act`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '方法',
    `params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '参数',
    `menu`   tinyint(1) NULL DEFAULT NULL COMMENT '菜单',
    `auth`   tinyint(1) NULL DEFAULT NULL COMMENT '权限',
    `isUrl`  tinyint(1) NULL DEFAULT NULL,
    `url`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `blank`  tinyint(1) NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    INDEX    `pid`(`pid`(191), `allow`, `menu`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 97 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of @pf_sys_menu
-- ----------------------------
INSERT INTO `@pf_sys_menu`
VALUES (1, '平台', 1, '0', 1, 0, 10, '', 'icofont-tools-alt-2', 'admin', '', '', '', 1, 0, 0, '', 0);
INSERT INTO `@pf_sys_menu`
VALUES (2, '系统账号管理', 1, '1', 1, 0, 20, '', 'icofont-businessman', 'admin', '', '', '', 1, 0, 0, '', 0);
INSERT INTO `@pf_sys_menu`
VALUES (5, '系统管理员', 1, '2', 2, 0, 30, '', '', 'admin', 'manager', 'index', '', 1, 0, 0, '', 0);
INSERT INTO `@pf_sys_menu`
VALUES (6, '修改基本信息', 1, '2', 2, 0, 60, '', '', 'admin', 'manager', 'info', '', 1, 1, 0, '', 0);
INSERT INTO `@pf_sys_menu`
VALUES (7, '修改账号密码', 1, '2', 2, 0, 70, '', '', 'admin', 'manager', 'password', '', 1, 1, 0, '', 0);
INSERT INTO `@pf_sys_menu`
VALUES (8, '工具集合', 1, '1', 1, 0, 80, '', 'icofont-calculations', 'admin', '', '', '', 1, 0, 0, '', 0);
INSERT INTO `@pf_sys_menu`
VALUES (9, '应用管理', 1, '8', 2, 0, 90, '', '', 'tool', 'index', 'index', '', 1, 0, 0, '', 1);
INSERT INTO `@pf_sys_menu`
VALUES (10, '表单管理', 1, '8', 2, 0, 100, '', '', 'tool', 'app_form', 'index', '', 1, 0, 0, '', 1);
INSERT INTO `@pf_sys_menu`
VALUES (11, '列表管理', 1, '8', 2, 0, 110, '', '', 'tool', 'app_list', 'index', '', 1, 0, 0, '', 1);
INSERT INTO `@pf_sys_menu`
VALUES (12, '菜单管理', 1, '8', 2, 0, 120, '', '', 'admin', 'sys_menu', 'index', '', 1, 0, 0, '', 0);
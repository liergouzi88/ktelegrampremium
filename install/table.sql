
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for options
-- ----------------------------
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options`  (
  `name` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of options
-- ----------------------------
INSERT INTO `options` VALUES ('error_total', '228');
INSERT INTO `options` VALUES ('fastlogin_ip', '');
INSERT INTO `options` VALUES ('message_total', '258');
INSERT INTO `options` VALUES ('password', '77e2edcc9b40441200e31dc57dbb8829');
INSERT INTO `options` VALUES ('send_total', '220');

-- ----------------------------
-- Table structure for plugins
-- ----------------------------
DROP TABLE IF EXISTS `plugins`;
CREATE TABLE `plugins`  (
  `pcn` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `enabled` int(1) NULL DEFAULT NULL,
  `lasterror` int(11) NULL DEFAULT NULL,
  `priority` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`pcn`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of plugins
-- ----------------------------
INSERT INTO `plugins` VALUES ('kaivip', 1, 1683092985, 1);

-- ----------------------------
-- Table structure for user_huiyuanodder
-- ----------------------------
DROP TABLE IF EXISTS `user_huiyuanodder`;
CREATE TABLE `user_huiyuanodder`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `money` double(10, 2) NULL DEFAULT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `certime` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `start` int(1) NULL DEFAULT NULL COMMENT '0 提交  1 处理 中 2 ok',
  `tancan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `order_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_huiyuanodder
-- ----------------------------

-- ----------------------------
-- Table structure for user_money
-- ----------------------------
DROP TABLE IF EXISTS `user_money`;
CREATE TABLE `user_money`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `money` double(10, 2) NULL DEFAULT NULL,
  `ljmoney` double(10, 2) NULL DEFAULT NULL,
  `trxadd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_money
-- ----------------------------

-- ----------------------------
-- Table structure for user_payodder
-- ----------------------------
DROP TABLE IF EXISTS `user_payodder`;
CREATE TABLE `user_payodder`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NULL DEFAULT NULL,
  `money` double(10, 2) NULL DEFAULT NULL,
  `ljmoney` double(10, 2) NULL DEFAULT NULL,
  `trxadd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_payodder
-- ----------------------------
INSERT INTO `user_payodder` VALUES (1, 1283520615, 100.00, 100.00, 'xxxxxxxxxx');

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `jill_booking` (
  `jb_sn` mediumint(9) unsigned NOT NULL auto_increment COMMENT '預約編號',
  `jb_uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '預約者',
  `jb_booking_time` datetime NOT NULL COMMENT '預約時間',
  `jb_booking_content` text NOT NULL COMMENT '預約理由',
  `jb_start_date` date NOT NULL COMMENT '開始日期',
  `jb_end_date` date NOT NULL COMMENT '結束日期',
  PRIMARY KEY (`jb_sn`)
) ENGINE=MyISAM;

CREATE TABLE `jill_booking_week` (
  `jb_sn` mediumint(9) unsigned NOT NULL COMMENT '預約編號',
  `jb_week` tinyint(1) NOT NULL COMMENT '星期',
  `jbt_sn` mediumint(8) unsigned NOT NULL COMMENT '時段編號',
  PRIMARY KEY (`jb_sn`,`jb_week`,`jbt_sn`)
) ENGINE=MyISAM;

CREATE TABLE `jill_booking_date` (
  `jb_sn` mediumint(9) unsigned NOT NULL COMMENT '預約編號',
  `jb_date` date NOT NULL COMMENT '日期',
  `jbt_sn` mediumint(8) unsigned NOT NULL auto_increment COMMENT '時段編號',
  `jb_waiting` tinyint(3) NOT NULL default '0' COMMENT '候補',
  `jb_status` enum('1','0') NOT NULL COMMENT '是否核准',
  PRIMARY KEY (`jb_sn`,`jb_date`,`jbt_sn`)
) ENGINE=MyISAM;

CREATE TABLE `jill_booking_item` (
  `jbi_sn` smallint(6) unsigned NOT NULL auto_increment COMMENT '場地編號',
  `jbi_title` varchar(255) NOT NULL default '' COMMENT '場地名稱',
  `jbi_desc` text NOT NULL COMMENT '場地說明',
  `jbi_sort` smallint(6) unsigned NOT NULL default '0' COMMENT '場地排序',
  `jbi_start` date NOT NULL  COMMENT '啟用日期',
  `jbi_end` date NOT NULL  COMMENT '停用日期',
  `jbi_enable` enum('1','0') NOT NULL COMMENT '是否可借',
  `jbi_approval` enum('0','1') NOT NULL COMMENT '是否需審核',
  PRIMARY KEY (`jbi_sn`)
) ENGINE=MyISAM;

CREATE TABLE `jill_booking_time` (
  `jbt_sn` mediumint(8) unsigned NOT NULL auto_increment COMMENT '時段編號',
  `jbi_sn` smallint(6) unsigned NOT NULL COMMENT '場地編號',
  `jbt_title` varchar(255) NOT NULL default '' COMMENT '時段標題',
  `jbt_sort` smallint(6) unsigned NOT NULL default '0' COMMENT '時段排序',
  `jbt_week` set('0','1','2','3','4','5','6') NOT NULL COMMENT '開放星期' ,
  PRIMARY KEY (`jbt_sn`)
) ENGINE=MyISAM;


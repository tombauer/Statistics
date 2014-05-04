<?php

namespace Modules\statistics\endurit_statistics;

if (!defined('CMS')) exit; //this file can't bee accessed directly

class Install{

    public function execute(){

        $sql="
            CREATE TABLE IF NOT EXISTS `".DB_PREF."m_statistics_endurit_statistics` (
              `id` int(111) NOT NULL AUTO_INCREMENT,
              `date` date NOT NULL,
              `pages` int(11) DEFAULT 0,
              `visitors` int(11) DEFAULT 0,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=121 ;
        ";

        $rs = mysql_query($sql);

        if(!$rs){
            trigger_error($sql." ".mysql_error());
        }        
    }
}
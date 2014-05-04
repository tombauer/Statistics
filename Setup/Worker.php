<?php

//namespace Plugin\Statistics\Setup;
namespace Plugin\Statistics\Setup;

class Worker extends \Ip\SetupWorker
{

    public function activate()
    {
        $sql="
            CREATE TABLE IF NOT EXISTS " . ipTable('statistics') . " (
              `id` int(111) NOT NULL AUTO_INCREMENT,
              `date` date NOT NULL,
              `pages` int(11) DEFAULT 0,
              `visitors` int(11) DEFAULT 0,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=121 ;
        ";     

        ipDb()->execute($sql);

    }

    public function deactivate()
    {

    }

    public function remove()
    {
		 $sql="DROP TABLE " . ipTable('statistics');     
        ipDb()->execute($sql);
    }
}

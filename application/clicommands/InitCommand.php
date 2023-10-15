<?php
/* icingaweb2-module-customdashboards (c) 2023 | GPLv2+ */

namespace Icinga\Module\Customdashboards\Clicommands;


use Icinga\Application\Config;
use Icinga\Cli\Command;
use Icinga\Module\Customdashboards\MappingIniRepository;


class InitCommand extends Command
{
    /** @var MappingIniRepository */
    protected $repo;


    public function indexAction()
    {
        if(!Config::module('customdashboards', "config")->hasSection('settings')){
            Config::module('customdashboards', "config")->setSection('settings',['menu_icon'=>'binoculars','priority'=>15,"max_dashlets"=>3, 'menu_name'=>"CUSTOM"])->saveIni();
            echo sprintf("customdashboards initialized");
            echo "\n";

        }else{
            echo sprintf("customdashboards already initialized");
            echo "\n";
        }
    }




}

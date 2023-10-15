<?php
/* Originally from Icinga Web 2 Elasticsearch Module (c) 2017 Icinga Development Team | GPLv2+ */
/* generated by icingaweb2-module-scaffoldbuilder | GPLv2+ */
/* icingaweb2-module-customdashboards (c) 2023 | GPLv2+ */

namespace Icinga\Module\Customdashboards;

use Icinga\Application\Config;
use Icinga\Data\ConfigObject;
use Icinga\Repository\IniRepository;

class MappingIniRepository extends IniRepository
{
    public function init()
    {

        $max_dashlets  = intval(Config::module('customdashboards', "config")->get('settings', 'max_dashlets'));
        if($max_dashlets ==0){
            $max_dashlets=4;
        }
       for($i=1; $i<=$max_dashlets;$i++){
           $this->queryColumns['mapping'][]="dashletname".$i;
           $this->queryColumns['mapping'][]="dashleturl".$i;
       }

        parent::init(); // TODO: Change the autogenerated stub
    }

    protected $configs = [
        'mapping' => [
            'name'      => 'mapping',
            'keyColumn' => 'name',
            'module'    => 'customdashboards'
        ]
    ];

    protected $queryColumns = [
        'mapping' => [
            'name',
            'priority',
            'enabled',
        ]
    ];
}

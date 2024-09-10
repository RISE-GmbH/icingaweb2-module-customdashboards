<?php

/** @var \Icinga\Application\Modules\Module $this */

use Icinga\Application\Config;
use Icinga\Application\Icinga;
use Icinga\Authentication\Auth;
use Icinga\Data\Filter\FilterEqual;
use Icinga\Data\Filter\FilterMatch;
use Icinga\Data\Filter\FilterOr;
use Icinga\Module\Customdashboards\MappingIniRepository;

$this->provideConfigTab('config/moduleconfig', array(
    'title' => $this->translate('Module Configuration'),
    'label' => $this->translate('Module Configuration'),
    'url' => 'moduleconfig'
));

$icon = Config::module('customdashboards', "config")->get('settings', 'menu_icon') != null ?
    trim(Config::module('customdashboards', "config")->get('settings', 'menu_icon'), "") : "dashboard";

$priority = Config::module('customdashboards', "config")->get('settings', 'priority') != null ?
    trim(Config::module('customdashboards', "config")->get('settings', 'priority'), "") : 15;

$name = Config::module('customdashboards', "config")->get('settings', 'menu_name') != null ?
    trim(Config::module('customdashboards', "config")->get('settings', 'menu_name'), "") : "CUSTOM";

$section = $this->menuSection($name, [
    'url' => "navigation/dashboard?name=$name",
    'icon' => $icon,
    'priority' => $priority
]);




$this->provideConfigTab('config/mapping', array(
    'title' => $this->translate('Configuration'),
    'label' => $this->translate('Configuration'),
    'url' => 'mapping'
));

$auth = Auth::getInstance();

if ($auth->isAuthenticated() && !Icinga::app()->isCli()) {
    $this->providePermission('customdashboards/mapping', $this->translate('allow access to mapping'));

    $isAuthor = ((new MappingIniRepository())->select()->where('author',$auth->getUser()->getUsername())->count() > 0);
    if($auth->hasPermission('customdashboards/mapping') || $isAuthor ){
        $section->add(N_('Mapping'))
            ->setUrl('customdashboards/mapping')
            ->setPriority(30);
    }
    $mappings = (new MappingIniRepository())->select()->fetchAll();

    foreach ($mappings as $mapping) {
        $permission = 'customdashboards/mapping/' . $mapping->name;

        if($mapping->enabled){
            $this->providePermission($permission, $this->translate('allow access to mapping') . " " . $mapping->name);
            if($auth->hasPermission($permission) || $mapping->author == $auth->getUser()->getUsername()){
                $section->add($mapping->name, array(
                    'url' => 'customdashboards/custom-dashboard',
                    'urlParameters' => array('pane' => $mapping->name),
                    'priority' => $mapping->priority,
                ));
            }

        }

    }
}


?>


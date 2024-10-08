<?php
/* Originally from Icinga Web 2 | (c) 2013 Icinga Development Team | GPLv2+ */
/* generated by icingaweb2-module-scaffoldbuilder | GPLv2+ */
/* icingaweb2-module-customdashboards (c) 2023 | GPLv2+ */

namespace Icinga\Module\Customdashboards\Controllers;

use Icinga\Application\Config;
use Icinga\Data\Filter\FilterEqual;
use Icinga\Data\Filter\FilterExpression;
use Icinga\Data\Filter\FilterMatch;
use Icinga\Data\Filter\FilterOr;
use Icinga\Module\Customdashboards\MappingIniRepository;
use Icinga\Module\Customdashboards\Customdashboard;
use Icinga\User;
use Icinga\Web\Controller\ActionController;
use Icinga\Web\Url;
use Icinga\Web\Widget\Dashboard;
use Icinga\Web\Widget\Dashboard\Dashlet as DashboardDashlet;


/**
 * Handle creation, removal and displaying of dashboards, panes and dashlets
 *
 * @see Icinga\Web\Widget\Dashboard for more information about dashboards
 */
class CustomDashboardController extends ActionController
{
    /**
     * @var Dashboard;
     */
    private $dashboard;

    public function init()
    {
        $this->dashboard = new CustomDashboard();
        $this->dashboard->setUser($this->Auth()->getUser());

    }

    /**
     * Display the dashboard with the pane set in the 'pane' request parameter
     *
     * If no pane is submitted or the submitted one doesn't exist, the default pane is
     * displayed (normally the first one)
     */
    public function indexAction()
    {

        $forAllUsers = (new MappingIniRepository())->select()->addFilter(new FilterMatch('enabled','=',"1"))->fetchAll();

        foreach ($forAllUsers as $mapping){
            $permission = 'customdashboards/mapping/'.$mapping->name;
            if($this->hasPermission($permission) || $this->hasPermission("customdashboards/mapping") || $mapping->author == $this->Auth()->getUser()->getUsername()){
                $mappings[$mapping->name]=$mapping;
            };

        }

        usort($mappings, function($a, $b) {return intval($a->priority) - intval($b->priority);});


        foreach ($mappings as $mapping) {

            $this->dashboard->createPane($mapping->name);
            $pane = $this->dashboard->getPane($mapping->name);


            $max_dashlets  = intval(Config::module('customdashboards', "config")->get('settings', 'max_dashlets',4));

            for($i=1; $i<=$max_dashlets;$i++){
                if($mapping->{"dashletname".$i} == "" || $mapping->{"dashletname".$i} ==null){
                    continue;
                }
                $dashletUrl= $mapping->{"dashleturl".$i};

                if(strpos($dashletUrl,'https://') !== false || strpos($dashletUrl,'http://') !== false ){
                    $dashletUrl= \ipl\Web\Url::fromPath('customdashboards/external',['name'=>$mapping->name, 'dashlet'=>$i]);
                }
                $dashlet = new DashboardDashlet(
                    $mapping->{"dashletname".$i},
                    $dashletUrl,
                    $pane
                );

                $dashlet->setUserWidget();
                $pane->addDashlet($dashlet);


            }
        }


        $this->dashboard->load();

        $this->createTabs();
        if (! $this->dashboard->hasPanes()) {
            $this->view->title = 'CustomDashboard';
        } else {
            $panes = array_filter(
                $this->dashboard->getPanes(),
                function ($pane) {
                    return ! $pane->getDisabled();
                }
            );
            if (empty($panes)) {
                $this->view->title = 'CustomDashboard';
                $this->getTabs()->add('customDashboard', array(
                    'active'    => true,
                    'title'     => $this->translate('CustomDashboard'),
                    'url'       => Url::fromRequest()
                ));
            } else {
                if ($this->_getParam('pane')) {
                    $pane = $this->_getParam('pane');
                    $this->dashboard->activate($pane);
                }
                if ($this->dashboard === null) {
                    $this->view->title = 'CustomDashboard';
                } else {
                    $this->view->title = $this->dashboard->getActivePane()->getTitle() . ' :: CustomDashboard';

                    $this->view->dashboard = $this->dashboard;
                }
            }
        }
    }



    /**
     * Create tab aggregation
     */
    private function createTabs()
    {
        $this->view->tabs = $this->dashboard->getTabs();
    }
}

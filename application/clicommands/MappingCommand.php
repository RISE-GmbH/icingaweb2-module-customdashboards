<?php
/* icingaweb2-module-customdashboards (c) 2023 | GPLv2+ */

namespace Icinga\Module\Customdashboards\Clicommands;


use Icinga\Application\Config;
use Icinga\Cli\Command;
use Icinga\Data\Filter\Filter;
use Icinga\Module\Customdashboards\MappingIniRepository;


class MappingCommand extends Command
{
    /** @var MappingIniRepository */
    protected $repo;


    public function init()
    {
        $this->repo = new MappingIniRepository();
    }

    public function createAction()
    {
        $name = $this->params->getRequired('name');


        $max_dashlets  = intval(Config::module('customdashboards', "config")->get('settings', 'max_dashlets'));
        if( $max_dashlets ==0 ){
            $max_dashlets=4;
        }
        $data = [
            'name' => $name,
            'priority' => $this->params->getRequired('priority'),
            'enabled' =>  $this->params->get('enabled',"1"),
        ];
        for($i=1; $i<=$max_dashlets;$i++){
            $data['dashleturl'.$i]=$this->params->get('dashleturl'.$i,"");
            $data['dashletname'.$i]=$this->params->get('dashletname'.$i,"");
        }



        $this->repo->insert($this->repo->getBaseTable(), $data);
        echo sprintf("%s was created", $name);
        echo "\n";
    }

    public function deleteAction()
    {
        $name = $this->params->getRequired('name');
        $query = $this->repo->select()->where('name', $name);

        if (! $query->hasResult()) {
            echo "Nothing to delete";
            echo "\n";
        }else{
            $this->repo->delete($this->repo->getBaseTable(),Filter::where('name', $name));
            echo sprintf("%s was deleted", $name);
            echo "\n";
        }

    }

    private function updateAction()
    {
        $name = $this->params->getRequired('name');

        $max_dashlets  = intval(Config::module('customdashboards', "config")->get('settings', 'max_dashlets'));
        if($max_dashlets ==0){
            $max_dashlets=4;
        }
        $data = ['name' => $name];
        if($this->params->get('priority') != null){
            $data['priority'] = $this->params->get('priority');
        }

        if($this->params->get('enabled') != null){
            $data['enabled'] = $this->params->get('enabled');
        }

        for($i=1; $i<=$max_dashlets;$i++){
            if($this->params->get('dashleturl'.$i) != null){
                $data['dashleturl'.$i]=$this->params->get('dashleturl'.$i);
            }
            if($this->params->get('dashletname'.$i) != null){
                $data['dashletname'.$i]=$this->params->get('dashletname'.$i);
            }
        }



        $this->repo->update($this->repo->getBaseTable(),$data,Filter::where('name', $name));
        echo sprintf("%s was updated", $name);
        echo "\n";
    }


}

<?php
/* Originally from Icinga Web 2 Elasticsearch Module (c) 2017 Icinga Development Team | GPLv2+ */
/* generated by icingaweb2-module-scaffoldbuilder | GPLv2+ */
/* icingaweb2-module-customdashboards (c) 2023 | GPLv2+ */

namespace Icinga\Module\Customdashboards\Forms;

use Icinga\Application\Config;
use Icinga\Data\Filter\Filter;
use Icinga\Forms\RepositoryForm;
use Icinga\Module\Customdashboards\MappingIniRepository;

/**
 * Create, update and delete a Config
 */
class MappingForm extends RepositoryForm
{

    public function init()
    {
        $this->repository = new MappingIniRepository();
        $this->redirectUrl = 'customdashboards/mapping';

    }



    /**
     * Set the identifier
     *
     * @param   string  $identifier
     *
     * @return  $this
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Set the mode of the form
     *
     * @param   int $mode
     *
     * @return  $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    protected function onUpdateSuccess()
    {
        if ($this->getElement('btn_remove')->isChecked()) {
            $this->setRedirectUrl("customdashboards/mapping/delete?id={$this->getIdentifier()}");
            $success = true;
        } else {
            $success = parent::onUpdateSuccess();
        }

        return $success;
    }

    protected function createBaseElements(array $formData)
    {
        $this->addElement(
            'text',
            'name',
            array(
                'description'   => $this->translate('Name of the Pane'),
                'label'         => $this->translate('Pane Name'),
                'placeholder'   => 'Dashboard 1',
                'required'      => true
            )
        );
        $this->addElement(
            'text',
            'author',
            array(
                'description'   => $this->translate('Author of the Pane'),
                'label'         => $this->translate('Author'),
                'required'      => true,
                'value'=>$this->Auth()->getUser()->getUsername()
            )
        );
        $this->addElement(
            'number',
            'priority',
            array(
                'description'   => $this->translate('Priority of the Pane'),
                'label'         => $this->translate('Priority'),
                'required'      => true
            )
        );


        $max_dashlets  = intval(Config::module('customdashboards', "config")->get('settings', 'max_dashlets'));
        if($max_dashlets ==0){
            $max_dashlets=4;
        }
        for($i=1; $i<=$max_dashlets;$i++){
            $this->addElement(
                'text',
                'dashletname'.$i,
                array(
                    'description'   => $this->translate('Name of the Dashlet '.$i),
                    'label'         => $this->translate('Dashlet Name '.$i),
                    'required'      => false
                )
            );
            $this->addElement(
                'text',
                'dashleturl'.$i,
                array(
                    'description'   => $this->translate('Url of the Dashlet '.$i),
                    'label'         => $this->translate('Dashlet Url '.$i),
                    'required'      => false
                )
            );

        }


        $this->addElement(
            'checkbox',
            'enabled',
            array(
                'description'       => $this->translate('Enable or disable this entry'),
                'label'             => $this->translate('Enabled'),
            )
        );

    }

    protected function createInsertElements(array $formData)
    {
        $this->createBaseElements($formData);

        $this->setTitle($this->translate('Create a New Pane'));

        $this->setSubmitLabel($this->translate('Save'));
    }

    protected function createUpdateElements(array $formData)
    {
        $this->createBaseElements($formData);

        $this->setTitle(sprintf($this->translate('Update Pane %s'), $this->getIdentifier()));

        $this->addElement(
            'submit',
            'btn_submit',
            [
                'decorators'            => ['ViewHelper'],
                'ignore'                => true,
                'label'                 => $this->translate('Save')
            ]
        );

        $this->addElement(
            'submit',
            'btn_remove',
            [
                'decorators'            => ['ViewHelper'],
                'ignore'                => true,
                'label'                 => $this->translate('Remove')
            ]
        );

        $this->addDisplayGroup(
            ['btn_submit', 'btn_remove'],
            'form-controls',
            [
                'decorators' => [
                    'FormElements',
                    ['HtmlTag', ['tag' => 'div', 'class' => 'control-group form-controls']]
                ]
            ]
        );
    }

    protected function createDeleteElements(array $formData)
    {
        $this->setTitle(sprintf($this->translate('Remove Pane %s'), $this->getIdentifier()));

        $this->setSubmitLabel($this->translate('Yes'));
    }

    protected function createFilter()
    {
        return Filter::where('name', $this->getIdentifier());
    }

    protected function getInsertMessage($success)
    {
        return $success
            ? $this->translate('Pane created')
            : $this->translate('Failed to create Mapping');
    }

    protected function getUpdateMessage($success)
    {
        return $success
            ? $this->translate('Pane updated')
            : $this->translate('Failed to update Mapping');
    }

    protected function getDeleteMessage($success)
    {
        return $success
            ? $this->translate('Pane removed')
            : $this->translate('Failed to remove Mapping');
    }
}

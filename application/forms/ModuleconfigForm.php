<?php
/* Originally from Icinga Web 2 Reporting Module (c) Icinga GmbH | GPLv2+ */
/* generated by icingaweb2-module-scaffoldbuilder | GPLv2+ */
/* icingaweb2-module-customdashboards (c) 2023 | GPLv2+ */

namespace Icinga\Module\Customdashboards\Forms;

use Icinga\Application\Icinga;
use Icinga\Forms\ConfigForm;
use Icinga\Web\Request;

class ModuleconfigForm extends ConfigForm
{
    protected static $dummyPassword = '_web_form_m0r34m4z1n6n1ck';

    public function init()
    {

        $this->setName('customdashboards_settings');
        $this->setSubmitLabel($this->translate('Save Changes'));
    }

    public function createElements(array $formData)
    {


        $this->addElement('text', 'settings_menu_name', [
            'label' => $this->translate('Menu Name'),
            'required'=>true,

        ]);
        $AllFontOptions = [];
        $fontOptions = [
            'wrench' => "wrench",
            'plug' => "plug",
            'binoculars' => "binoculars",
            'dashboard' => "dashboard",

        ];

        $fontFile = Icinga::app()->getBaseDir().DIRECTORY_SEPARATOR."application/fonts/fontello-ifont/config.json";
        $fontFileContent = file_get_contents($fontFile);
        $fontEntries = json_decode($fontFileContent);

        foreach ($fontEntries->glyphs as $fontEntry){
            $AllFontOptions[$fontEntry->css]=$fontEntry->css;
        }
        asort($AllFontOptions);
        if(count($AllFontOptions) > 0){
            $fontOptions = $AllFontOptions;
        }
        $this->addElement('select', 'settings_menu_icon', [
            'label' => $this->translate('Menu icon'),
            'multiOptions' => $fontOptions,
            'description' => $this->translate(
                'The icon shown in the menu'
            ),
            'required'=>true,
            'value' =>  "dashboard",
        ]);

        $this->addElement('number', 'settings_priority', [
            'label' => $this->translate('Priority'),

            'description' => $this->translate(
                'The priority/position in the menu'
            ),
            'required'=>true,
            'value'=>15
        ]);
        $maxDashlets=10;
        $dashletOptions=[];
        for($i=1;$i<=$maxDashlets;$i++){
            $dashletOptions[$i]=$i;
        }
        $this->addElement('select', 'settings_max_dashlets', [
            'label' => $this->translate('Maximum dashlets'),
            'multiOptions' => $dashletOptions,
            'description' => $this->translate(
                'Maximum dashlets per pane'
            ),
            'required'=>true,
            'value' =>  "3",
        ]);

        $this->addElement('checkbox', 'settings_allow_external_urls', [
            'label' => $this->translate('Allow external Urls'),
            'description' => $this->translate(
                'Enable or disable the usage of external urls in customdashboards'
            ),
        ]);


    }




}
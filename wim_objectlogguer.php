<?php
/**
* 2007-2016 fjrus
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author fjrus <fjrus@webimpacto.com>
*  @copyright  2007-2016 fjrus
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once('classes/ObjectLogger.php');

if (!defined('_PS_VERSION_')) {
  exit;
}
  
class Wim_objectlogguer extends Module
{
    public function __construct()
    {
        $this->name = 'wim_objectlogguer';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'fjrus';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min'=> '1.6', 'max'=> _PS_VERSION_);
        $this->bootstrap = true;
        
        parent::__construct();
        
        $this->displayName = $this->l('wim_objectlogguer');
        $this->description = $this->l('Description of my module.');
        
        $this->confirmUninstall=$this->l('Are you sure you want to uninstall?');
        
        if (!Configuration::get('MYMODULE_NAME')) {      
            $this->warning=$this->l('No name provided');
        }
    }

    public function install()
    {    
       include(dirname(__FILE__).'\sql\install.php');
       return parent::install() &&
       $this->registerHook('actionObjectAddAfter') &&
       $this->registerHook('actionObjectDeleteAfter') &&
       $this->registerHook('actionObjectUpdateAfter');
    }

    public function hookActionObjectUpdateAfter($params)
    {
        $update=new ObjectLogger();
        $update->affected_objec=$params['object']->id;
        $update->action_type='Update';
        $update->object_type=get_class($params['object']);
        $update->message="Object ". get_class($params['object']) . " with id " . $params['object']->id;
        $update->date_add=date("Y-m-d H:i:s");
        if(get_class($params['object']) != 'ObjectLogger') {
          $update->add();
        }
    }

    public function hookActionObjectAddAfter($params)
    {
        $anadir=new ObjectLogger();
        $anadir->affected_object=$params['object']->id;
        $anadir->action_type='Add';
        $anadir->object_type=get_class($params['object']);
        $anadir->message="Object ". get_class($params['object']) . " with id " . $params['object']->id;
        $anadir->date_add=date("Y-m-d H:i:s");
        if(get_class($params['object']) != 'ObjectLogger') {
          $anadir->add();
        }
    }

    public function hookActionObjectDeleteAfter($params)
    {
        $del=new ObjectLogger();
        $del->affected_object=$params['object']->id;
        $del->action_type='Delete';
        $del->object_type=get_class($params['object']);
        $del->message="Object ". get_class($params['object']) . " with id " . $params['object']->id;
        $del->date_add=date("Y-m-d H:i:s");
        if(get_class($params['object']) != 'ObjectLogger') {
          $del->add();
        }
    }
}
?>
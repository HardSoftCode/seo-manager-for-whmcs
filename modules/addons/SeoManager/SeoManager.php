<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS")) die("This file cannot be accessed directly");

function SeoManager_config()
{
  $configarray = array(
  "name" => "SEO Manager",
  "description" => "The module will display titel, keywords and description to any page in WHMCS",
  "version" => "2.2.2",
  "author" => "<a href=\"http://www.hardsoftcode.com\" target=\"_blank\">HSC</a>",
  "language" => "english",
  "fields" => array(
  "delete" => array ("FriendlyName" => "Delete Modul DB", "Type" => "yesno", "Size" => "25", "Description" => "Tick this box to delete the module database on deactivating"),
  ));
  return $configarray;
}

function SeoManager_activate()
{
  try
  {
    if(!Capsule::schema()->hasTable('mod_pageseo'))
    {
      Capsule::schema()->create('mod_pageseo', function ($table)
      {
        $table->increments('id');
        $table->text('title');
        $table->text('page');
        $table->text('keywords');
        $table->text('description');
        $table->text('activated');
       });
     }
  }
  catch (\Exception $e)
  {
    return array('status'=>'error','description'=>'Unable to create table mod_pageseo: ' .$e->getMessage());
  }

  return array('status'=>'success','description'=>'Module activated successfully. Click configuration to configure the module');
}

function SeoManager_deactivate()
{
  $delete = Capsule::table('tbladdonmodules')->where('module', 'SeoManager')->where('setting', 'delete')->first();

  if($delete->value)
  {
    try
    {
      Capsule::schema()->dropIfExists('mod_pageseo');
    }
    catch (\Exception $e)
    {
      return array('status'=>'error','description'=>'Unable to drop tables: ' .$e->getMessage());
    }
  }

  return array('status'=>'success','description'=>'Module deactivated successfully');
}

function SeoManager_output($vars)
{
  $modulelink = $vars['modulelink'];
  $LANG       = $vars['_lang'];

  require(dirname( __FILE__ ).'/includes/pages/menu.php');

  if(file_exists(dirname( __FILE__ ).'/includes/pages/'.$_REQUEST['a'].'.php'))
  {
    require(dirname( __FILE__ ).'/includes/pages/'.$_REQUEST['a'].'.php');
  }
  else
  {
    require(dirname( __FILE__ ).'/includes/pages/home.php');
  }
}

<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS")) die("This file cannot be accessed directly");

function SEO_GetUrl()
{
  $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';

  return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

function SeoManager_ClientAreaHeadOutput_Hook($vars)
{
  $data = Capsule::table('mod_pageseo')->where('page', rtrim(SEO_GetUrl(), "/"))->where('activated', '0')->first();

  $keywords    = $data->keywords;
  $description = $data->description;

  if($description)
  {
    $return = "<meta name=\"description\" content=\"$description\" />\r";
  }
  if($keywords)
  {
    $return .= "<meta name=\"keywords\" content=\"$keywords\" />\r";
  }

  return $return;
}

add_hook('ClientAreaHeadOutput',1,'SeoManager_ClientAreaHeadOutput_Hook');

function SeoManager_ClientAreaPage_Hook($vars)
{
  $data = Capsule::table('mod_pageseo')->where('page', rtrim(SEO_GetUrl(), "/"))->where('activated', '0')->first();

  $title = $data->title;

  if($title)
  {
    $return['pagetitle'] = $title;

    return $return;
  }
}

add_hook('ClientAreaPage',1,'SeoManager_ClientAreaPage_Hook');

<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS")) die("This file cannot be accessed directly");

if($_REQUEST['p'] == '')
{
  if($_REQUEST['success'])
  {
    echo ' <script>$(document).ready( function(){ window.setTimeout( function(){ $(".alert").slideUp(); }, 2500);});</script>';
    echo '<div class="alert alert-success" style="font-size:14px"><strong><span class="fas fa-check"></span> '.$LANG['success'].'</strong> '.$LANG['successhelp'].'</div>';
  }

  echo '<h1><span class="fas fa-home"></span> '.$LANG['home'].'</h1>';

  echo '<p><a class="btn btn-primary" href="'.$modulelink.'&p=manage" role="button"><i class="fas fa-plus"></i> '.$LANG['createnewpageseo'].'</a></p>';
  echo '<div class="panel panel-default">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>'.$LANG['title'].'</th>
                <th>'.$LANG['pageurl'].'</th>
                <th>'.$LANG['status'].'</th>
                <th></th>
              </tr>
            </thead>
            <tbody>';
  $result = Capsule::table('mod_pageseo')->orderBy('id', 'DESC')->get();
  foreach ($result as $data)
  {
    $id          = $data->id;
    $title       = $data->title;
    $url         = $data->page;
    $status      = $data->activated;

    $statusuot = ($status) ? '<span class="label closed">'.$LANG['disable'].'</span>' : '<span class="label active">'.$LANG['enable'].'</span>';

    $statusbuttons = ($status) ? '<a href="'.$modulelink.'&p=enable&id='.$id.'" class="btn btn-default btn-sm"><i class="fas fa-check"></i> '.$LANG['enable'].'</a>' : '<a href="'.$modulelink.'&p=disable&id='.$id.'" class="btn btn-warning btn-sm"><i class="fas fa-ban"></i> '.$LANG['disable'].'</a>';

       echo '<tr>
                <td style="padding-top: 13px;">'.$title.'</td>
                <td style="padding-top: 13px;"><a href="'.$url.'" target="_blank">'.$url.'</a></td>
                <td style="padding-top: 13px;">'.$statusuot.'</td>
                <td>
                  '.$statusbuttons.'
                  <a href="'.$modulelink.'&p=manage&id='.$id.'" class="btn btn-success btn-sm"><i class="fas fa-edit"></i> '.$LANG['edit'].'</a>
                  <a href="'.$modulelink.'&p=delete&id='.$id.'" class="btn btn-danger btn-sm" onclick="return confirm(\''.$LANG['deleteinfo'].'\');"><i class="fas fa-trash"></i> '.$LANG['delete'].'</a>
                </td>
              </tr>';
  }

  if(!$id)
  {
    echo '<tr>
            <td colspan="4" class="text-center">'.$LANG['norecordsfound'].'</td>
          </tr>';
  }

      echo '</tbody>
          </table>
        </div>';
}

if($_REQUEST['p'] == 'manage')
{
  $id = $_REQUEST['id'];

  if($id)
  {
    $data = Capsule::table('mod_pageseo')->where('id', $id)->first();

    $title       = $data->title;
    $url         = $data->page;
    $keywords    = $data->keywords;
    $description = $data->description;
    $status      = $data->activated;
  }

  echo '<h1><span class="fas fa-edit"></span> '; if($id){ echo $LANG['editpageseo'];}else{ echo $LANG['createnewpageseo'];} echo'</h1>';

  echo '<form name="form" action="'.$modulelink.'&p=save&id='.$id.'" method="POST" class="form-horizontal">
          <div class="panel panel-default">
            <div class="panel-heading">
              <a href="'.$modulelink.'" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> '.$LANG['back'].'</a>
              <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> '.$LANG['savechanges'].'</button>
              <button type="reset" class="btn btn-default btn-sm"><i class="fas fa-times"></i> '.$LANG['cancel'].'</button>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['title'].'</label>
                <div class="col-md-6">
                  <input type="text" name="title" value="'.$title.'" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['pageurl'].'</label>
                <div class="col-md-6">
                  <input type="text" name="url" value="'.$url.'" class="form-control">
                  <span class="help-block">'.$LANG['pageurlhelp'].'</span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['keywords'].'</label>
                <div class="col-md-6">
                  <input type="text" name="keywords" value="'.$keywords.'" class="form-control">
                  <span class="help-block">'.$LANG['keywordshelp'].'</span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['description'].'</label>
                <div class="col-md-6">
                  <textarea name="description" class="form-control" rows="3">'.$description.'</textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['disable'].'</label>
                <div class="col-md-6">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="status" '; if ($status){ echo'CHECKED'; } echo '>'.$LANG['disablehelp'].'
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="panel-footer">
              <a href="'.$modulelink.'" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> '.$LANG['back'].'</a>
              <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> '.$LANG['savechanges'].'</button>
              <button type="reset" class="btn btn-default btn-sm"><i class="fas fa-times"></i> '.$LANG['cancel'].'</button>
            </div>
          </div>
        </form>';
}

if($_REQUEST['p'] == 'save')
{
  $id          = $_REQUEST['id'];
  $title       = $_REQUEST['title'];
  $url         = $_REQUEST['url'];
  $keywords    = $_REQUEST['keywords'];
  $description = $_REQUEST['description'];
  $status      = $_REQUEST['status'];

  if(!$status)
  {
    $status = '0';
  }

  if($id)
  {
    Capsule::table('mod_pageseo')->where('id', $id)->update(array(
                                                              'title'       => $title,
                                                              'page'        => $url,
                                                              'keywords'    => $keywords,
                                                              'description' => $description,
                                                              'activated'   => $status,
                                                              ));
  }
  else
  {
    Capsule::table('mod_pageseo')->insert(array(
                                                              'title'       => $title,
                                                              'page'        => $url,
                                                              'keywords'    => $keywords,
                                                              'description' => $description,
                                                              'activated'   => $status,
                                                              ));
  }

  header('Location: '.$modulelink.'&success=true');
  exit;
}

if($_REQUEST['p'] == 'delete')
{
  $id = $_REQUEST['id'];

  Capsule::table('mod_pageseo')->where('id', $id)->delete();

  header('Location: '.$modulelink);
  exit;
}

if($_REQUEST['p'] == 'enable')
{
  $id = $_REQUEST['id'];

  Capsule::table('mod_pageseo')->where('id', $id)->update(array('activated' => ''));

  header('Location: '.$modulelink);
  exit;
}

if($_REQUEST['p'] == 'disable')
{
  $id = $_REQUEST['id'];

  Capsule::table('mod_pageseo')->where('id', $id)->update(array('activated' => 'on'));

  header('Location: '.$modulelink);
  exit;
}

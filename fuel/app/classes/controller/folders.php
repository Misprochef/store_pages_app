<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Input;
use Fuel\Core\View;

class Controller_Folders extends Controller_Template
{
    public $template = 'layout';
    public function action_index()
    {
        $view = View::forge('folders/index');
        $this->template->title = "Store Pages App folders";
        $this->template->disp_sidebar = false;
        $this->template->content = $view;
        
        $folders_data = Model_Folders::res_folder_db('get_all');
        $arr_folder = Model_Folders::res_folder_db('get_arr_for_select', $folders_data);
        $this->template->arr_folder = $arr_folder;

        $view->set('folders', $folders_data);
    }

    public function action_folder_pages($folder_name = null)
    {
        $view = View::forge("folders/folder_pages");
        $title_name = "Store Pages App フォルダー内のページ一覧";
        $this->template->title = $title_name;
        $this->template->folder_name = $folder_name;

        $folders_data = Model_Folders::res_folder_db('get_all');
        $this->template->folders = $folders_data;
        $arr_folder = Model_Folders::res_folder_db('get_arr_for_select', $folders_data);
        $this->template->arr_folder = $arr_folder;

        $this->template->disp_sidebar = true;
        $this->template->content = $view;

        $folder_id = Model_Folders::res_folder_db('get_by_name', $folder_name)[0]['id'];
        $pages_data = Model_Pages::desc_updated_at($folder_id);
        $view->set(array('err_msg' => null,
                         'pages' => $pages_data,
                         'title_name' => $title_name,
                         'folder_name' => $folder_name));

        if (Input::param() != array()) {
            $page = Model_Pages::forge();
            $page->url = Input::param('url');
            if (Input::param('title') != "") {
                $page->title = Input::param('title');
                $target_url = Input::param('url');
                try {
                    file_get_contents($target_url);
                } catch (PhpErrorException $exc) {
                    $view->set(array('err_msg' => $exc->getMessage(),
                                     'pages' => $pages_data,
                                     'title_name' => $title_name,
                                     'folder_name' => $folder_name));
                    return;
                }
            } else {
                $target_url = Input::param('url');
                try {
                    $html_source = file_get_contents($target_url);
                } catch (PhpErrorException $exc) {
                    $view->set(
                        array('err_msg' => $exc->getMessage(),
                              'pages' => $pages_data,
                              'title_name' => $title_name,
                              'folder_name' => $folder_name)
                    );
                    return;
                }
                preg_match_all('{<title>(.*?)</title>}', $html_source, $matches);
                if (!isset($matches[1]) or count($matches[1]) === 0) {
                    return Response::forge(View::forge('pages/add_page', ['err_msg' => null,
                                                                          'title_not_getted' => true,
                                                                          'folders' => $folders_data]));
                } else {
                    if (count($matches[1]) >= 2) {
                        $page->title = array($matches[1][0]);
                    } else {
                        $page->title = $matches[1];
                    }
                }
            }
            $page->folder_id = Model_Folders::res_folder_db('get_by_name', Input::param('folder'))[0]['id'];
            $page->created_at = date("Y/m/d H:i:s");
            $page->updated_at = date("Y/m/d H:i:s");
            
            $page->id = array(Model_Pages::count() + 1);
            $ret_arr = Model_Img::getImg($page);
            if (!$ret_arr) {
            } elseif ($ret_arr['img_type'] == 'favicon') {
                $page->fav_path = $ret_arr['path'];
            } elseif ($ret_arr['img_type'] == 'in_page_img' or 'og_img') {
                $page->img_path = $ret_arr['path'];
            }
            if ($page->validates()) {
                unset($page->id);
                $page->save();
                Response::redirect("folders/folder_pages/$folder_name");
            }
        }
    }

    public function action_add_folder()
    {
        $view = View::forge('folders/add_folder');
        $this->template->title = "Store Pages App folderの追加";

        $folders_data = Model_Folders::res_folder_db('get_all');
        $this->template->folders = $folders_data;
        $arr_folder = Model_Folders::res_folder_db('get_arr_for_select', $folders_data);
        $this->template->arr_folder = $arr_folder;
        $this->template->disp_sidebar = true;
        $this->template->content = $view;

        $view->set(['err_msg' => null]);

        if (Input::param() != array()) {
            $result = Model_Folders::res_folder_db('insert', Input::param('folder'));
            if ($result === false) {
                $view->set(['err_msg' => 'すでに存在しているフォルダー名です。別のフォルダー名を入力して下さい。']);
                return;
            } else {
                Response::redirect("pages/index");
            }
        }
    }

    public function action_edit_folder($folder_name = null)
    {
        $view = View::forge('folders/edit_folder');
        $this->template->title = "Store Pages App folderの編集";

        $folders_data = Model_Folders::res_folder_db('get_all');
        $this->template->folders = $folders_data;
        $arr_folder = Model_Folders::res_folder_db('get_arr_for_select', $folders_data);
        $this->template->arr_folder = $arr_folder;

        $this->template->disp_sidebar = true;
        $this->template->content = $view;

        $folder = Model_Folders::res_folder_db('get_by_name', $folder_name)[0];
        if (!$folder) {
            throw new HttpNotFoundException();
        }

        $view->set(['err_msg' => null, 'folder_name' => $folder_name]);

        if (Input::param() != array()) {
            $result = Model_Folders::res_folder_db('update', [Input::param('folder'), $folder['id']]);
            if ($result === false) {
                $view->set(['err_msg' => 'すでに存在しているフォルダー名です。別のフォルダー名を入力して下さい。']);
                return;
            } else {
                Response::redirect("pages/index");
            }
        }
    }

    public function action_delete_folder($folder_id = null)
    {
        $folders = Model_Folders::res_folder_db('get_by_id', $folder_id);
        if (!$folders) {
            throw new HttpNotFoundException();
        }
        Model_Folders::res_folder_db('logical_delete', $folder_id);
        Response::redirect("pages/index");
    }
}
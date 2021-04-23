<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\HttpNotFoundException;
use Fuel\Core\Input;
use Fuel\Core\PhpErrorException;
use Fuel\Core\Response;
use Fuel\Core\View;

class Controller_Pages extends Controller_Template
{
    public $template = 'layout';
    public function action_index()
    {
        $view = View::forge('pages/index');
        $title_name = "Store Pages App index-page";
        $this->template->title = $title_name;
        $this->template->disp_sidebar = true;
        $this->template->content = $view;
        
        $folders_data = Model_Folders::get_data('get_all');
        $this->template->folders = $folders_data;
        $arr_folder = Model_Folders::get_data('get_arr_for_select', $folders_data);
        $this->template->arr_folder = $arr_folder;

        $pages_not_in_folder = Model_Pages::desc_updated_at();
        
        $pages_in_folder = array();
        if ($folders_data) {
            foreach ($folders_data as $folder_data) {
                $pages_in_folder = array_merge($pages_in_folder, array($folder_data['name'] => array()));
            }
            foreach ($folders_data as $folder_data) {
                $pages_in_folder[$folder_data['name']] = Model_Pages::desc_updated_at($folder_data['id']);
            }
        }

        $view->set(array('err_msg' => null,
                         'title_name' => $title_name,
                         'pages_not_in_folder' => $pages_not_in_folder,
                         'pages_in_folder_arr' => $pages_in_folder));

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
                    'title_name' => $title_name,
                    'pages_not_in_folder' => $pages_not_in_folder,
                    'pages_in_folder_arr' => $pages_in_folder));
                    return;
                }
            } else {
                $target_url = Input::param('url');
                try {
                    $html_source = file_get_contents($target_url);
                } catch (PhpErrorException $exc) {
                    $view->set(array('err_msg' => $exc->getMessage(),
                    'title_name' => $title_name,
                    'pages_not_in_folder' => $pages_not_in_folder,
                    'pages_in_folder_arr' => $pages_in_folder));
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
            if (Input::param('folder') != null) {
                $selected_folder = Model_Folders::get_data('get_by_name', Input::param('folder'));
                $page->folder_id = $selected_folder[0]['id'];
            }
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
                Response::redirect("pages/index");
            }
        }
    }
    
    public function action_add_page()
    {
        $folders_data = Model_Folders::get_data('get_all');
        $this->template->folders = $folders_data;
        $arr_folder = Model_Folders::get_data('get_arr_for_select', $folders_data);
        $this->template->arr_folder = $arr_folder;

        $view = View::forge('pages/add_page', ['err_msg' => null,
                                               'title_not_getted' => null,
                                               'folders' => $folders_data,
                                               'arr_folder' => $arr_folder]);
        $this->template->title = 'Page add page';
        $this->template->disp_sidebar = true;
        $this->template->content = $view;
                            
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
                                     'title_not_getted' => true,
                                     'folders' => $folders_data,
                                     'arr_folder' => $arr_folder));
                    return;
                }
            } else {
                $target_url = Input::param('url');
                try {
                    $html_source = file_get_contents($target_url);
                } catch (PhpErrorException $exc) {
                    $view->set(array('err_msg' => $exc->getMessage(),
                                     'title_not_getted' => true,
                                     'folders' => $folders_data,
                                     'arr_folder' => $arr_folder));
                    return;
                }
                preg_match_all('{<title>(.*?)</title>}', $html_source, $matches);
                if (!isset($matches[1]) or count($matches[1]) === 0) {
                    $view->set(['err_msg' => null,
                                'title_not_getted' => true,
                                'folders' => $folders_data,
                                'arr_folder' => $arr_folder]);
                    return;
                } else {
                    if (count($matches[1]) >= 2) {
                        $page->title = array($matches[1][0]);
                    } else {
                        $page->title = $matches[1];
                    }
                }
            }
            if (Input::param('folder') != null) {
                $selected_folder = Model_Folders::get_data('get_by_name', Input::param('folder'));
                $page->folder_id = $selected_folder[0]['id'];
            }
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
                Response::redirect("pages/index");
            }
        }
    }
                            
    public function action_edit_page($id = null)
    {
        // $titleを引数とすると、viewから値が改変されて渡ってくる場合がある→なぜ？
        // $page = Model_Pages::find_by('title', $title);
        // 元々、上記コードでの実装をしており、これは配列で返す（find_by_pkはインスタンスで返す）

        $folders_data = Model_Folders::get_data('get_all');
        $this->template->folders = $folders_data;
        $arr_folder = Model_Folders::get_data('get_arr_for_select', $folders_data);
        $this->template->arr_folder = $arr_folder;

        $page = array(Model_Pages::find_by_pk($id));
        if (!($page)) {
            throw new HttpNotFoundException();
        }
        
        $page_id = $id;
        $title = $page[0]->title;
        $page_url = $page[0]->url;
        $page_folder_id = $page[0]->folder_id;
        if ($page_folder_id) {
            $folder_name = Model_Folders::get_data('get_by_id', $page_folder_id)[0]['name'];
        } else {
            $folder_name = null;
        }
        $view = View::forge('pages/edit_page', ['err_msg' => null,
                                               'page_title' => $title,
                                               'page_url' => $page_url,
                                               'page_folder' => $folder_name,
                                               'folders' => $folders_data,
                                               'arr_folder' => $arr_folder]);
        $this->template->title = 'Edit page form';
        $this->template->disp_sidebar = true;
        $this->template->content = $view;
                                                    
        if (Input::param() != array()) {
            if (Input::param('folder') == null) {
                $page = Model_Pages::forge()->set(['id' => $page_id,
                    'folder_id' => null,
                    'title' => Input::param('title'),
                    'url' => Input::param('url'),
                    'updated_at' => date("Y/m/d H:i:s")])->is_new(false);
                    
                if ($page->validates()) {
                    $page->save();
                    Response::redirect('pages/index');
                }
            } else {
                $page = Model_Pages::forge()->set(['id' => $page_id,
                    'folder_id' => Model_Folders::get_data('get_by_name', Input::param('folder'))[0]['id'],
                    'title' => Input::param('title'),
                    'url' => Input::param('url'),
                    'updated_at' => date("Y/m/d H:i:s")])->is_new(false);
                    
                if ($page->validates()) {
                    $page->save();
                    Response::redirect('pages/index');
                }
            }
        }
    }
                                                    
    public function action_delete_page($id = null)
    {
        $page = array(Model_Pages::find_by_pk($id));
        if (!($page)) {
            throw new HttpNotFoundException();
        }
        
        $page = Model_Pages::forge()->set(array('id' => $id, 'deleted_at' => date("Y/m/d H:i:s")))->is_new(false);
        if ($page->validates()) {
            $page->save();
            Response::redirect('pages/index');
        }
    }
}
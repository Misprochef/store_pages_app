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
        
        $folders_data = Model_Folders::get_all();
        $this->template->folders = $folders_data;

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

        function getFavIndex($page_data)
        {
            $save_dir = sprintf(dirname(__FILE__, 5) . '/public/assets/img/%s', $page_data->title[0]);
            if (!file_exists($save_dir)) {
                mkdir($save_dir);
            }

            $domain = parse_url($page_data->url)['host'];
            $data = @file_get_contents("http://www.google.com/s2/favicons?domain=$domain");
            $fav_path = sprintf('%s/fav_%s.png', $save_dir, $domain);
            @file_put_contents($fav_path, $data);
            $save_dir_for_asset_class = sprintf('%s/fav_%s.png', $page_data->title[0], $domain);
            return array('img_type' => 'favicon', 'path' => $save_dir_for_asset_class);
        }

        function getImgIndex($page_data)
        {
            $html_source = file_get_contents($page_data->url);

            if ($html_source == null or $html_source == '') {
                throw new Exception('Failed to get html source from url.');
            }
            preg_match_all('/<meta property="og:image" content="(.*?)"/', $html_source, $matches);
            $is_in_page_img = false;
            
            if (!isset($matches[1]) or count($matches[1]) === 0) {
                preg_match_all('/src="(.*?(\.jpg|\.jpeg|\.png|\.avif|\.webp))"/i', $html_source, $matches);
                $is_in_page_img = true;
                if (!isset($matches[1]) or count($matches[1]) === 0) {
                    $is_in_page_img = false;
                    return getFavIndex($page_data);
                }
            }
            
            $save_dir = sprintf(dirname(__FILE__, 5) . '/public/assets/img/%s', $page_data->title[0]);
            if (!file_exists($save_dir)) {
                mkdir($save_dir);
            }

            $base_tmp = explode('/', $page_data->url);
            $base = sprintf('%s/%s/%s', $base_tmp[0], $base_tmp[1], $base_tmp[2]);

            foreach ($matches[1] as $k => $img_url) {
                $fname_tmp = explode('/', $img_url);
                $fname_tmp = array_reverse($fname_tmp);
                if ($is_in_page_img) {
                    $fpath = sprintf('%s/%s_%s', $save_dir, $k, $fname_tmp[0]);
                } else {
                    $fpath = sprintf('%s/%s_%s', $save_dir, 'og_img_', $fname_tmp[0]);
                }

                if (!preg_match('/^https?:\/\//', $img_url)) {
                    $img_url = sprintf('%s/%s', $base, $img_url);
                }

                $data = @file_get_contents($img_url);
                if ($data) {
                    @file_put_contents($fpath, $data);
                } else {
                    return getFavIndex($page_data);
                }
                
                if ($is_in_page_img) {
                    $save_dir_for_asset_class = sprintf('%s/%s_%s', $page_data->title[0], $k, $fname_tmp[0]);
                    return array('img_type' => 'in_page_img', 'path' => $save_dir_for_asset_class);
                } else {
                    $save_dir_for_asset_class = sprintf('%s/%s_%s', $page_data->title[0], 'og_img_', $fname_tmp[0]);
                    return array('img_type' => 'og_img', 'path' => $save_dir_for_asset_class);
                }
            }
        }

        if (Input::param() != array()) {
            $page = Model_Pages::forge();
            $page->url = Input::param('url');
            if (Input::param('title') != "") {
                $page->title = Input::param('title');
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
                    $page->title = $matches[1];
                }
            }
            if (Input::param('folder') != null) {
                $selected_folder = Model_Folders::get_by_name(Input::param('folder'));
                $page->folder_id = $selected_folder[0]['id'];
            }
            $page->created_at = date("Y/m/d H:i:s");
            $page->updated_at = date("Y/m/d H:i:s");
            
            $ret_arr = getImgIndex($page);
            if (!$ret_arr) {
                // このケースは存在しないはずだが、一応分岐を用意
                // 処理方法として、viewファイル内に、imgが取得できなかったと表示
            } elseif ($ret_arr['img_type'] == 'favicon') {
                $page->fav_path = $ret_arr['path'];
            } elseif ($ret_arr['img_type'] == 'in_page_img' or 'og_img') {
                $page->img_path = $ret_arr['path'];
            }
            if ($page->validates()) {
                $page->save();
                Response::redirect("pages/index");
            }
        }
    }
    
    public function action_add_page()
    {
        $folders_data = Model_Folders::get_all();
        $this->template->folders = $folders_data;

        $view = View::forge('pages/add_page', ['err_msg' => null,
                                               'title_not_getted' => null,
                                               'folders' => $folders_data]);
        $this->template->title = 'Page add page';
        $this->template->disp_sidebar = true;
        $this->template->content = $view;

        function getFav($page_data)
        {
            $save_dir = sprintf(dirname(__FILE__, 5) . '/public/assets/img/%s', $page_data->title[0]);
            if (!file_exists($save_dir)) {
                mkdir($save_dir);
            }

            $domain = parse_url($page_data->url)['host'];
            $data = @file_get_contents("http://www.google.com/s2/favicons?domain=$domain");
            $fav_path = sprintf('%s/fav_%s.png', $save_dir, $domain);
            @file_put_contents($fav_path, $data);
            $save_dir_for_asset_class = sprintf('%s/fav_%s.png', $page_data->title[0], $domain);
            return array('img_type' => 'favicon', 'path' => $save_dir_for_asset_class);
        }

        function getImg($page_data)
        {
            $html_source = file_get_contents($page_data->url);

            if ($html_source == null or $html_source == '') {
                throw new Exception('Failed to get html source from url.');
            }
            preg_match_all('/<meta property="og:image" content="(.*?)"/', $html_source, $matches);
            $is_in_page_img = false;
            
            if (!isset($matches[1]) or count($matches[1]) === 0) {
                preg_match_all('/src="(.*?(\.jpg|\.jpeg|\.png|\.avif|\.webp))"/i', $html_source, $matches);
                $is_in_page_img = true;
                if (!isset($matches[1]) or count($matches[1]) === 0) {
                    $is_in_page_img = false;
                    return getFav($page_data);
                }
            }
            
            $save_dir = sprintf(dirname(__FILE__, 5) . '/public/assets/img/%s', $page_data->title[0]);
            if (!file_exists($save_dir)) {
                mkdir($save_dir);
            }

            $base_tmp = explode('/', $page_data->url);
            $base = sprintf('%s/%s/%s', $base_tmp[0], $base_tmp[1], $base_tmp[2]);

            foreach ($matches[1] as $k => $img_url) {
                $fname_tmp = explode('/', $img_url);
                $fname_tmp = array_reverse($fname_tmp);
                if ($is_in_page_img) {
                    $fpath = sprintf('%s/%s_%s', $save_dir, $k, $fname_tmp[0]);
                } else {
                    $fpath = sprintf('%s/%s_%s', $save_dir, 'og_img_', $fname_tmp[0]);
                }

                if (!preg_match('/^https?:\/\//', $img_url)) {
                    $img_url = sprintf('%s/%s', $base, $img_url);
                }

                $data = @file_get_contents($img_url);
                if ($data) {
                    @file_put_contents($fpath, $data);
                } else {
                    return getFav($page_data);
                }
                
                if ($is_in_page_img) {
                    $save_dir_for_asset_class = sprintf('%s/%s_%s', $page_data->title[0], $k, $fname_tmp[0]);
                    return array('img_type' => 'in_page_img', 'path' => $save_dir_for_asset_class);
                } else {
                    $save_dir_for_asset_class = sprintf('%s/%s_%s', $page_data->title[0], 'og_img_', $fname_tmp[0]);
                    return array('img_type' => 'og_img', 'path' => $save_dir_for_asset_class);
                }
            }
        }
                            
        if (Input::param() != array()) {
            $page = Model_Pages::forge();
            $page->url = Input::param('url');
            if (Input::param('title') != "") {
                $page->title = Input::param('title');
            } else {
                $target_url = Input::param('url');
                try {
                    $html_source = file_get_contents($target_url);
                } catch (PhpErrorException $exc) {
                    $view->set(array('err_msg' => $exc->getMessage(),
                                     'title_not_getted' => true,
                                     'folders' => $folders_data));
                    return;
                }
                preg_match_all('{<title>(.*?)</title>}', $html_source, $matches);
                if (!isset($matches[1]) or count($matches[1]) === 0) {
                    $view->set(['err_msg' => null,
                                'title_not_getted' => true,
                                'folders' => $folders_data]);
                    return;
                } else {
                    $page->title = $matches[1];
                }
            }
            if (Input::param('folder') != null) {
                $selected_folder = Model_Folders::get_by_name(Input::param('folder'));
                $page->folder_id = $selected_folder[0]['id'];
            }
            $page->created_at = date("Y/m/d H:i:s");
            $page->updated_at = date("Y/m/d H:i:s");
            
            $ret_arr = getImg($page);
            if (!$ret_arr) {
                // このケースは存在しないはずだが、一応分岐を用意
                // 処理方法として、viewファイル内に、imgが取得できなかったと表示
            } elseif ($ret_arr['img_type'] == 'favicon') {
                $page->fav_path = $ret_arr['path'];
            } elseif ($ret_arr['img_type'] == 'in_page_img' or 'og_img') {
                $page->img_path = $ret_arr['path'];
            }
            if ($page->validates()) {
                $page->save();
                Response::redirect("pages/index");
            }
        }
    }
                            
    public function action_edit_page($title = null)
    {
        $folders_data = Model_Folders::get_all();
        $this->template->folders = $folders_data;

        $page = Model_Pages::find_by('title', $title);
        if (!($page)) {
            throw new HttpNotFoundException();
        }

        $page_id = $page[0]->id;
        $page_url = $page[0]->url;
        $page_folder_id = $page[0]->folder_id;
        if ($page_folder_id) {
            $folder_name = Model_Folders::get_by_id($page_folder_id)[0]['name'];
        } else {
            $folder_name = null;
        }
        $view = View::forge('pages/edit_page', ['err_msg' => null,
                                               'page_title' => $title,
                                               'page_url' => $page_url,
                                               'page_folder' => $folder_name,
                                               'folders' => $folders_data]);
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
                    'folder_id' => Model_Folders::get_by_name(Input::param('folder'))[0]['id'],
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
                                                    
    public function action_delete_page($title = null)
    {
        $page = Model_Pages::find_by('title', $title);
        if (!($page)) {
            throw new HttpNotFoundException();
        }
        
        $page_id = $page[0]->id;
        $page = Model_Pages::forge()->set(array('id' => $page_id, 'deleted_at' => date("Y/m/d H:i:s")))->is_new(false);
        if ($page->validates()) {
            $page->save();
            Response::redirect('pages/index');
        }
    }
}
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
        
        $folders_data = Model_Folders::get_all();
        $view->set('folders', $folders_data);
    }

    public function action_folder_pages($folder_name = null)
    {
        $view = View::forge("folders/folder_pages");
        $title_name = "Store Pages App フォルダー内のページ一覧";
        $this->template->title = $title_name;
        $this->template->folder_name = $folder_name;
        $this->template->folders = Model_Folders::get_all();
        $this->template->disp_sidebar = true;
        $this->template->content = $view;

        $folder_id = Model_Folders::get_by_name($folder_name)[0]['id'];
        $pages_data = Model_Pages::desc_updated_at($folder_id);
        $view->set(array('err_msg' => null, 'pages' => $pages_data, 'title_name' => $title_name, 'folder_name' => $folder_name));

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
                    $view->set(
                        array('err_msg' => $exc->getMessage(),
                                    'pages' => $pages_data,
                                     'title_name' => $title_name,
                                     'folder_name' => $folder_name,)
                    );
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
            $page->folder_id = Model_Folders::get_by_name(Input::param('folder'))[0]['id'];
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
                Response::redirect("folders/folder_pages/$folder_name");
            }
        }
    }

    public function action_add_folder()
    {
        $view = View::forge('folders/add_folder');
        $this->template->title = "Store Pages App folderの追加";
        $this->template->folders = Model_Folders::get_all();
        $this->template->disp_sidebar = true;
        $this->template->content = $view;

        $view->set(['err_msg' => null]);

        if (Input::param() != array()) {
            $result = Model_Folders::insert(Input::param('folder'));
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
        $this->template->folders = Model_Folders::get_all();
        $this->template->disp_sidebar = true;
        $this->template->content = $view;

        $folder = Model_Folders::get_by_name($folder_name)[0];
        if (!$folder) {
            throw new HttpNotFoundException();
        }

        $view->set(['err_msg' => null, 'folder_name' => $folder_name]);

        if (Input::param() != array()) {
            $result = Model_Folders::update(Input::param('folder'), $folder['id']);
            if ($result === false) {
                $view->set(['err_msg' => 'すでに存在しているフォルダー名です。別のフォルダー名を入力して下さい。']);
                return;
            } else {
                Response::redirect("pages/index");
            }
        }
    }

    public function action_delete_folder($folder_name = null)
    {
        $folders = Model_Folders::get_by_name($folder_name);
        if (!$folders) {
            throw new HttpNotFoundException();
        }
        Model_Folders::logical_delete($folder_name);
        Response::redirect("pages/index");
    }
}
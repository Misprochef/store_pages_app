<?php

use Fuel\Core\Model_Crud;

class Model_Pages extends Model_Crud
{
    protected static $_table_name = 'pages';
    protected static $_primary_key = 'id';

    public static function desc_updated_at($folder_id = null)
    {
        if ($folder_id) {
            $results = DB::select()->from('pages')
            ->where_open()->where('deleted_at', '=', null)
            ->and_where('folder_id', '=', $folder_id)
            ->where_close()->order_by('updated_at', '=', 'desc')
            ->execute();
            return $results->as_array();
        } else {
            $results = DB::select()->from('pages')
            ->where_open()->where('deleted_at', '=', null)
            ->and_where('folder_id', '=', null)
            ->where_close()->order_by('updated_at', '=', 'desc')
            ->execute();
            return $results->as_array();
        }
    }
}

class Model_Get_Img extends Model
{
    public static function getFav($page_data)
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

    public static function getImg($page_data)
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
                return self::getFav($page_data);
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
                return self::getFav($page_data);
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
}
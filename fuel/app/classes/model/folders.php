<?php

use Fuel\Core\DB;
use Fuel\Core\Model;

class Model_Folders extends Model
{
    public static function get_data($type = null, $folder_param = null)
    {
        $query = DB::select()->from('folders')->where('deleted_at', '=', null);
        
        if ($type === 'all') {
            $results = $query->execute();
        } elseif ($type === 'id' or $type === 'name') {
            $results = $query
            ->and_where($type, '=', $folder_param)
            ->execute();
        }
        return $results->as_array();
    }

    public static function get_arr_for_select($folders_data)
    {
        $arr_folder = [null => '登録しない'];
        if (!$folders_data) {
            return $arr_folder;
        } else {
            foreach ($folders_data as $folder) {
                $arr_folder = array_merge($arr_folder, [$folder['name'] => $folder['name']]);
            }
            return $arr_folder;
        }
    }

    public static function insert($folder_name)
    {
        $query_selected = DB::select()->from('folders');
        $search_result = $query_selected
                         ->where('name', '=', $folder_name)
                         ->and_where('deleted_at', '=', null)
                         ->execute();
        if ($search_result->as_array()) {
            return false;
        } else {
            DB::insert('folders')
            ->set(['name' => $folder_name])
            ->execute();
        }
    }

    public static function update($new_folder_name, $folder_id)
    {
        $query_selected = DB::select()->from('folders');
        $search_result = $query_selected
                         ->where('name', '=', $new_folder_name)
                         ->and_where('deleted_at', '=', null)
                         ->execute();
        if ($search_result->as_array()) {
            return false;
        } else {
            DB::update('folders')
            ->set(['name' => $new_folder_name])
            ->where('id', '=', $folder_id)
            ->execute();
        }
    }

    public static function logical_delete($folder_id)
    {
        DB::update('folders')
        ->set(['deleted_at' => date("Y/m/d H:i:s")])
        ->where('id', '=', $folder_id)
        ->execute();
    }
}
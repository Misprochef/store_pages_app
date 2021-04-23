<?php

use Fuel\Core\DB;
use Fuel\Core\Model;

class Model_Folders extends Model
{
    public static function get_data($type_of_operation = null, $folder_param = null)
    {
        $query_selected = DB::select()->from('folders');
        if ($type_of_operation === 'get_all') {
            $results = $query_selected
            ->where('deleted_at', '=', null)
            ->execute();
        } elseif ($type_of_operation === 'get_arr_for_select') {
            $arr_folder = [null => '登録しない'];
            $folders = $folder_param;
            if (!$folders) {
                return $arr_folder;
            } else {
                foreach ($folders as $folder) {
                    $arr_folder = array_merge($arr_folder, [$folder['name'] => $folder['name']]);
                }
                return $arr_folder;
            }
        } elseif ($type_of_operation === 'get_by_name') {
            $results = $query_selected
            ->where('name', '=', $folder_param)
            ->and_where('deleted_at', '=', null)
            ->execute();
        } elseif ($type_of_operation === 'get_by_id') {
            $results = $query_selected
            ->where('id', '=', $folder_param)
            ->and_where('deleted_at', '=', null)
            ->execute();
        }
        return $results->as_array();
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
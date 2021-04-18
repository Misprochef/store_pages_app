<?php

use Fuel\Core\DB;
use Fuel\Core\Model;

class Model_Folders extends Model
{
    public static function get_all()
    {
        $results = DB::select()->from('folders')
        ->where('deleted_at', '=', null)->execute();
        return $results->as_array();
    }

    public static function get_by_name($folder_name)
    {
        $results = DB::select()->from('folders')
        ->where('name', '=', $folder_name)->limit(1)->execute();
        return $results->as_array();
    }

    public static function get_by_id($folder_id)
    {
        $results = DB::select()->from('folders')
        ->where('id', '=', $folder_id)->execute();
        return $results->as_array();
    }

    public static function insert($folder_name)
    {
        $search_result = DB::select()->from('folders')
        ->where_open()->where('name', '=', $folder_name)
        ->and_where('deleted_at', '=', null)->where_close()->execute();
        if ($search_result->as_array()) {
            return false;
        } else {
            DB::insert('folders')->set(['name' => $folder_name])->execute();
        }
    }

    public static function update($new_folder_name, $folder_id)
    {
        $search_result = DB::select()->from('folders')
        ->where_open()->where('name', '=', $new_folder_name)
        ->and_where('deleted_at', '=', null)->where_close()->execute();
        if ($search_result->as_array()) {
            return false;
        } else {
            DB::update('folders')->set(['name' => $new_folder_name])
            ->where('id', '=', $folder_id)->execute();
        }
    }

    public static function logical_delete($folder_name)
    {
        DB::update('folders')->set(['deleted_at' => date("Y/m/d H:i:s")])
        ->where('name', '=', $folder_name)->execute();
    }
}
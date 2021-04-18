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
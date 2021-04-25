<?php

use Fuel\Core\DB;
use Fuel\Core\Model_Crud;

class Model_Pages extends Model_Crud
{
    protected static $_table_name = 'pages';
    protected static $_primary_key = 'id';

    public static function desc_updated_at($folder_id = null)
    {
        $query = DB::select()->from('pages')
                 ->where('deleted_at', '=', null)
                 ->and_where('folder_id', '=', $folder_id);
            
        $page_order_exist = DB::select()->from('pages')
                            ->where('deleted_at', '=', null)
                            ->and_where('folder_id', '=', $folder_id)
                            ->and_where('page_order', '!=', null);
    
        if ($page_order_exist) {
            $results = $query->order_by('page_order')->execute();
        } else {
            $results = $query->order_by('updated_at', '=', 'desc')->execute();
        }
        return $results->as_array();
    }
}
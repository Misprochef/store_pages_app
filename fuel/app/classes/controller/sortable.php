<?php

use Fuel\Core\Controller_Rest;

class Controller_Sortable extends Controller_Rest
{
    public function post_cards()
    {
        $sorted_pages = $_POST['cards'];
        
        foreach ($sorted_pages as $sorted_page) {
            $page = Model_Pages::forge()->set([
            'id' => $sorted_page['id'],
            'page_order' => $sorted_page['page_order']
            ])->is_new(false);
            
            if ($page->validates()) {
                $page->save();
            }
        }
          
        return $this->response($sorted_pages, 200);
    }
}
<?php
return array(
    '_root_' => 'pages/index', // default roure
    '_404_' => 'welcome/404', // page not found
    /**
     * -------------------------------------------------------------------------
     *  Example for Presenter
     * -------------------------------------------------------------------------
     *  A route for showing page using Presenter
     */
    'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);
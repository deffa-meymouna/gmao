<?php
$settings = [
    /**
     * items per page that can be displayed
     * you must have at least 1 element in array
     * 
     * @var array of integer
     */
    'items_per_page' => [15,30,50],
    /**
     * items per page displayed by default
     *
     * @var integer
     */
    'items_per_page_default' => 15,
];
/**
 * You do not need to edit below this line
 */
return array(
    'administration' => $settings,
);

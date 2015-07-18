<?php
namespace Administration\Options;

/**
 *
 * @author alexandre
 *        
 */
interface ItemsPerPageOptionsInterface
{
    /**
     *
     * @param array of integer $itemPerPage
     * @return \Administration\Options\ModuleOptions
     */
    public function setItemsPerPage($itemPerPage);
    /**
     * get items that can be displayed per page
     *
     * @return array of integer
     */
    public function getItemsPerPage();
}

?>
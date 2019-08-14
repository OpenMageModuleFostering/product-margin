<?php
class Studio45_Productmargin_Model_Mysql4_Forms_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('productmargin/forms');
    }   
}
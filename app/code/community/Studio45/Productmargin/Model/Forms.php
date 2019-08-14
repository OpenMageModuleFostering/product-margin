<?php
class Studio45_Productmargin_Model_Forms extends Mage_Core_Model_Abstract
{
    protected $_currentFormId=0;

    public function _construct()
    {
        parent::_construct();
        $this->_init('productmargin/forms');
    }
}
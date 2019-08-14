<?php
class Studio45_Productmargin_Block_Adminhtml_Productmargin extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_productmargin';
    $this->_blockGroup = 'productmargin';
    $this->_headerText = Mage::helper('productmargin')->__('Manage Product Margin');
    $this->_addButtonLabel = Mage::helper('productmargin')->__('Add Product Margin');
    parent::__construct();
  }
}
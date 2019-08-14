<?php
class Studio45_Productmargin_Block_Adminhtml_Productmargin_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('productmargin_tabs');
	    $this->setName('productmargin_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('productmargin')->__('Product Information'));
  }
  protected function _beforeToHtml()
  {
      $this->addTab('general_section', array(
          'label'     => Mage::helper('productmargin')->__('Product Information'),
          'title'     => Mage::helper('productmargin')->__('Product Information'),
          'content'   => $this->getLayout()->createBlock('productmargin/adminhtml_productmargin_edit_tab_form')->toHtml(),
      ));

		 return parent::_beforeToHtml();
  }
}
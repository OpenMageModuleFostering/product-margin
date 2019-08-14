<?php
class Studio45_Productmargin_Block_Adminhtml_Productmargin_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'productmargin';
        $this->_controller = 'adminhtml_productmargin';
        
        $this->_updateButton('save', 'label', Mage::helper('productmargin')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('productmargin')->__('Delete'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('productmargin_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'productmargin_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'productmargin_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
    public function getHeaderText()
    {
        if( Mage::registry('productmargin_data') && Mage::registry('productmargin_data')->getFormsIndex() )
        {
            $currentFormId = Mage::registry('productmargin_data')->getFormsIndex();
            Mage::getSingleton('core/session')->setCurrentFormId($currentFormId);
            return Mage::helper('productmargin')->__("Edit '%s'", $this->htmlEscape(Mage::registry('productmargin_data')->getProductName()));            
        }
        else
        {
            Mage::getSingleton('core/session')->setCurrentFormId(0);
            return Mage::helper('productmargin')->__('Add Product Margin');
        }
    }
}
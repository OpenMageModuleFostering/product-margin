<?php
class Studio45_Productmargin_Helper_Data extends Mage_Core_Helper_Abstract
{
	protected $_formData;
    
    public function setFormData($data)
    {
        $this->_formData = $data;
    }
    public function getFormData()
    {
        return $this->_formData;
    }
    public function getRedirectUrl()
    {
        return Mage::getStoreConfig('productmargin_section/form_submission/redirect_url');
    }
    
    public function getFormsModel()
    {
        return Mage::getModel('productmargin/forms');
    }
    
    public function isEnabled()
    {
        return Mage::getStoreConfig('productmargin_section/general/active');
    }
    
    public function showLinkinTopmenu()
    {
        return Mage::getStoreConfig('productmargin_section/general/in_topmenu');
    }
   
    public function getFormCollection()
    {
        $formCollection = array();
        $formCollection = Mage::getModel('productmargin/forms')->getCollection();
        return $formCollection;
    }
   
    public function getCurrentFormId()
    {
        $sessionFormId = intval(Mage::getSingleton('core/session')->getCurrentFormId());
        if(is_int($currentFormId = $sessionFormId))
            return $currentFormId;
    }
    
}
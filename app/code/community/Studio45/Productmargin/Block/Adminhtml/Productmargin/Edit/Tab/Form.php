<?php
class Studio45_Productmargin_Block_Adminhtml_Productmargin_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$model = Mage::registry('productmargin_data');
		
		if($model->getStores())
		{		
			$model->setStores(explode(',',$model->getStores()));
		}
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('productmargin_form', array('legend'=>Mage::helper('productmargin')->__('Product Information')));

		$_productCollection = Mage::getModel('catalog/product')
                        ->getCollection()
                        ->addAttributeToSort('created_at', 'DESC')
                        ->addAttributeToSelect('*')
                        ->load();
	   $array[] = array('label' => 'Select Product', 'value' => '');        
		foreach ($_productCollection as $_product)
		{
		   $getSku = $_product->getSku();
		   $getName = $_product->getName();

		   $array[] = array('label' => $getName.'  (sku-'.$getSku.')', 'value' => $getSku.'||'.$getName);
		}

		$fieldset->addField('title', 'select', array(
			'label'     => Mage::helper('productmargin')->__('Select Product'),
			'name'      => 'title',
			'class'     => 'required-entry',
			'required' => true,
			'values'    => $array,
		));

		$fieldset->addField('ebay_price','text',array(
                'label'     => Mage::helper('productmargin')->__('Ebay Product Price'),
                'name'      => 'ebay_price',
                'class'     => 'required-entry validate-number',
                'required' => true,             
        ));

		if (Mage::getSingleton('adminhtml/session')->getFormData())
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getFormData());
			Mage::getSingleton('adminhtml/session')->setFormData(null);
		} elseif ( Mage::registry('productmargin_data') ) {
			$form->setValues(Mage::registry('productmargin_data')->getData());
		}
		return parent::_prepareForm();
	}
}
<?php
class Studio45_Productmargin_Adminhtml_ProductmarginController extends Mage_Adminhtml_Controller_Action
{
    public function fieldsgridAction()
    {
        echo $this->getLayout()->createBlock('productmargin/adminhtml_productmargin_edit_tab_fieldsgrid')->toHtml();
    }
    public function recordsgridAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('productmargin/adminhtml_productmargin_edit_tab_recordsgrid'));
        $this->renderLayout();
    }
	public function optionsAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }	
	public function getBaseTmpMediaUrl()
    {
        return Mage::getBaseUrl('media') . 'productmargin';
    }	
	public function getBaseTmpMediaPath()
    {
        return Mage::getBaseDir('media') . DS . 'productmargin';
    }	
	protected function _initAction()
	{
		$this->loadLayout()
		->_setActiveMenu('productmargin/items')
		->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Form Manager'));
		
		return $this;
	}
	public function indexAction()
	{
		$this->_initAction()
		->renderLayout();
	}
	
	public function editAction()
	{			    
	    $this->_title($this->__("Form"));
		$this->_title($this->__("Form Listing"));
	    $this->_title($this->__("Edit Form"));
		
		$id = $this->getRequest()->getParam("id");
		$model = Mage::getModel("productmargin/forms")->load($id);

		if ($model->getFormsIndex() || $id==0)
		{
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data))
			{
				$model->setData($data);
			}
			Mage::register("productmargin_data", $model);
			$this->loadLayout();
			$this->_setActiveMenu("productmargin/items");
			$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Form Manager"), Mage::helper("adminhtml")->__("Form Manager"));
			$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Form Description"), Mage::helper("adminhtml")->__("Form Description"));
			$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
			$this->_addContent($this->getLayout()->createBlock("productmargin/adminhtml_productmargin_edit"))->_addLeft($this->getLayout()->createBlock("productmargin/adminhtml_productmargin_edit_tabs"));
			$this->renderLayout();
		} 
		else {
			Mage::getSingleton("adminhtml/session")->addError(Mage::helper("productmargin")->__("Form does not exist."));
			$this->_redirect("*/*/");
		}
	}
	public function newAction()
	{
		$this->_title($this->__("Form"));
		$this->_title($this->__("New Form"));

	    $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("productmargin/forms")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}
		Mage::register("productmargin_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("productmargin/items");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Form Manager"), Mage::helper("adminhtml")->__("Form Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Form Description"), Mage::helper("adminhtml")->__("Form Description"));

		$this->_addContent($this->getLayout()->createBlock("productmargin/adminhtml_productmargin_edit"))->_addLeft($this->getLayout()->createBlock("productmargin/adminhtml_productmargin_edit_tabs"));

		$this->renderLayout();
	}
	public function saveAction()
	{
		if ($data = $this->getRequest()->getPost())
		{			
			$model = Mage::getModel("productmargin/forms")->load($this->getRequest()->getParam("id"));
			$currentFormId = $this->getRequest()->getParam("id");
			$_helper = Mage::helper("productmargin");

			try
			{				
			
				if(isset($data['stores']) && !empty($data['stores']))
				{			
					if(in_array('0',$data['stores'])){
						$data['stores'] = array(0);
					}				
					$data['stores'] = implode(',',$data['stores']);
				}
                if ($model->getCreatedTime() == NULL)
                {
                    $data['created_time'] = now();
                }
                $data['update_time'] = now();

                $skuPrd = explode("||", $_REQUEST['title']);
                $prdSku = $skuPrd[0];
                $prdName = $skuPrd[1];

                $data['product_name'] = $prdName;
                $data['product_sku'] = $prdSku;

				$_Prdproduct = Mage::getModel('catalog/product')->loadByAttribute('sku',$prdSku);
				$prdPrice = $_Prdproduct->getPrice();

				$ebayPrice = $_REQUEST['ebay_price'];

				if ($prdPrice > $ebayPrice)
				{
					$marginPrice = $prdPrice - $ebayPrice;
					$mrPrice = "- ".$marginPrice;
				}else if($prdPrice < $ebayPrice)
				{
					$marginPrice = $ebayPrice - $prdPrice;
					$mrPrice = "+ ".$marginPrice;
				}else if($prdPrice == $ebayPrice )
				{
					$mrPrice = "0";
				}else
				{
					$mrPrice = $ebayPrice - $prdPrice;
				}
				
				$data['margin_price'] = $mrPrice;
				$data['product_price'] = $prdPrice;

                $model->setData($data)
                	->setFormsIndex($this->getRequest()->getParam("id"))
                	->save();

				if(!$currentFormId)
					$currentFormId = $model->getFormsIndex();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productmargin')->__(' Product margin was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getFormsIndex()));
					return;
				}
				$this->_redirect('*/*/');
				return;
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productmargin')->__('Unable to find product margin information to save'));
		$this->_redirect('*/*/');
	}
	
	
	public function deleteAction() 
	{
		if( $this->getRequest()->getParam('id') > 0 )
		{
			try
			{
				$model = Mage::getModel('productmargin/forms');
				
				$model->setFormsIndex($this->getRequest()->getParam('id'))
				->delete();
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Successfully deleted'));
				$this->_redirect('*/*/');
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}
	public function massDeleteAction() 
	{
		$productmarginIds = $this->getRequest()->getParam('productmargin');
		if(!is_array($productmarginIds))
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Form(s)'));
		}
		else
		{
			try
			{
				foreach ($productmarginIds as $productmarginId)
				{
					$productmargin = Mage::getModel('productmargin/forms')->load($productmarginId);
					$productmargin->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(
				Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($productmarginIds)));
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}	
	public function massStatusAction()
	{
		$productmarginIds = $this->getRequest()->getParam('productmargin');
		if(!is_array($productmarginIds))
		{
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Form(s)'));
		}
		else
		{
			try
			{
				foreach ($productmarginIds as $productmarginId)
				{
					$productmargin = Mage::getSingleton('productmargin/forms')
					->load($productmarginId)
					->setStatus($this->getRequest()->getParam('status'))
					->setIsMassupdate(true)
					->save();
				}
				$this->_getSession()->addSuccess(
				$this->__('Total of %d record(s) were successfully updated', count($productmarginIds)));
			}
			catch (Exception $e)
			{
				$this->_getSession()->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}	
}
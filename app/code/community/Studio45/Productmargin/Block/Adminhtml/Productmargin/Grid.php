<?php
class Studio45_Productmargin_Block_Adminhtml_Productmargin_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('productmarginGrid');
      $this->setDefaultSort('forms_index');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }  
  protected function _prepareCollection()
  {
      $collection = Mage::getModel('productmargin/forms')->getResourceCollection();
   
      foreach ($collection as $view) {
          if ( $view->getStores() && $view->getStores() != 0 ) {
              $view->setStores(explode(',',$view->getStores()));
          } else {
              $view->setStores(array('0'));
          }
      }   
        $this->setCollection($collection);
        $this->_prepareTotals('product_price,ebay_price'); 

      return parent::_prepareCollection();
  }

    protected function _prepareTotals($columns = null){
        $columns=explode(',',$columns);
            if(!$columns){
            return;
        }

        $this->_countTotals = true;   
        $totals = new Varien_Object();
        $fields = array();
        foreach($columns as $column)
        {
            $fields[$column]    = 0; 
        } 
        foreach ($this->getCollection() as $item)
        {
            foreach($fields as $field=>$value)
            {
                $fields[$field]+=$item->getData($field);

            }
        }

        $totals->setData($fields);
        $this->setTotals($totals);
        return;
    }
  protected function _prepareColumns()
  {
      $this->addColumn('forms_index', array(
          'header'    => Mage::helper('productmargin')->__('ID'),
          'align'     =>'left',
          'width'     => '50px',
          'index'     => 'forms_index',
          'totals_label'      => $this->__('Total'),
      ));
      $this->addColumn('product_name', array(
          'header'    => Mage::helper('productmargin')->__('Product Name'),
          'align'     =>'left',
          'width'     => '250px',
          'index'     => 'product_name',
      ));
      $this->addColumn('product_sku', array(
          'header'    => Mage::helper('productmargin')->__('Product SKU'),          
          'align'     =>'left',
          'width'     => '50px',
          'index'     => 'product_sku',
      ));
      
      $this->addColumn('product_price', array(
          'header'    => Mage::helper('productmargin')->__('Product Price'),          
          'align'     =>'left',
          'width'     => '50px',
          'index'     => 'product_price',
      ));
      $this->addColumn('ebay_price', array(
          'header'    => Mage::helper('productmargin')->__('Ebay Product Price'),          
          'align'     =>'left',
          'width'     => '50px',
          'index'     => 'ebay_price',
      ));
      
      $this->addColumn('margin_price', array(
          'header'    => Mage::helper('productmargin')->__('Product Margin'),          
          'align'     =>'left',
          'width'     => '50px',
          'index'     => 'margin_price',
      ));

      $this->addColumn('created_time', array(
          'header'    => Mage::helper('productmargin')->__('Created Time'),
          'align'     => 'left',
          'width'     => '150px',
          'index'     => 'created_time',
      ));
      	  
        if ( !Mage::app()->isSingleStoreMode() )
        {
            $this->addColumn('stores', array(
                'header' => Mage::helper('productmargin')->__('Store View'),
                'width'     => '150px',
                'index' => 'stores',
                'type' => 'store',
                'store_all' => true,
                'store_view' => true,
                'sortable' => true,
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
            ));
        }
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('productmargin')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('productmargin')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    ),
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
                'totals_label'      => ''
        ));
		
	  
      return parent::_prepareColumns();
  }
    protected function _filterStoreCondition($collection, $column)
    {
        if ( !$value = $column->getFilter()->getValue() ) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('forms_index');
        $this->getMassactionBlock()->setFormFieldName('productmargin');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('productmargin')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('productmargin')->__('Are you sure?'),
             'totals_label'      => ''
        ));

        
        return $this;
    }
  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }
}
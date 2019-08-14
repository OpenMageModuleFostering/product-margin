<?php
class Studio45_Productmargin_Model_Observer
{
    public function addToTopmenu(Varien_Event_Observer $observer)
    {
        if(Mage::helper('productmargin')->showLinkinTopmenu())
        {
            $menu = $observer->getMenu();
            $tree = $menu->getTree();
         
            $node = new Varien_Data_Tree_Node(array(
                    'name'   => 'Productmargin',
                    'id'     => 'productmargin',
                    'url'    => Mage::getUrl('productmargin'),
            ), 'id', $tree, $menu);
         
            $menu->addChild($node);
         
            // Children menu items
            $collection = Mage::getModel('productmargin/forms')->getCollection();
            $collection->addFieldToFilter('status',array('eq'=>1));
            $collection->addFieldToFilter('in_menu',array('eq'=>1));
            foreach ($collection as $category)
            {
                $tree = $node->getTree();
                $data = array(
                    'name'   => $category->getTitle(),
                    'id'     => 'category-node-'.$category->getFormsIndex(),
                    'url'    => Mage::getUrl('productmargin/index/view').'id/'.$category->getFormsIndex(),
                );     
                $subNode = new Varien_Data_Tree_Node($data, 'id', $tree, $node);
                $node->addChild($subNode);
            }
        }
    }
}
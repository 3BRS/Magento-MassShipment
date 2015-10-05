<?php

/**
 * Observer class
 *
 * @category    Mage
 * @package     Keyup_MassShipment
 * @author      Vojtech Grec <vojtech.grec@keyup.eu>
 * @copyright   Copyright (c) 2015 Keyup IT Services (http://www.keyup.eu)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Keyup_MassShipment_Model_Observer {

    /**
     * Add new mass actions to Orders grid
     *
     * @param Varien_Event_Observer $observer
     */
    public function addMassAction(Varien_Event_Observer $observer) {

        $block = $observer->getEvent()->getBlock();

        if ($block instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction &&
                $block->getRequest()->getControllerName() == 'sales_order') {
            
            $helper = Mage::helper('keyup_massshipment');

            $block->addItem('keyup_massshipment_no_emails', array(
                'label' => $helper->__('Ship (no emails)'),
                'url' => Mage::helper("adminhtml")->getUrl('adminhtml/keyupShipment/massShip'),
                'confirm' => $helper->__('Are you sure?'),
            ));

            $block->addItem('keyup_massshipment_with_emails', array(
                'label' => $helper->__('Ship (with emails)'),
                'url' => Mage::helper("adminhtml")->getUrl('adminhtml/keyupShipment/massShipEmail'),
                'confirm' => $helper->__('Are you sure?'),
            ));
        }
    }

}

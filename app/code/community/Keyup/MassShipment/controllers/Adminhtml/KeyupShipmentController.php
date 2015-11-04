<?php

/**
 * Mass shipment controller
 *
 * @category    Mage
 * @package     Keyup_MassShipment
 * @author      Vojtech Grec <vojtech.grec@keyup.eu>
 * @copyright   Copyright (c) 2015 Keyup IT Services (http://www.keyup.eu)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Keyup_MassShipment_Adminhtml_KeyupShipmentController extends Mage_Adminhtml_Controller_Action {
    
    /**
     * Allow controller to be called by default.
     * @return boolean
     */
    protected function _isAllowed() {
        return true;
    }
    
    /**
     * TODO: Add ACL for actions.
     * @param type $action
     * @return type
    protected function _isAllowedAction($action) {
        return Mage::getSingleton('admin/session')->isAllowed(
            'sales/order/' . $action
        );
    }*/

    /**
     * Create shipments for specified orders
     *
     * @param  array $orderIds  order IDs (array of integers)
     * @param  bool  $sendEmail true to send customer notification
     * @return int              number of shipments created
     */
    protected function _shipOrders($orderIds, $sendEmail) {
        $ordersShipped = 0;

        $orders = Mage::getModel('sales/order')
                    ->getCollection()
                    ->addFieldToFilter('entity_id', array('in' => $orderIds));

        foreach ($orders as $order) {
            if ($order->canShip()) {
                $qtys = array();

                foreach ($order->getAllItems() as $item) {
                    if ($item->canShip()) {
                        $qtys[$item->getId()] = $item->getQtyToShip();
                    }
                }

                $shipment = $order->prepareShipment($qtys);

                if ($shipment) {
                    $shipment->register();

                    if ($sendEmail) {
                        $shipment->setEmailSent(true);
                    }

                    $shipment->getOrder()->setIsInProcess(true);
                    $transactionSave = Mage::getModel('core/resource_transaction')
                        ->addObject($shipment)
                        ->addObject($shipment->getOrder())
                        ->save();

                    if ($sendEmail) {
                        $shipment->sendEmail();
                    }

                    $ordersShipped++;
                }
            }
        }

        return $ordersShipped;
    }

    /**
     * Create shipments for orders specified by "order_ids" param
     *
     * @param  boolean  $sendEmail true to send customer notification
     * @return bool|int            number of shipments created or false
     */
    protected function _actionShipOrders($sendEmail = false) {
        $ids = $this->getRequest()->getParam('order_ids');

        try {
            return $this->_shipOrders($ids, $sendEmail);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Mass shipment action (no emails)
     */
    public function massShipAction() {
        if (($cnt = $this->_actionShipOrders()) !== false) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('keyup_massshipment')->__('Total of %d orders were shipped', (int) $cnt)
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('keyup_massshipment')->__('Error shipping orders')
            );
        }

        $this->_redirect('adminhtml/sales_order/index');
    }

    /**
     * Mass shipment action (with emails)
     */
    public function massShipEmailAction() {
        if (($cnt = $this->_actionShipOrders(true)) !== false) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('keyup_massshipment')->__('Total of %d orders were shipped (emails sent)', (int) $cnt)
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('keyup_massshipment')->__('Error shipping orders')
            );
        }

        $this->_redirect('adminhtml/sales_order/index');
    }
}
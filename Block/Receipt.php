<?php
class Cammino_Pagseguro_Block_Receipt extends Mage_Payment_Block_Form {
	
	private $_orderId;
	
	protected function _construct() {
		$session = Mage::getSingleton('checkout/session');
		$order = Mage::getModel("sales/order");
		$order->loadByIncrementId($session->getLastRealOrderId());
		$this->_orderId = $order->getRealOrderId();
		$this->setTemplate("pagseguro/receipt.phtml");

		Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($this->_orderId)));

		parent::_construct();
	}
	
	public function getOrderId() {
		return $this->_orderId;
	}
	
}
?>
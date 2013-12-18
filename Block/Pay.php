<?php
class Cammino_Pagseguro_Block_Pay extends Mage_Payment_Block_Form {
	
	protected function _construct() {
		$this->setTemplate('pagseguro/pay.phtml');
		parent::_construct();
	}
	
	public function getPaymentUrl() {
		$pay = Mage::getSingleton('pagseguro/standard');
		$session = Mage::getSingleton("checkout/session");
		$orderId = isset($this->getRequest()->getParam("id")) ? $this->getRequest()->getParam("id") : $session->getLastRealOrderId();

		$url = $pay->getPaymentUrl($orderId);

		return $url;
	}
	
}
?>
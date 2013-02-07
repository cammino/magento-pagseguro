<?php
class Cammino_Pagseguro_StandardController extends Mage_Core_Controller_Front_Action {
	
	public function receiptAction() {
		$this->analyticsTrack();

		$block = $this->getLayout()->createBlock('pagseguro/receipt');
		$this->loadLayout();
		$this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}
	
	public function payAction() {
		$block = $this->getLayout()->createBlock('pagseguro/pay');
		$this->renderBlock($block);
	}
	
	private function renderBlock($block) {
		$this->loadLayout();
		$this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();		
	}
	

	private function analyticsTrack() {
		$session = Mage::getSingleton('checkout/session');
		$order = Mage::getModel("sales/order");
		$order->loadByIncrementId($session->getLastRealOrderId());
		$orderId = $order->getRealOrderId();
		Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($orderId)));
	}

}
?>
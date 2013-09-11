<?php
class Cammino_Pagseguro_StandardController extends Mage_Core_Controller_Front_Action {
	
	public function receiptAction() {
		$block = $this->getLayout()->createBlock('pagseguro/receipt');
		$this->loadLayout();
		//$this->analyticsTrack();
		$this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}
	
	public function payAction() {
		$block = $this->getLayout()->createBlock('pagseguro/pay');
		$adwordsBlock = $this->getLayout()->createBlock("adwords/tracker");

		$this->loadLayout();
		$this->analyticsTrack();
		$this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
		$this->getLayout()->getBlock('content')->append($block);
		$this->getLayout()->getBlock('content')->append($adwordsBlock);
		$this->renderLayout();
	}
	
	private function renderBlock($block) {
		$this->loadLayout();
		$this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();		
	}

	private function analyticsTrack() {
		$session = Mage::getSingleton('checkout/session');
		$orderId = $session->getLastOrderId();
		Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($orderId)));

	}

	public function returnAction() {
		//if ($this->getRequest()->isPost()) {
			//$post = $this->getRequest()->getPost();
			//var_dump($post);
		//}

		//$this->loadLayout();
		//$this->renderLayout();
	}

}
?>
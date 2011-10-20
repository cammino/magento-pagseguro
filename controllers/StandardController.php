<?php
class Cammino_Pagseguro_StandardController extends Mage_Core_Controller_Front_Action {
	
	public function receiptAction() {
		$block = $this->getLayout()->createBlock('pagseguro/receipt');
		$this->renderBlock($block);
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
	
}
?>
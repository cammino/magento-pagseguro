<?php
class Cammino_Pagseguro_Block_Pay extends Mage_Payment_Block_Form {
	
	protected function _construct() {
//		$this->setTemplate('pagseguro/pay.phtml');
		parent::_construct();
	}

	protected function _toHtml() {
		$pay = Mage::getSingleton('pagseguro/standard');
		$url = $pay->getUrl();

		return $url;
	}
	
}
?>
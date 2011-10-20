<?php
class Cammino_Pagseguro_Block_Receipt extends Mage_Payment_Block_Form {
	
	protected function _construct() {
//		$this->setTemplate('pagseguro/receipt.phtml');
		parent::_construct();
	}
	
	protected function _toHtml() {
		$checkout = Mage::getSingleton('pagseguro/checkout');
		
		$checkout->setCurrency("BRL");
		$checkout->setReference("REF1234");
		$checkout->addItem(1, "Product 1", 100.00, 1, 100.00);
		$checkout->addItem(2, "Product 2", 200.00, 1, 100.00);
		$checkout->setSender("Sender", "sender@store.com", "00", "12345678");
		$checkout->setShipping(1, "Address", "123", "3th floor", "District", "123456789", "City", "ES", "BRA");
		
		return $checkout->getXML();
	}
	
}
?>
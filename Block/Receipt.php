<?php
class Cammino_Pagseguro_Block_Receipt extends Mage_Payment_Block_Form {
	
	protected function _construct() {
//		$this->setTemplate('pagseguro/receipt.phtml');
		parent::_construct();
	}
	
	protected function _toHtml() {
		$checkout = Mage::getSingleton('pagseguro/checkout');
		
		$checkout->setEmail("marceloferracioli@gmail.com");
		$checkout->setToken("D0C6344F3E7A4D8FBD2BAB46E7FE82D4");
		$checkout->setCurrency("BRL");
		$checkout->setReference("00001");
		$checkout->addItem(1, "Product 1", 100.00, 1, 100.00);
		$checkout->addItem(2, "Product 2", 200.00, 1, 100.00);
		$checkout->setSender("Marcelo Ferracioli", "marcelo@cammino.com.br", "17", "32119936");
		$checkout->setShipping(1, "R. Floriano Peixoto", "690", "Sala 3", "Boa Vista", "15025110", "São José do Rio Preto", "SP", "BRA");
		
		return $checkout->sendRequest();
	}
	
}
?>
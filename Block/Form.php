<?php
class Cammino_Pagseguro_Block_Form extends Mage_Payment_Block_Form {
	
	protected function _construct() {
		$this->setTemplate('pagseguro/form.phtml');
		parent::_construct();
	}
	
}
?>
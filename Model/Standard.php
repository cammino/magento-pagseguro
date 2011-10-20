<?php
class Cammino_Pagseguro_Model_Standard extends Mage_Payment_Model_Method_Abstract {
	
	protected $_canAuthorize = false;
	protected $_canCapture = false;
	protected $_code = 'pagseguro_standard';
	protected $_formBlockType = 'sps/form';
	
	public function assignData($data) {
//		$addata = new Varien_Object;
//		$addata["term"] = $data["term"];
		
//		$info = $this->getInfoInstance();
//		$info->setAdditionalData(serialize($addata));
		
		return $this;
	}
		
	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('pagseguro/standard/receipt', array('_secure' => false));
	}
	
}
?>
<?php
class Cammino_Pagseguro_Model_Checkout extends Mage_Core_Model_Abstract {

	protected $_root;
	protected $_currency;
	protected $_items;
	protected $_reference;
	protected $_sender;
	protected $_shipping;
	protected $_email;
	protected $_token;
	
	protected function _construct() {
		$this->_root = new SimpleXMLElement('<checkout/>');
		$this->_currency = $this->_root->addChild('currency');
		$this->_items = $this->_root->addChild('items');
		$this->_reference = $this->_root->addChild('reference');
		$this->_sender = $this->_root->addChild('sender');
		$this->_shipping = $this->_root->addChild('shipping');
	}
	
	public function addItem($id, $description, $amount, $quantity, $weight) {
		$item = $this->_items->addChild('item');
		$item->addChild('id', $id);
		$item->addChild('description', $description);
		$item->addChild('amount', number_format($amount, 2, '.', ''));
		$item->addChild('quantity', number_format($quantity, 0, '', ''));
		$item->addChild('weight', number_format($weight, 0, '', ''));
	}
	
	public function setSender($name, $email, $phoneCode, $phoneNumber) {
		$this->_sender->addChild('name', $name);
		$this->_sender->addChild('email', $email);
		$phone = $this->_sender->addChild('phone');
		$phone->addChild('areaCode', $phoneCode);
		$phone->addChild('number', $phoneNumber);
	}
	
	public function setShipping($type, $street, $number, $complement, $district, $postalCode, $city, $state, $country) {
		$this->_shipping->addChild('type', $type);
		$address = $this->_shipping->addChild('address');
		$address->addChild('street', $street);
		$address->addChild('number', $number);
		$address->addChild('complement', $complement);
		$address->addChild('district', $district);
		$address->addChild('postalCode', $postalCode);
		$address->addChild('city', $city);
		$address->addChild('state', $state);
		$address->addChild('country', $country);
	}
	
	public function setCurrency($currency) {
		$this->_root->currency = $currency;
	}
	
	public function setReference($reference) {
		$this->_root->reference = $reference;
	}
	
	public function setEmail($email) {
		$this->_email = $email;
	}
	
	public function setToken($token) {
		$this->_token = $token;
	}
	
	public function getXML() {
		return $this->_root->asXML();
	}
	
	public function sendRequest() {
		$url = "https://ws.pagseguro.uol.com.br/v2/checkout?email=".$this->_email."&token=".$this->_token;
		$xml = $this->_root->asXML();
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: application/xml; charset=utf-8"));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
		$response = curl_exec($curl);
		
		return $response;
	}

}
?>
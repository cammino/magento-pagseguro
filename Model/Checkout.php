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
	protected $_errors;
	protected $_paymentCode;
	
	protected function _construct() {
		$this->_root = new SimpleXMLElement('<checkout/>');
		$this->_currency = $this->_root->addChild('currency');
		$this->_items = $this->_root->addChild('items');
		$this->_reference = $this->_root->addChild('reference');
		$this->_sender = $this->_root->addChild('sender');
		$this->_shipping = $this->_root->addChild('shipping');
		$this->_errors = array();
	}
	
	public function addItem($id, $description, $amount, $quantity, $weight) {
		$item = $this->_items->addChild('item');
		$item->addChild('id', substr(strval($id), 0, 100));
		$item->addChild('description', substr(strval($description), 0, 100));
		$item->addChild('amount', number_format($amount, 2, '.', ''));
		$item->addChild('quantity', number_format($quantity, 0, '', ''));
		$item->addChild('weight', number_format($weight, 0, '', ''));
	}
	
	public function setSender($name, $email, $phoneCode, $phoneNumber) {
		$this->_sender->addChild('name', substr(strval($name), 0, 50));
		$this->_sender->addChild('email', $email);
		$phone = $this->_sender->addChild('phone');
		$phone->addChild('areaCode', substr(preg_replace('/[^0-9]/', '', strval($phoneCode)), 0, 2));
		$phone->addChild('number', substr(preg_replace('/[^0-9]/', '', strval($phoneNumber)), 0, 9));


	}
	
	public function setShipping($type, $street, $number, $complement, $district, $postalCode, $city, $state, $country) {
		$this->_shipping->addChild('type', $type);
		$address = $this->_shipping->addChild('address');
		$address->addChild('street', substr(strval($street), 0, 80));
		$address->addChild('number', substr(strval($number), 0, 20));
		$address->addChild('complement', substr(strval($complement), 0, 40));
		$address->addChild('district', substr(strval($district), 0, 60));
		$address->addChild('postalCode', substr(preg_replace('/[^0-9]/', '', strval($postalCode)), 0, 8));
		$address->addChild('city', substr(strval($city), 0, 60));
		$address->addChild('state', substr(strval($state), 0, 2));
		$address->addChild('country', $country);
	}
	
	public function setCurrency($currency) {
		$this->_root->currency = $currency;
	}
	
	public function setReference($reference) {
		$this->_root->reference = substr(strval($reference), 0, 200);
	}
	
	public function setEmail($email) {
		$this->_email = $email;
	}
	
	public function setToken($token) {
		$this->_token = $token;
	}
	
	public function setExtraAmount($extraAmount) {
		//$sign = ($extraAmount < 0) ? "-" : "";
		$this->_root->addChild('extraAmount', number_format($extraAmount, 2, '.', ''));
	}
	
	public function setShippingCost($cost) {
		$this->_shipping->addChild('cost', number_format($cost, 2, '.', ''));
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
		
		return $this->processResponse($response);
	}
	
	public function processResponse($response) {
		$xml = simplexml_load_string($response);
		$this->processErrors($xml);
		
		if (count($this->_errors) > 0) {
			return false;
		} else {
			$this->_paymentCode = strval($xml->code);
			return true;
		}
	}
	
	public function paymentUrl() {
		return "https://pagseguro.uol.com.br/v2/checkout/payment.html?code=" . $this->_paymentCode;
	}
	
	public function processErrors($xml) {
		$_xml = clone $xml;
		$_xml = $_xml->xpath("/errors");
		
		foreach($_xml as $node) {
			array_push($this->_errors, strval($node->error->message));
		}
	}
	
	public function getErrors() {
		return $this->_errors;
	}

}
?>
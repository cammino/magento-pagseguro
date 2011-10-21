<?php
class Cammino_Pagseguro_Model_Standard extends Mage_Payment_Model_Method_Abstract {
	
	protected $_canAuthorize = false;
	protected $_canCapture = false;
	protected $_code = 'pagseguro_standard';
	protected $_formBlockType = 'pagseguro/form';
	
	// public function assignData($data) {
	// 	$addata = new Varien_Object;
	// 	$addata["term"] = $data["term"];
	// 	$info = $this->getInfoInstance();
	// 	$info->setAdditionalData(serialize($addata));
	// 	return $this;
	// }
		
	public function getOrderPlaceRedirectUrl() {
		return $this->getUrl();
	}
	
	private function getUrl() {
		$order = Mage::getModel("sales/order");
		$checkout = Mage::getSingleton('pagseguro/checkout');
		$session = Mage::getSingleton('checkout/session');
		$quote = $session->getQuote();
		
		$order->loadByIncrementId($session->getLastRealOrderId());
		
		$checkout->setEmail($this->getConfigData("email"));
		$checkout->setToken($this->getConfigData("token"));
		$checkout->setCurrency("BRL");
		$checkout->setReference($order->getRealOrderId());
		
		$this->addItems($checkout, $quote);
		$this->setSender($checkout);
		$this->setShipping($checkout);

		$checkout->sendRequest();
		
		return $checkout->paymentUrl();
	}
	
	private function addItems($checkout, $quote) {
		foreach($quote->getAllVisibleItems() as $item) {
			$sku = $item->getSku();
			$name = $item->getName();
			$price = 100.00;
			$quantity = $item->getQty();
			$weight = 100.00;
			
			$checkout->addItem($sku, $name, $price, $quantity, $weight);
		}
	}
	
	private function setSender($checkout) {
		$name = $this->getConfigData("sender_name");
		$email = $this->getConfigData("email");
		$phoneCode = $this->getConfigData("phone_code");
		$phoneNumber = $this->getConfigData("phone_number");
		
		$checkout->setSender($name, $email, $phoneCode, $phoneNumber);
	}
	
	private function setShipping($checkout, $quote) {
		$shippingAddress = $quote->getShippingAddress();
		
		$address1 = $shippingAddress->getStreet(1);
		$address2 = $shippingAddress->getStreet(2);
		$address3 = $shippingAddress->getStreet(3);
		$address4 = $shippingAddress->getStreet(4);
		$postcode = preg_replace("@[^\d]@", "", $shippingAddress->getPostcode());
		$city = $shippingAddress->getCity();
		$state = $shippingAddress->getState();
		$country = $shippingAddress->getCountry(); // BRA
		
		$checkout->setShipping(1, $address1, $address2, $address3, $address4, $postcode, $city, $state, "BRA");
	}
}
?>
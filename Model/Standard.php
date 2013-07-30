<?php
class Cammino_Pagseguro_Model_Standard extends Mage_Payment_Model_Method_Abstract {
	
	protected $_canAuthorize = false;
	protected $_canCapture = false;
	protected $_code = "pagseguro_standard";
	protected $_errors = array();
//	protected $_formBlockType = "pagseguro/form";
	
//	public function assignData($data) {
//		$addata = new Varien_Object;
//		$addata["term"] = $data["term"];
//		$info = $this->getInfoInstance();
//		$info->setAdditionalData(serialize($addata));
//		return $this;
//	}
		
	public function getOrderPlaceRedirectUrl() {		
		return Mage::getUrl('pagseguro/standard/pay', array('_secure' => false));
	}
	
	public function getPaymentUrl($orderId) {
		$order = Mage::getModel("sales/order");
		$checkout = Mage::getSingleton("pagseguro/checkout");
		$order->loadByIncrementId($orderId);

		$orderData = $order->getData();
		$customer = Mage::getModel("customer/customer");
		$customer->load($orderData['customer_id']);
		
		$checkout->setEmail($this->getConfigData("email"));
		$checkout->setToken($this->getConfigData("token"));
		$checkout->setCurrency("BRL");
		$checkout->setReference($order->getRealOrderId());
		
		$this->addItems($checkout, $order);
		$this->setSender($checkout, $customer);
		$this->setShipping($checkout, $order);
		$this->setExtraAmount($checkout, $order);
		
		$checkout->sendRequest();
		$_errors = $checkout->getErrors();
		
		return $checkout->paymentUrl();
	}

	public function getErrors()
	{
		return $_errors;
	}
	
	private function addItems($checkout, $order) {
		foreach($order->getItemsCollection(array(), true) as $item) {
			$sku = $item->getSku();
			$name = $item->getName();
			$price = $item->getPrice();
			$quantity = $item->getQtyToInvoice();
			$weight = 100.00;

			if ($quantity == 0) $quantity = 1;
			
			$checkout->addItem($sku, $name, $price, $quantity, $weight);
		}
	}
	
	private function setSender($checkout, $customer) {

		$customerData = $customer->getData();

		$name = $customerData["firstname"] . " " . $customerData["lastname"];
		$email = $customerData["email"];
		$phoneCode = $this->getConfigData("phone_code");
		$phoneNumber = $this->getConfigData("phone_number");

		if (strlen($name) > 100) $name = substr($name, 0, 99);
		
		$checkout->setSender($name, $email, $phoneCode, $phoneNumber);
	}
	
	private function setShipping($checkout, $order) {
		$shippingAddress = $order->getShippingAddress();		
		$regionId = $shippingAddress->getRegionId();

		$state = $this->getRegionName($regionId);
		$address1 = $shippingAddress->getStreet(1);
		$address2 = $shippingAddress->getStreet(2);
		$address3 = $shippingAddress->getStreet(3);
		$address4 = $shippingAddress->getStreet(4);
		$postcode = preg_replace("@[^\d]@", "", $shippingAddress->getPostcode());
		$city = $shippingAddress->getCity();
		$country = "BRA";
		
		$checkout->setShipping(1, $address1, $address2, $address3, $address4, $postcode, $city, $state, $country);
	}
	
	private function getRegionName($regionId) {
		$regions = Mage::getModel('directory/region')->getResourceCollection()->addCountryFilter("BR")->load();
		$regionCode = "";
		foreach($regions as $item) {
			if($item->region_id == $regionId) {
				$regionCode = $item->code;
			}
		}
		return $regionCode;
	}
	
	private function setExtraAmount($checkout, $order) {
		$shippingAmount = floatval($order->shipping_amount);
		$checkout->setExtraAmount($shippingAmount);
	}
}
?>
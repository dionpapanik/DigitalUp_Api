<?php
class DigitalUp_Apifon_Model_Observer
{

	public function sendSuccessOrder(Varien_Event_Observer $observer)
	{
		$helper = Mage::helper('apifon');

		if ($helper->isEnabled()){

			$lastOrderId = Mage::getSingleton('checkout/session')->getLastOrderId(); //get the id of placed order // ex 3
			$orderObject = Mage::getModel('sales/order')->load($lastOrderId); //load the obj & get data
			$billingData = $orderObject->getBillingAddress();
			$firstname = $billingData->getData("firstname");
			$lastname = $billingData->getData("lastname");
			$phone = $billingData->getData("telephone");
			$orderNo = $orderObject->getData("increment_id"); // get the full order no // ex 100000003

			$text = $helper->getSuccessText();
			if (strpos($text, '{{firstname}}') !== false) {
				$text = str_replace('{{firstname}}', $firstname, $text);
			}

			if (strpos($text, '{{lastname}}') !== false) {
				$text = str_replace('{{lastname}}', $lastname, $text);
			}

			if (strpos($text, '{{order}}') !== false) {
				$text = str_replace('{{order}}', $orderNo, $text);
			}

			$check = mb_substr ($phone, 0, 2);
			if ($check == '69') { //only for mobile starts with 69...
				$this->_sendData($phone, $text);
			}
		}

	}

	public function sendTrackingNumber(Varien_Event_Observer $observer)
	{
		$helper = Mage::helper('apifon');
		if ($helper->getEnableTracking() === '1'){

			$shipment = $observer->getEvent()->getShipment();
			$phone = $shipment->getBillingAddress()->getData('telephone');

			// @todo  find the right phone number!!!
			// Mage::log('telephone ' .$phone, null, 'dionisis.log', true); // done!
			$check = mb_substr ($phone, 0, 2);

			if ($check == '69'){ //only for mobile starts with 69...

				/*
				 * get all shippments data, after save.
				 * asign to $carrier & $number only the last shipment
				 * if order has more than one carriers & No will asign the last
				 */
				foreach ($shipment->getAllTracks() as $track) {
					$carrier = $track->getData('title');
					$number = $track->getData('track_number');
				}

				/* prepare the text */
				$text = $helper->getTrackingText();
				if (strpos($text, '{{carrier}}') !== false) {
					$text = str_replace('{{carrier}}', $carrier, $text);
				}

				if (strpos($text, '{{trackNo}}') !== false) {
					$text = str_replace('{{trackNo}}', $number, $text);
				}

				$this->_sendData($phone, $text);
			}
		}
	}

	private function _sendData($phone, $text){

		$helper = Mage::helper('apifon');
		$apiKey = $helper->getKey();
        $senderName = $helper->getSenderName();

        $url_encoded_text = urlencode($text);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://smsc.apifon.com/send/?apikey='.$apiKey.'&from='.$senderName.'&to=30'.$phone.'&text='.$url_encoded_text);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_data = curl_exec($curl);
        $curl_httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

		/*Zend_Debug::dump($apiKey);
		Zend_Debug::dump($senderName);
		Zend_Debug::dump($phone);
		Zend_Debug::dump($text);

		Mage::log('apiKey ' . $apiKey, 6, 'dionisis.log', true);
		Mage::log('senderName ' . $senderName, 6, 'dionisis.log', true);
		Mage::log('phone ' . $phone, 6, 'dionisis.log', true);
		Mage::log('text ' . $text, 6, 'dionisis.log', true);*/
		
		// ola cool!
	}
}

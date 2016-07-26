<?php
class DigitalUp_Apifon_Helper_Data extends Mage_Core_Helper_Abstract
{
	const GET_ENABLED = 'apifon/general/enabled';
	const GET_SUCCESS_TEXT = 'apifon/general/success_text';
	const GET_ENABLE_TRACKING = 'apifon/general/enable_tracking';
	const GET_TRACKING_TEXT = 'apifon/general/tracking_text';
	const GET_KEY = 'apifon/general/key';
	const GET_SENDER_NAME= 'apifon/general/sender_name';

	protected $_enabled = null;
	protected $_enabledTracking = null;

	//	return bool
	public function isEnabled()
	{
		if (is_null($this->_enabled)) {
			$this->_enabled =  (bool) Mage::getStoreConfig(self::GET_ENABLED);
		}
		return $this->_enabled;
	}

	//	return string
	public function getSuccessText()
	{
		return Mage::getStoreConfig(self::GET_SUCCESS_TEXT);
	}

	//	return bool
	public function getEnableTracking()
	{
		if (is_null($this->_enabledTracking)) {
			$this->_enabledTracking =  (bool) Mage::getStoreConfig(self::GET_ENABLE_TRACKING);
		}
		return $this->_enabledTracking;
	}

	//	return string
	public function getTrackingText()
	{
		return Mage::getStoreConfig(self::GET_TRACKING_TEXT);
	}

	//	return string
	public function getKey()
	{
		return Mage::getStoreConfig(self::GET_KEY);
	}

	//	return string
	public function getSenderName()
	{
		return Mage::getStoreConfig(self::GET_SENDER_NAME);
	}
}
	 
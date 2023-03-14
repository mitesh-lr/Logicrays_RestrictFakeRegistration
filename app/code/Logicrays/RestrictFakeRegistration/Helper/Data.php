<?php
/**
 * Logicrays
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Logicrays
 * @package     Logicrays_RestrictFakeRegistration
 * @copyright   Copyright (c) Logicrays (https://www.logicrays.com/)
 */
declare(strict_types=1);

namespace Logicrays\RestrictFakeRegistration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use ReCaptcha\ReCaptcha;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * For Decalre all the method for get the Data
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const MODULE_ENABLED = 'restrictfakeregistration/general/enable';
    public const DOMAIN_TYPE='restrictfakeregistration/general/restriction_type';
    
    public const DOMAIN_LIST    = 'restrictfakeregistration/general/domains_list';
    public const DOMAIN_ERROR   = 'restrictfakeregistration/general/domain_error';

    public const FNAME_LIMIT   = 'restrictfakeregistration/general/firstnamelimit';
    public const FNAME_ERROR = 'restrictfakeregistration/general/firstname_error';

    public const LNAME_LIMIT   = 'restrictfakeregistration/general/lastnamelimit';
    public const LNAME_ERROR = 'restrictfakeregistration/general/lastname_error';

    public const CUSTOMER_NAME_LIST  = 'restrictfakeregistration/general/customer_name';
    public const NAME_ERROR = 'restrictfakeregistration/general/name_error';

    public const IP_BLACK_LIST ='restrictfakeregistration/general/ipblacklist';
    public const IP_BLACK_LIST_ERROR ='restrictfakeregistration/general/ipblacklist_error';


    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface;
     */
    private $serializer;

    /**
     * @param Context $context
     * @param JsonHelper $jsonHelper
     * @param SerializerInterface $serializer
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        JsonHelper $jsonHelper,
        SerializerInterface $serializer,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->_jsonHelper = $jsonHelper;
        $this->serializer = $serializer;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check module status
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(
             self::MODULE_ENABLED,
             \Magento\Store\Model\ScopeInterface::SCOPE_STORE
         );
    }
    
    /**
     * Get restriction type
     *
     * @return string
     */
    public function getRestrictionType()
    {
        return $this->scopeConfig->getValue(
            self::DOMAIN_TYPE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get domain error message
     *
     * @return string
     */
    public function getDomainErrMassage()
    {
        return $this->scopeConfig->getValue(
            self::DOMAIN_ERROR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get domain list
     *
     * @return string
     */
    public function getAllDomainList()
    {
        return $this->scopeConfig->getValue(
            self::DOMAIN_LIST,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

   
    /**
     * Get firstname limit
     *
     * @return int
     */
    public function getFirstNameLimit()
    {
        return $this->scopeConfig->getValue(
            self::FNAME_LIMIT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }


    /**
     * Get firstname error message
     *
     * @return string
     */
    public function getFirstNameErrMessage()
    {
        return $this->scopeConfig->getValue(
            self::FNAME_ERROR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get lastname limit
     *
     * @return int
     */
    public function getLastNameLimit()
    {
        return $this->scopeConfig->getValue(
            self::LNAME_LIMIT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }


    /**
     * Get lastname error message
     *
     * @return string
     */
    public function getLastNameErrMessage()
    {
        return $this->scopeConfig->getValue(
            self::LNAME_ERROR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get allcustomer list
     *
     * @return int
     */
    public function getAllCustomerList()
    {
        return $this->scopeConfig->getValue(
            self::CUSTOMER_NAME_LIST,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get name error message
     *
     * @return string
     */
    public function getNameErrMassage()
    {
        return $this->scopeConfig->getValue(
            self::NAME_ERROR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get blacklistip list
     *
     * @return string
     */
    public function getAllBlackListIP()
    {
        return $this->scopeConfig->getValue(
            self::IP_BLACK_LIST,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }


    /**
     * Get blacklistip list ip error message
     *
     * @return string
     */
    public function getBlackListIPErrMassage()
    {
        return $this->scopeConfig->getValue(
            self::IP_BLACK_LIST_ERROR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}

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

namespace Logicrays\RestrictFakeRegistration\Plugin;

use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\UrlFactory;
use Magento\Framework\Message\ManagerInterface;
use Logicrays\RestrictFakeRegistration\Helper\Data;

class RestrictCustomer
{

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlModel;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Logicrays\RestrictFakeRegistration\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $remotAddress;

    /**
     * RestrictCustomer constructor.
     * @param UrlFactory $urlFactory
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $messageManager
     * @param Data $helper
     * @param RemoteAddress $remotAddress
     */
    public function __construct(
        UrlFactory $urlFactory,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        Data $helper,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remotAddress
    ) {
        $this->urlModel = $urlFactory->create();
        $this->resultRedirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->helper = $helper;
        $this->remotAddress = $remotAddress;
    }

    /**
     * Check Customer Registration Data
     *
     * @param \Magento\Customer\Controller\Account\CreatePost $subject
     * @param \Closure $proceed
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject,
        \Closure $proceed
    ) {
        $enabled = $this->helper->isEnabled();
        if (!$enabled) {
            return $proceed();
        }
        
        $verifyDetails = [];
        
        /** @var \Magento\Framework\App\RequestInterface $request */
        $email = $subject->getRequest()->getParam('email');
        $firstname = strtolower($subject->getRequest()->getParam('firstname'));
        $lastname  = strtolower($subject->getRequest()->getParam('lastname'));
        $fNameLimit =  $this->helper->getFirstNameLimit();
        $fnameErrMsg = $this->helper->getFirstNameErrMessage();
        $lNameLimit =  $this->helper->getLastNameLimit();
        $lnameErrMsg = $this->helper->getLastNameErrMessage();
        $dummyNameList = $this->helper->getAllCustomerList();
        $nameErrMsg =  $this->helper->getNameErrMassage();
        $ipBlockList =  $this->helper->getAllBlackListIP();
        $ipBlockListErrMsg = $this->helper->getBlackListIPErrMassage();
        $domainList= $this->helper->getAllDomainList();
        $domainErrMsg =  $this->helper->getDomainErrMassage();
        $emailArray = explode('@', $email);
        $currentIp = $this->remotAddress->getRemoteAddress();
        $restrictionType =  $this->helper->getRestrictionType();

        $verifyDetails[$emailArray[1]] = [
            "list" => $domainList,
            "errorMsg" => $domainErrMsg
        ];

        $verifyDetails[$firstname] = [
            "list" => $dummyNameList,
            "errorMsg" => $nameErrMsg
        ];

        $verifyDetails[$currentIp] = [
            "list" => $ipBlockList,
            "errorMsg" => $ipBlockListErrMsg
        ];

        $verifyLenth = [];

        $verifyLenth[$firstname] = [
            "length" => $fNameLimit,
            "errorMsg" => $fnameErrMsg
        ];
        $verifyLenth[$lastname] = [
            "length" => $lNameLimit,
            "errorMsg" => $lnameErrMsg
        ];

        $isExcl = ($restrictionType == 1) ? false : true;
        $cnt = 0;

        foreach ($verifyDetails as $param => $listArray) {
            
            if (empty($listArray['list'])) {
                continue;
            }

            $list = explode(",", $listArray['list']);
            $list = array_map('trim', $list);
            $list = array_map('strtolower', $list);

            if (!$isExcl && $cnt == 0) {
                $isValid = (in_array($param, $list)) ? true : false;
            } else {
                $isValid = (!in_array($param, $list)) ? true : false;
            }

            $cnt++;

            if (!$isValid) {
                $this->messageManager->addErrorMessage($listArray['errorMsg']);
                $redirectionUrl = $this->urlModel->getUrl('*/*/create', ['_secure' => true]);
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setUrl($redirectionUrl);
            }

        }

        foreach ($verifyLenth as $param => $listArray) {
            
            if (empty($listArray['length'])) {
                continue;
            }
    
            $isValid = (strlen($param) <= (int)$listArray['length']) ? true : false;

            if (!$isValid) {
                $this->messageManager->addErrorMessage($listArray['errorMsg']);
                $redirectionUrl = $this->urlModel->getUrl('*/*/create', ['_secure' => true]);
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setUrl($redirectionUrl);
            }

        }

        return $proceed();
    }
}

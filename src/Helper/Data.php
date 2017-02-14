<?php

namespace Space48\QuickView2\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
//use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface as StoreScopeInterface;

class Data extends AbstractHelper
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    public function __construct(
//        ScopeConfigInterface $scopeConfig,
        Context $context
    )
    {
//        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function getConfig($path)
    {
        $this->_scopeConfig->getValue('space48_quickview2_section/Space48_QuickView2/' . $path, StoreScopeInterface::SCOPE_STORE);

    }

}


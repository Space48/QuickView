<?php

namespace Space48\QuickView2\Block\Category;

use Magento\Framework\View\Element\Template;
use Space48\QuickView2\Helper\Data;

class View extends Template
{

    protected $_helper;

    public function __construct(Data $helper)
    {
        $this->_helper = $helper;

    }

    public function getButtonText()
    {
        return $this->_helper->getConfig('quickview_text');
    }

}


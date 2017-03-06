<?php
/**
 * Space48_QuickView
 *
 * @category    Space48
 * @package     Space48_QuickView
 * @Date        02/2017
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @author      @diazwatson
 */

declare(strict_types = 1);

namespace Space48\QuickView\Block\Catalog\Category\View;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class QuickView extends Template
{

    const SYSTEM_CONFIG_PATH = 'space48_quickview/general/';
    const QUICKVIEW_PRODUCT_VIEW_ID = 'quickview/product/view/id/';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */

    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);
    }

    public function getButtonText()
    {
        return $this->getConfig('btn_text');
    }

    public function getConfig($field)
    {
        return $this->_scopeConfig->getValue(self::SYSTEM_CONFIG_PATH . $field, ScopeInterface::SCOPE_STORE);

    }

    public function getQuickViewJson()
    {
        return json_encode([
            'html' => $this->getContent()
        ]);
    }

    private function getContent()
    {
        return 'Hello World';
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->getParentBlock()->getChildBlock('compare')->getProduct();
    }

    public function getQuickViewUrl($productId)
    {
        return $this->getBaseUrl() . self::QUICKVIEW_PRODUCT_VIEW_ID . $productId;
    }
}
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

    protected $route = 'quickview/product/*/';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */

    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getButtonText()
    {
        return $this->getConfig('btn_text');
    }

    /**
     * @param $field
     *
     * @return mixed
     */
    public function getConfig($field)
    {
        return $this->_scopeConfig->getValue(self::SYSTEM_CONFIG_PATH . $field, ScopeInterface::SCOPE_STORE);

    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->getParentBlock()->getChildBlock('compare')->getProduct();
    }

    /**
     * @param int $productId
     *
     * @return string
     */
    public function getQuickViewUrl($productId)
    {
        return $this->getUrl($this->route, ['id' => $productId]);
    }

    public function getBreadcrumbs()
    {
        $bread = $this->getLayout()->getBlock('breadcrumbs');

        return $bread;
    }
}
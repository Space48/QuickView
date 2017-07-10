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

declare(strict_types=1);

namespace Space48\QuickView\Block\Catalog\Category\View;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Registry;

class QuickView extends Template
{

    const SYSTEM_CONFIG_PATH = 'space48_quickview/general/';

    private $route = 'quickview/product/*/';

    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @param Context  $context
     * @param Registry $coreRegistry
     */

    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        $this->coreRegistry = $coreRegistry;
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
     * @param $field string
     *
     * @return \Magento\Framework\App\Config
     */
    public function getConfig($field)
    {
        return $this->_scopeConfig->getValue(
            self::SYSTEM_CONFIG_PATH . $field,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProduct()
    {
        /** @var $compareBlock \Magento\Catalog\Block\Product\ProductList\Item\AddTo\Compare */
        $compareBlock = $this->getParentBlock()->getChildBlock('compare');
        return $compareBlock->getProduct();
    }

    /**
     * @param int  $productId
     * @param  int $categoryId
     *
     * @return string
     */
    public function getQuickViewUrl($productId, $categoryId)
    {
        return $this->getUrl($this->route, ['id' => $productId, 'cat' => $categoryId]);
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getCurrentCategory()
    {
        return $this->coreRegistry->registry('current_category');
    }
}

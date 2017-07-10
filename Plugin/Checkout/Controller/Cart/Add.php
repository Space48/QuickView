<?php
/**
 * Space48_QuickView
 *
 * @category    Space48
 * @package     Space48_QuickView
 * @Date        04/2017
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @author      @diazwatson
 */

namespace Space48\QuickView\Plugin\Checkout\Controller\Cart;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Add
{
    /**
     * ScopeConfigInterface
     *
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Add constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {

        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Plugin
     *
     * @param \Magento\Checkout\Controller\Cart\Add $subject
     * @param callable                              $proceed
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function aroundExecute(\Magento\Checkout\Controller\Cart\Add $subject, callable $proceed)
    {
        $referrerUrl = $subject->getRequest()->getServer('HTTP_REFERER');

        $returnValue = $proceed();
        if ($returnValue && !$this->shouldRedirectToCart()) {
            $returnValue->setUrl($referrerUrl);
        }
        return $returnValue;
    }

    /**
     * Should Redirect To Cart
     *
     * @return boolean
     */
    private function shouldRedirectToCart(): bool
    {
        return $this->scopeConfig->getValue(
            'checkout/cart/redirect_to_cart',
            ScopeInterface::SCOPE_STORE
        );
    }
}

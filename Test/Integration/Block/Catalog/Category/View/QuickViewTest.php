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

namespace Space48\QuickView\Block\Catalog\Category\View;

use Magento\Store\Model\StoreManagerInterface;
use Magento\TestFramework\ObjectManager;

class QuickViewTest extends \PHPUnit_Framework_TestCase
{

    /** @var QuickView $block */
    public $block;

    public function setUp()
    {
        $this->block = ObjectManager::getInstance()->create(QuickView::class);
    }

    public function testGetButtonTextReturnsRightString()
    {
        $expectedString = 'Quick View';
        $this->assertEquals($expectedString, $this->block->getButtonText());
    }

    public function testGetQuickViewUrlReturnsTheRightUrl()
    {
        $productId = 3;
        $categoryId = 4;
        $expectedUrl = $this->getBaseUrl() . "quickview/product/index/id/{$productId}/cat/{$categoryId}/";
        $quickViewUrl = $this->block->getQuickViewUrl($productId, $categoryId);

        $this->assertEquals($expectedUrl, $quickViewUrl);
    }

    /**
     * @return mixed
     */
    private function getBaseUrl()
    {
        return ObjectManager::getInstance()
            ->get(StoreManagerInterface::class)
            ->getStore()
            ->getBaseUrl();
    }
}

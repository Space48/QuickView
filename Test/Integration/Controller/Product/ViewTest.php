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

declare(strict_types=1);

namespace Space48\QuickView\Controller\Product;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\AbstractController;

class ViewTest extends AbstractController
{
    /**
     * @magentoDataFixture Magento/Catalog/controllers/_files/products.php
     * @magentoAppArea     frontend
     * @covers             \Space48\QuickView\Controller\Product\View
     */
    public function testReturnsJsonResponse()
    {
        $this->initProduct();
        $contentTypeHeader = $this->getResponse()->getHeader('Content-Type');
        $this->assertNotFalse($contentTypeHeader);
        $this->assertSame('application/json', $contentTypeHeader->getFieldValue());
    }

    private function initProduct()
    {
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = Bootstrap::getObjectManager();

        /**  @var $repository \Magento\Catalog\Model\ProductRepository */
        $repository = $objectManager->create('Magento\Catalog\Model\ProductRepository');
        $product = $repository->get('simple_product_1');

        /** @var $currentProduct \Magento\Catalog\Model\Product */
        $objectManager->get('Magento\Framework\Registry')->register('current_product', $product);

        $this->dispatch("/quickview/product/view/id/{$product->getId()}/cat/2/");
    }
}

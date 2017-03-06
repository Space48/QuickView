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

namespace Space48\QuickView\Controller\Product;

use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Pricing\Helper\Data;

class View extends Action
{

    /**
     * @var
     */
    protected $_resultJsonFactory;

    /**
     * @var ProductRepository
     */
    protected $_productRepository;

    /**
     * @var Data
     */
    protected $_priceHelper;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var ImageBuilder
     */
    private $_imageBuilder;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ProductRepository $productRepository,
        ImageBuilder $imageBuilder,
        Data $priceHelper
    )
    {
        $this->_storeManager = $storeManager;
        $this->_productRepository = $productRepository;
        $this->_priceHelper = $priceHelper;
        parent::__construct($context);
        $this->_imageBuilder = $imageBuilder;
    }

    /**
     * Dispatch request
     *
     * @return string
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $productId = (int) $this->getRequest()->getParam('id');
        $product = $this->getProductById($productId);

        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $data = array(
            'name'          => $product->getData('name'),
            'sku'           => $product->getData('sku'),
            'is_salable'    => $product->getData('is_salable'),
            'price'         => $this->_priceHelper->currency($product->getData('price'), true, false),
            'special_price' => ($product->getData('special_price')) ? $this->_priceHelper->currency($product->getData('special_price'), true, false) : null,
            'product_url'   => $product->getProductUrl(),
            'gallery'       => $this->getGallery($product),
            'breadcrumb'    => array()
        );

        $result = $result->setData($data);

        return $result;
    }

    /**
     * @param $productId
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface|mixed
     */
    private function getProductById($productId)
    {
        return $this->_productRepository->getById($productId);
    }

    /**
     * @param $product
     *
     * @return mixed
     */
    protected function getGallery($product)
    {
        $gallery = $product->getData('media_gallery');
        foreach ($gallery['images'] as $key => $image) {
            $gallery['images'][$key]['file'] = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $image['file'];
//            $gallery['images'][$key]['thumbnail'] = $this->_imageBuilder->setProduct($product)->setImageId('thumbnail')->setAttributes(array())->create();
        }

        return $gallery;
    }

}


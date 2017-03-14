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

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Pricing\Helper\Data;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Helper\Data as CatalogHelperData;

class View extends Action
{

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $_categoryRepository;

    /**
     * @var Data
     */
    protected $_priceHelper;

    /**
     * @var Image
     */
    protected $_imageHelper;

    /**
     * @var ListProduct
     */
    protected $_listProduct;
    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var CatalogHelperData
     */
    protected $_catalogData;

    /**
     * View constructor.
     *
     * @param Context                     $context
     * @param StoreManagerInterface       $storeManager
     * @param ProductRepositoryInterface  $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     *
     * @param Image                       $imageHelper
     * @param Data                        $priceHelper
     * @param Registry                    $coreRegistry
     * @param CatalogHelperData           $catalogData
     * @param ListProduct                 $listProduct
     *
     * @internal param ListProduct $listProduct
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        Image $imageHelper,
        Data $priceHelper,
        Registry $coreRegistry,
        CatalogHelperData $catalogData,
        ListProduct $listProduct
    )
    {
        $this->_productRepository = $productRepository;
        $this->_categoryRepository = $categoryRepository;
        $this->_storeManager = $storeManager;
        $this->_priceHelper = $priceHelper;
        $this->_imageHelper = $imageHelper;
        $this->_listProduct = $listProduct;
        $this->_coreRegistry = $coreRegistry;
        $this->_catalogData = $catalogData;
        parent::__construct($context);
    }

    /**
     * Dispatch request
     *
     * @return string
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $product = $this->initProduct();

        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $result = $result->setData(array(
            'name'          => $product->getData('name'),
            'price'         => $this->formatPrice($product->getData('price')),
            'special_price' => $this->formatPrice($product->getData('special_price')),
            'sku'           => $product->getData('sku'),
            'product_url'   => $product->getProductUrl(),
            'is_salable'    => $product->getData('is_salable'),
            'gallery'       => $this->getGallery($product),
            'breadcrumb'    => $this->getBreadcrumbs($product),
            'add_to_cart'   => $this->_listProduct->getAddToCartPostParams($product)
        ));

        return $result;
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface|mixed
     *
     */
    private function initProduct()
    {
        $productId = $this->getRequest()->getParam('id');
        $product = $this->_productRepository->getById($productId);
        $this->_coreRegistry->register('current_product', $product);

        return $product;
    }

    /**
     * @param $price
     *
     * @return float|string
     */
    public function formatPrice($price)
    {
        return $this->_priceHelper->currency($price, true, false);
    }

    /**
     * @param $product
     *
     * @return array
     */
    protected function getGallery($product)
    {
        $gallery = $product->getData('media_gallery');

        foreach ($gallery['images'] as $key => $image) {
            $gallery['images'][$key]['file'] = $this->_imageHelper->init($product, 'product_page_image_large')
                ->setImageFile($image['file'])
                ->getUrl();

            $gallery['images'][$key]['thumbnail'] = $this->_imageHelper->init($product, 'product_page_image_small')
                ->setImageFile($image['file'])
                ->getUrl();
        }

        return $gallery;
    }

    /**
     * @return array
     */
    private function getBreadcrumbs()
    {
        $breadcrumbs = array();

        $this->_initCategory();

        /** @var array $path */
        $path = $this->_catalogData->getBreadcrumbPath();

        foreach ($path as $name => $breadcrumb) {
            $breadcrumbs[$name] = $breadcrumb;
        }

        return $breadcrumbs;
    }

    /**
     * @return $this
     */
    protected function _initCategory()
    {
        $category = $this->_categoryRepository->get($this->getRequest()->getParam('cat'), $this->getStoreId());
        $this->_coreRegistry->register('current_category', $category);

        return $this;
    }

    /**
     * @return int
     */
    private function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

}



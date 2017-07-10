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
use Magento\Framework\Event\Manager as EventManager;
use Magento\Catalog\Model\Session;

class View extends Action
{

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var Data
     */
    private $priceHelper;

    /**
     * @var Image
     */
    private $imageHelper;

    /**
     * @var ListProduct
     */
    private $listProduct;
    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var CatalogHelperData
     */
    private $catalogData;

    private $eventManager;

    private $catalogSession;

    /**
     * View constructor.
     *
     * @param Context                     $context
     * @param StoreManagerInterface       $storeManager
     * @param ProductRepositoryInterface  $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Image                       $imageHelper
     * @param Data                        $priceHelper
     * @param Registry                    $coreRegistry
     * @param CatalogHelperData           $catalogData
     * @param ListProduct                 $listProduct
     * @param EventManager                $eventManager
     * @param Session                     $catalogSession
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
        ListProduct $listProduct,
        EventManager $eventManager,
        Session $catalogSession
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->storeManager = $storeManager;
        $this->priceHelper = $priceHelper;
        $this->imageHelper = $imageHelper;
        $this->listProduct = $listProduct;
        $this->coreRegistry = $coreRegistry;
        $this->catalogData = $catalogData;
        $this->eventManager = $eventManager;
        $this->catalogSession = $catalogSession;
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
        /** @var \Magento\Catalog\Model\Product $product */
        $currentProduct = $this->coreRegistry->registry('current_product');
        $product =  $currentProduct ? $currentProduct : $this->initProduct();

        $data = $this->getQuickViewData($product);
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        return $result->setData($data);
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface
     *
     */
    private function initProduct()
    {
        $productId = $this->getRequest()->getParam('id');
        $product = $this->productRepository->getById($productId);
        $this->coreRegistry->register('current_product', $product);

        return $product;
    }

    /**
     * @param $product \Magento\Catalog\Model\Product
     *
     * @return array
     */
    private function getQuickViewData($product): array
    {
        $data = [
            'name'          => $product->getData('name'),
            'price'         => $this->formatPrice($product->getData('price')),
            'special_price' => $this->getSpecialPrice($product),
            'sku'           => $product->getData('sku'),
            'product_url'   => $product->getProductUrl(),
            'product_type'  => $product->getTypeId(),
            'is_salable'    => $product->getData('is_salable'),
            'gallery'       => $this->getGallery($product),
            'breadcrumb'    => $this->getBreadcrumbs(),
            'add_to_cart'   => $this->listProduct->getAddToCartPostParams($product)
        ];

        $this->catalogSession->setSpecialQuickViewData([]);
        $this->eventManager->dispatch('space_set_quickview_data_after', ['product' => $product]);
        $data['special_data'] = $this->catalogSession->getSpecialQuickViewData();

        return $data;
    }

    /**
     * @param $price
     *
     * @@return  float|string
     */
    private function formatPrice($price)
    {
        if (($price) != null) {
            $price = $this->priceHelper->currency($price, true, false);
        }

        return $price;
    }

    /**
     * @param $product \Magento\Catalog\Model\Product
     *
     * @return float|null|string
     */
    private function getSpecialPrice($product)
    {
        $specialPrice = ($product->getData('special_price') != $product->getData('price')) ?
            $this->formatPrice($product->getData('special_price')) : null;

        /* Return null for special price if price and special price are the same */

        return $specialPrice;
    }

    /**
     * @param $product \Magento\Catalog\Model\Product
     *
     * @return array
     */
    private function getGallery($product)
    {
        $gallery = $product->getData('media_gallery');

        foreach ($gallery['images'] as $key => $image) {
            $gallery['images'][$key]['file'] = $this->imageHelper->init($product, 'product_page_image_large')
                ->setImageFile($image['file'])
                ->getUrl();

            $gallery['images'][$key]['thumbnail'] = $this->imageHelper->init($product, 'product_page_image_small')
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
        $breadcrumbs = [];

        $breadcrumbs['home'] = [
            'label' => __('Home'),
            'title' => __('Go to Home Page'),
            'link'  => $this->storeManager->getStore()->getBaseUrl()
        ];

        $this->initCategory();

        /** @var array $path */
        $path = $this->catalogData->getBreadcrumbPath();

        foreach ($path as $name => $breadcrumb) {
            $breadcrumbs[$name] = $breadcrumb;
        }

        return $breadcrumbs;
    }

    /**
     * @return $this
     */
    private function initCategory()
    {
        $category = $this->categoryRepository->get($this->getRequest()->getParam('cat'), $this->getStoreId());
        $this->coreRegistry->register('current_category', $category);

        return $this;
    }

    /**
     * @return int
     */
    private function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}

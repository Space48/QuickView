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

use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class View extends Action
{

    protected $_resultJsonFactory;
    private $_productRepository;

    public function __construct(Context $context, ProductRepository $productRepository)
    {
        $this->_productRepository = $productRepository;
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
        $productId = (int) $this->getRequest()->getParam('id');
        $product =$this->getProductById($productId);

        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result = $result->setData($product);
        return $result;
    }

    private function getProductById($productId)
    {
        return $this->_productRepository->getById($productId);
    }

}


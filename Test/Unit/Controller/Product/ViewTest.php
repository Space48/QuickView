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

use Magento\Framework\App\Action\Action;

class ViewTest extends \PHPUnit_Framework_TestCase
{

    public function testControllerExtendActionController()
    {
        $controller = $this->getMockBuilder(View::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertInstanceOf(Action::class, $controller);
    }
}

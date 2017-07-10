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

use Magento\Framework\View\Element\Template;

class QuickViewTest extends \PHPUnit_Framework_TestCase
{

    /** @var  QuickView */
    public $block;

    public function setUp()
    {
        $this->block = $this->getMockBuilder(QuickView::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->block->method('getButtonText')->willReturn('Quick View');
    }

    public function testIsInstanceOfTemplate()
    {
        $this->assertInstanceOf(Template::class, $this->block);
    }

    public function testGetButtonTextReturnsTheRightType()
    {
        $this->assertEquals('Quick View', $this->block->getButtonText());

    }
}

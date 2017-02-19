<?php
/**
 * ModuleConfigTest.php
 *
 * @Date        02/2017
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @author      @diazwatson
 */

namespace Space48\QuickView2\Test\Integration;


use Magento\Framework\Component\ComponentRegistrar;

class ModuleConfigTest extends \PHPUnit_Framework_TestCase
{

    public function testModuleisInstalled()
    {
        $registrar = new ComponentRegistrar;
        $path = $registrar->getPaths(ComponentRegistrar::MODULE);
        $this->assertArrayHasKey('Space48_QuickView2', $path);

    }
}

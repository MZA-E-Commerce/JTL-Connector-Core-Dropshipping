<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Session;

use Jtl\Connector\Core\Exception\SessionException;
use Jtl\Connector\Core\Session\SessionHelper;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class SessionHelperTest extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testHas(): void
    {
        $helper                 = new SessionHelper('foo');
        $_SESSION['foo']['bar'] = 'boofar';
        $_SESSION['foo']['you'] = 'yalla';
        $this->assertTrue($helper->has('bar'));
        $this->assertTrue($helper->has('you'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testHasNot(): void
    {
        $helper = new SessionHelper('foo');
        $this->assertFalse($helper->has('bar'));
        $this->assertFalse($helper->has('yes'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGet(): void
    {
        $helper                 = new SessionHelper('foo');
        $_SESSION['foo']['bar'] = 'vaaaaalue';
        $_SESSION['foo']['och'] = 'taataa';
        $this->assertEquals('vaaaaalue', $helper->get('bar'));
        $this->assertEquals('taataa', $helper->get('och'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetDefault(): void
    {
        $helper = new SessionHelper('foo');
        $this->assertEquals('baras', $helper->get('foo', 'baras'));
        $this->assertEquals('faburus', $helper->get('och', 'faburus'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testSet(): void
    {
        $helper = new SessionHelper('yo');
        $helper->set('lo', 'miau');
        $this->assertArrayHasKey('lo', $_SESSION['yo']);
        $this->assertEquals('miau', $_SESSION['yo']['lo']);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testUnset(): void
    {
        $helper                   = new SessionHelper('tests');
        $_SESSION['tests']['foo'] = 'bar';
        $this->assertArrayHasKey('foo', $_SESSION['tests']);
        $helper->unset('foo');
        $this->assertArrayNotHasKey('foo', $_SESSION['tests']);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SessionException
     * @throws \ReflectionException
     */
    public function testCreateByObjectClass(): void
    {
        $expectedNamespace           = \ZipArchive::class;
        $helper                      = SessionHelper::createByObjectClass(new \ZipArchive());
        $reflectionClass             = new \ReflectionClass($helper);
        $reflectionPropertyNamespace = $reflectionClass->getProperty('namespace');
        $reflectionPropertyNamespace->setAccessible(true);
        $actualNamespace = $reflectionPropertyNamespace->getValue($helper);
        $this->assertEquals($expectedNamespace, $actualNamespace);
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        unset($_SESSION);
    }
}

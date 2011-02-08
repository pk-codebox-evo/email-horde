<?php
/**
 * Test the decorator for time measurements.
 *
 * PHP version 5
 *
 * @category   Kolab
 * @package    Kolab_Format
 * @subpackage UnitTests
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @license    http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link       http://pear.horde.org/index.php?package=Kolab_Format
 */

/**
 * Prepare the test setup.
 */
require_once dirname(__FILE__) . '/../../Autoload.php';

/**
 * Test the decorator for time measurements.
 *
 * Copyright 2010-2011 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @category   Kolab
 * @package    Kolab_Format
 * @subpackage UnitTests
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @license    http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link       http://pear.horde.org/index.php?package=Kolab_Format
 */
class Horde_Kolab_Format_Unit_Decorator_TimedTest
extends Horde_Kolab_Format_TestCase
{
    public function testConstructor()
    {
        $this->getFactory()->create(
            'XML', 'contact', array('timelog' => true)
        );
    }

    public function testGetName()
    {
        $this->assertEquals(
            'kolab.xml',
            $this->getFactory()
            ->create('XML', 'contact', array('timelog' => true))
            ->getName()
        );
    }

    public function testGetMimeType()
    {
        $this->assertEquals(
            'application/x-vnd.kolab.contact',
            $this->getFactory()
            ->create('XML', 'contact', array('timelog' => true))
            ->getMimeType()
        );
    }

    public function testGetDisposition()
    {
        $this->assertEquals(
            'attachment',
            $this->getFactory()
            ->create('XML', 'contact', array('timelog' => true))
            ->getDisposition()
        );
    }

    public function testTimeSpent()
    {
        $timed = $this->_getTimedMock();
        $a = '';
        $timed->load($a);
        $this->assertType(
            'float',
            $timed->timeSpent()
        );
    }

    public function testTimeSpentIncreases()
    {
        $timed = $this->_getTimedMock();
        $a = '';
        $timed->load($a);
        $t_one = $timed->timeSpent();
        $timed->save(array());
        $this->assertTrue(
            $t_one < $timed->timeSpent()
        );
    }

    public function testLogLoad()
    {
        $timed = $this->_getTimedMock();
        $a = '';
        $timed->load($a);
        $this->assertContains(
            'Kolab Format data parsing complete. Time spent:',
            array_pop($this->logger->log)
        );
    }

    public function testLogSave()
    {
        $timed = $this->_getTimedMock();
        $a = array();
        $timed->save($a);
        $this->assertContains(
            'Kolab Format data generation complete. Time spent:',
            array_pop($this->logger->log)
        );
    }

    public function testNoLog()
    {
        $timed = new Horde_Kolab_Format_Decorator_Timed(
            $this->getMock('Horde_Kolab_Format'),
            new Horde_Support_Timer(),
            true
        );
        $a = array();
        $timed->save($a);
    }

    private function _getTimedMock()
    {
        $this->logger = new Stub_Log();
        return new Horde_Kolab_Format_Decorator_Timed(
            $this->getMock('Horde_Kolab_Format'),
            new Horde_Support_Timer(),
            $this->logger
        );
    }
}

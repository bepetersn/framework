<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   framework
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Hubzero\Base\Tests;

use Hubzero\Test\Basic;
use Hubzero\Utility\Validate;
use InvalidArgumentException;

/**
 * Validate utility test
 */
class ValidateTest extends Basic
{
	/**
	 * Tests if a value is a boolean integer or true/false
	 *
	 * @covers  \Hubzero\Utility\Validate::boolean
	 * @return  void
	 **/
	public function testBoolean()
	{
		$tests = array(
			0 => true,
			1 => true,
			'foo' => false,
			'1' => true,
			'0' => true,
			'true' => false,
			3543 => false
		);

		foreach ($tests as $test => $result)
		{
			$this->assertEquals(Validate::boolean($test), $result);
		}

		$this->assertTrue(Validate::boolean(true));
		$this->assertTrue(Validate::boolean(false));
	}

	/**
	 * Tests if a value is within a specified range.
	 *
	 * @covers  \Hubzero\Utility\Validate::between
	 * @return  void
	 **/
	public function testBetween()
	{
		$tests = array(
			array(
				'str' => 'Donec id elit non mi porta gravida at eget metus.',
				'min' => 3,
				'max' => 100,
				'val' => true
			),
			array(
				'str' => 'Vehicula Sit Dolor',
				'min' => 1,
				'max' => 7,
				'val' => false
			),
			array(
				'str' => '123456789',
				'min' => 0,
				'max' => 10,
				'val' => true
			),
			array(
				'str' => 'dolo',
				'min' => 5,
				'max' => 8,
				'val' => false
			),
		);

		foreach ($tests as $test)
		{
			$this->assertEquals(Validate::between($test['str'], $test['min'], $test['max']), $test['val']);
		}
	}

	/**
	 * Tests if a value is numeric.
	 *
	 * @covers  \Hubzero\Utility\Validate::numeric
	 * @return  void
	 **/
	public function testNumeric()
	{
		$tests = array(
			"42" => true,
			1337 => true,
			0x539 => true,
			02471 => true,
			0b10100111001 => true,
			1337e0 => true,
			"not numeric" => false,
			9.1 => true,
			null => false
		);

		foreach ($tests as $value => $result)
		{
			$this->assertEquals(Validate::numeric($value), $result);
		}

		$this->assertFalse(Validate::numeric(array()));
	}

	/**
	 * Tests if value is an integer
	 *
	 * @covers  \Hubzero\Utility\Validate::integer
	 * @return  void
	 **/
	public function testInteger()
	{
		$tests = array(
			"42" => true,
			'+51' => true,
			-16 => true,
			1337 => true,
			0x539 => false,
			02471 => true,
			1337e0 => true,
			"not numeric" => false,
			9.1 => true,
			null => false
		);

		foreach ($tests as $value => $result)
		{
			$this->assertEquals(Validate::integer($value), $result);
		}

		$this->assertFalse(Validate::integer(array()));
	}

	/**
	 * Tests if value is a positive integer
	 *
	 * @covers  \Hubzero\Utility\Validate::positiveInteger
	 * @return  void
	 **/
	public function testPositiveInteger()
	{
		$tests = array(
			0 => false,
			"42" => true,
			'+51' => true,
			-16 => false,
			1337 => true,
			0x539 => true,
			02471 => true,
			1337e0 => true,
			"not numeric" => false,
			9.1 => true,
			null => false
		);

		foreach ($tests as $value => $result)
		{
			$this->assertEquals(Validate::positiveInteger($value), $result);
		}

		$this->assertFalse(Validate::positiveInteger(array()));
	}

	/**
	 * Tests if value is a non-negative integer
	 *
	 * @covers  \Hubzero\Utility\Validate::nonNegativeInteger
	 * @return  void
	 **/
	public function testNonNegativeInteger()
	{
		$tests = array(
			0 => true,
			"42" => true,
			'+51' => true,
			-16 => false,
			1337 => true,
			"not numeric" => false,
			9.1 => true,
			null => false
		);

		foreach ($tests as $value => $result)
		{
			$this->assertEquals(Validate::nonNegativeInteger($value), $result);
		}

		$this->assertFalse(Validate::nonNegativeInteger(array()));
	}

	/**
	 * Tests if value is a negative integer
	 *
	 * @covers  \Hubzero\Utility\Validate::negativeInteger
	 * @return  void
	 **/
	public function testNegativeInteger()
	{
		$tests = array(
			0 => false,
			"42" => false,
			'+51' => false,
			-16 => true,
			1337 => false,
			"not numeric" => false,
			9.1 => false,
			null => false
		);

		foreach ($tests as $value => $result)
		{
			$this->assertEquals(Validate::negativeInteger($value), $result);
		}

		$this->assertFalse(Validate::negativeInteger(array()));
	}

	/**
	 * Tests if value is an orcid
	 *
	 * @covers  \Hubzero\Utility\Validate::orcid
	 * @return  void
	 **/
	public function testOrcid()
	{
		$tests = array(
			'0000-0000-0000-0000' => true,
			'123-45635-7891-0112' => false,
			'123A-45B6-7CD1-E190' => false,
			'1234567891011112' => false,
			'1234-4567-8910-1112' => true,
			'1234-4567-8910' => false,
			'1234-4567' => false,
			'1234' => false,
			'A123-4567-8910-1112' => false
		);

		foreach ($tests as $value => $result)
		{
			$this->assertEquals(Validate::orcid($value), $result);
		}
	}

	/**
	 * Tests if value is not Empty
	 *
	 * @covers  \Hubzero\Utility\Validate::notEmpty
	 * @return  void
	 **/
	public function testNotEmpty()
	{
		$value = '';
		$this->assertEquals(Validate::notEmpty($value), false);

		$value = '  ';
		$this->assertEquals(Validate::notEmpty($value), false);

		$value = "\n";
		$this->assertEquals(Validate::notEmpty($value), false);

		$value = "\t";
		$this->assertEquals(Validate::notEmpty($value), false);

		$value = "\n0";
		$this->assertEquals(Validate::notEmpty($value), true);

		$value = '0';
		$this->assertEquals(Validate::notEmpty($value), true);

		$value = 'fsdd';
		$this->assertEquals(Validate::notEmpty($value), true);

		$value = array('check' => 'fsdd');
		$this->assertEquals(Validate::notEmpty($value), true);
	}

	/**
	 * Tests if value is a valid group alias
	 *
	 * @covers  \Hubzero\Utility\Validate::group
	 * @return  void
	 **/
	public function testGroup()
	{
		$tests = array(
			'testname' => true,
			'91test' => true,
			'91_test' => true,
			'_91test' => false,
			'12345' => false,
			'Test Name' => false,
			'TESTNAME' => false,
			'test-name' => false
		);

		foreach ($tests as $value => $result)
		{
			$this->assertEquals(Validate::group($value), $result);
		}

		$tests = array(
			'test-name' => true
		);

		foreach ($tests as $value => $result)
		{
			$this->assertEquals(Validate::group($value, true), $result);
		}
	}

	/**
	 * Tests if value is reserved
	 *
	 * @covers  \Hubzero\Utility\Validate::reserved
	 * @return  void
	 **/
	public function testReserved()
	{
		$usernames = array(
			'adm',
			'alfred',
			'apache',
			'backup',
			'bin',
			'canna',
			'condor',
			'condor-util',
			'daemon',
			'debian-exim',
			'exim',
			' ftp',
			'   games',
			'ganglia',
			'gnats',
			'gopher',
			'gridman',
			'halt',
			'httpd',
			'ibrix',
			' invigosh ',
			'irc',
			'LDAP',
			'list',
			'lp',
			'mail  ',
			'   mailnull',
			'man',
			'mysql',
			'nagios',
			'netdump',
			'news',
			'nfsnobody',
			"\nnoaccess",
			"nobody\t",
			'nscd',
			'ntp',
			'operator',
			'openldap',
			'pcap',
			'postgres',
			'proxy',
			'pvm',
			"root\t",
			'rpc',
			'rpcuser',
			'rpm',
			'sag',
			'shutdown',
			'smmsp',
			'sshd',
			'statd',
			'sync',
			'sys',
			'submit',
			'uucp',
			'vncproxy',
			'vncproxyd',
			'vcsa',
			'wheel',
			'www',
			'www-data',
			'xfs',
		);
		$groups = array(
			'abrt',
			'adm',
			'apache',
			'apps',
			'audio',
			'avahi',
			'avahi-autoipd',
			'backup',
			'bin',
			'boinc',
			'cdrom',
			'cgred',
			'cl-builder',
			'clamav',
			'condor',
			'crontab',
			'ctapiusers',
			'daemon',
			"\ndbus",
			'debian-exim',
			'desktop_admin_r',
			'desktop_user_r   ',
			'dialout',
			'dip',
			'disk',
			'fax',
			'floppy',
			' ftp',
			'fuse',
			'   games',
			'gdm',
			'gnats',
			'gopher',
			'gridman',
			'haldaemon',
			' hsqldb ',
			'irc',
			'itisunix',
			'jackuser',
			'kmem',
			'kvm',
			'LDAP',
			'libuuid',
			'list',
			'lock',
			'lp',
			'mail  ',
			'man',
			'mem',
			'messagebus',
			'mysql',
			'netdev',
			'news',
			'nfsnobody',
			"nobody\t",
			'nogroup',
			'nscd',
			'nslcd',
			'ntp',
			'openldap',
			'operator',
			'oprofile',
			'plugdev',
			'postdrop',
			'postfix',
			'powerdev',
			'proxy',
			'pulse',
			'pulse-access',
			'qemu',
			'qpidd',
			'radvd',
			'rdma',
			"root\t",
			'rpc',
			'rpcuser',
			'rtkit',
			'sasl',
			'saslauth',
			'shadow',
			'slocate',
			'src',
			'ssh',
			'sshd',
			'ssl-cert',
			'STAFF',
			'stapdev',
			'stapusr',
			'stap-server',
			'stapsys',
			'stunnel4',
			'sudo',
			'sys',
			'tape',
			'tcpdump',
			'tomcat',
			'tty',
			'tunnelers',
			'usbmuxd',
			'users',
			'utmp',
			'utempter',
			'uucp',
			'video',
			'vcsa',
			'voice',
			'wbpriv',
			'webalizer',
			'wheel',
			'www-data',
			'zookeeper',
		);

		foreach ($usernames as $val)
		{
			$this->assertTrue(Validate::reserved('username', $val));
		}

		foreach ($groups as $val)
		{
			$this->assertTrue(Validate::reserved('group', $val));
		}

		$diff = array_diff($groups, $usernames);

		foreach ($diff as $val)
		{
			$this->assertFalse(Validate::reserved('username', $val));
		}

		$diff = array_diff($usernames, $groups);

		foreach ($diff as $val)
		{
			$this->assertFalse(Validate::reserved('group', $val));
		}

		$this->setExpectedException(InvalidArgumentException::class);
		$result = Validate::reserved('foo', 'bar');
	}

	/**
	 * Tests if value is a string contains only integer or letters
	 *
	 * @covers  \Hubzero\Utility\Validate::alphaNumeric
	 * @return  void
	 **/
	public function testAlphaNumeric()
	{
		$tests = array(
			'testname' => true,
			'91test' => true,
			'91_test' => false,
			'AfOO981' => true,
			'12345' => true,
			'Test Name' => false,
			'TESTNAME' => true,
			'test!' => false,
			'test-name' => false
		);

		foreach ($tests as $value => $result)
		{
			$this->assertEquals(Validate::alphaNumeric($value), $result);
		}

		$value = '0';
		$this->assertTrue(Validate::alphaNumeric($value));

		$value = '';
		$this->assertFalse(Validate::alphaNumeric($value));

		foreach ($tests as $value => $result)
		{
			$value = array('check' => $value);
			$this->assertEquals(Validate::alphaNumeric($value), $result);
		}
	}

	/**
	 * Tests if value is a number is in specified range.
	 *
	 * @covers  \Hubzero\Utility\Validate::range
	 * @return  void
	 **/
	public function testRange()
	{
		$value = 5;
		$this->assertTrue(Validate::range($value));

		$this->assertTrue(Validate::range($value, 1));

		$this->assertTrue(Validate::range($value, 1, 10));

		$this->assertTrue(Validate::range($value, 9));

		$this->assertTrue(Validate::range($value, null, 4));

		$this->assertFalse(Validate::range($value, 1, 4));

		$value = -5;
		$this->assertTrue(Validate::range($value, -10, -1));

		$value = 'five';
		$this->assertFalse(Validate::range($value));

		$value = log(0);
		$this->assertFalse(Validate::range($value), 0, 100000);
	}
}

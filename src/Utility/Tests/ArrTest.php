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
use Hubzero\Utility\Arr;

/**
 * Arr utility test
 */
class ArrTest extends Basic
{
	/**
	 * Tests converting values to integers
	 *
	 * @covers  \Hubzero\Utility\Arr::toInteger
	 * @return  void
	 **/
	public function testToInteger()
	{
		$data = array(
			'1',
			'322',
			55,
			false,
			'foo'
		);

		Arr::toInteger($data);

		$this->assertTrue(is_array($data), 'Value returned was not an array');

		foreach ($data as $val)
		{
			$this->assertTrue(is_int($val), 'Value returned was not an integer');
		}

		$data = new \stdClass;
		$data->one = '1';
		$data->two = false;
		$data->three = 55;

		Arr::toInteger($data);

		$this->assertTrue(is_array($data), 'Value returned was not an array');
		$this->assertTrue(empty($data), 'Value returned was not an empty array');

		$dflt = array(
			'1',
			'322',
			55,
			false,
			'foo'
		);

		$data = new \stdClass;
		$data->one = '1';
		$data->two = false;
		$data->three = 55;

		Arr::toInteger($data, $dflt);

		$this->assertTrue(is_array($data), 'Value returned was not an array');
		$this->assertFalse(empty($data), 'Value returned was an empty array');

		foreach ($data as $key => $val)
		{
			$this->assertTrue(is_int($val), 'Value returned was not an integer');
			$this->assertEquals($val, (int)$dflt[$key]);
		}
	}

	/**
	 * Tests converting values to objects
	 *
	 * @covers  \Hubzero\Utility\Arr::toObject
	 * @return  void
	 **/
	public function testToObject()
	{
		$data1 = array(
			'one' => '1',
			'two' => '322',
			'three' => 55,
			'four' => array(
				'a' => 'foo',
				'b' => 'bar'
			)
		);

		$data2 = new \stdClass;
		$data2->foo = 'one';
		$data2->bar = 'two';
		$data2->lor = array(
			'ipsum',
			'lorem'
		);

		$datas = array();
		$datas[] = $data1;
		$datas[] = $data2;

		foreach ($datas as $data)
		{
			$result = Arr::toObject($data);

			$this->assertTrue(is_object($result), 'Value returned was not an object');

			foreach ((array)$data as $key => $val)
			{
				$this->assertTrue(isset($result->$key), 'Property "' . $key . '" not set on returned object');
				if (!is_array($val))
				{
					$this->assertEquals($result->$key, $val);
				}
				else
				{
					$this->assertTrue(is_object($result->$key), 'Value returned was not an object');

					foreach ($val as $k => $v)
					{
						$this->assertTrue(isset($result->$key->$k), 'Property not set on returned object');
						$this->assertEquals($result->$key->$k, $v);
					}
				}
			}
		}
	}

	/**
	 * Tests converting values from objects
	 *
	 * @covers  \Hubzero\Utility\Arr::fromObject
	 * @return  void
	 **/
	public function testFromObject()
	{
		$data1 = array(
			'one' => '1',
			'two' => '322',
			'three' => 55,
			'four' => array(
				'a' => 'foo',
				'b' => 'bar'
			)
		);

		$data2 = new \stdClass;
		$data2->foo = 'one';
		$data2->bar = 'two';
		$data2->lor = array(
			'ipsum',
			'lorem'
		);
		$data2->ipsum = new \stdClass;
		$data2->ipsum->dolor = 'mit';
		$data2->ipsum->carac = 'kol';

		$datas = array();
		$datas[] = $data1;
		$datas[] = $data2;

		foreach ($datas as $data)
		{
			$result = Arr::fromObject($data);

			$this->assertTrue(is_array($result), 'Value returned was not an array');

			foreach ((array)$data as $key => $val)
			{
				$this->assertTrue(isset($result[$key]), 'Array value not set from object property');
				if (!is_array($val) && !is_object($val))
				{
					$this->assertEquals($result[$key], $val);
				}
				else
				{
					$this->assertTrue(isset($result[$key]), 'Array value not set from object property');

					foreach ((array)$val as $k => $v)
					{
						$this->assertTrue(isset($result[$key][$k]), 'Property not set on returned object');
						$this->assertEquals($result[$key][$k], $v);
					}
				}
			}
		}

		$result = Arr::fromObject($data2, false);

		$this->assertTrue(is_array($result), 'Value returned was not an array');
		foreach ((array)$data2 as $key => $val)
		{
			$this->assertTrue(isset($result[$key]), 'Array value not set from object property');
			$this->assertEquals($result[$key], $val);
		}
	}

	/**
	 * Tests determining if array is associative array
	 *
	 * @covers  \Hubzero\Utility\Arr::isAssociative
	 * @return  void
	 **/
	public function testIsAssociative()
	{
		$data = array(
			'one' => '1',
			'two' => '322',
			'three' => 55
		);

		$this->assertTrue(Arr::isAssociative($data), 'Value is an associative array');

		$data = array(
			133,
			675,
			744
		);

		$this->assertFalse(Arr::isAssociative($data), 'Value is not an associative array');

		$data = new \stdClass;
		$data->one = 'foo';
		$data->two = 'bar';

		$this->assertFalse(Arr::isAssociative($data), 'Value is not an associative array');
	}
}

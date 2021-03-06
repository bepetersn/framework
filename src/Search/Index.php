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
 * @package   hubzero-cms
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Hubzero\Search;

use Hubzero\Search\Adapters;

/**
 * Index - For indexing operations
 * 
 */
class Index
{
	/**
	 * __construct 
	 * 
	 * @param mixed $config - Configuration object
	 * @access public
	 * @return void
	 */
	public function __construct($config)
	{
		$engine = $config->get('engine');
		if ($engine != 'hubgraph')
		{
			$adapter = "\\Hubzero\\Search\\Adapters\\" . ucfirst($engine) . 'IndexAdapter';
			$this->adapter = new $adapter($config);
		}
		return $this;
	}

	/**
	 * getLogs - Returns an array of search engine query log entries
	 * 
	 * @access public
	 * @return void
	 */
	public function getLogs()
	{
		$logs = $this->adapter->getLogs();
		return $logs;
	}

	/**
	 * defragment search index
	 *
	 * @return void
	 */
	public function optimize()
	{
		return $this->adapter->optimize();
	}

	/**
	 * lastInsert - Returns the timestamp of the last document indexed
	 * 
	 * @access public
	 * @return void
	 */
	public function lastInsert()
	{
		$lastInsert = $this->adapter->lastInsert();
		return $lastInsert;
	}

	/**
	 * status - Checks whether or not the search engine is responding 
	 *
	 * @access public
	 * @return void
	 */
	public function status()
	{
		return $this->adapter->status();
	}

	/**
	 * index - Stores a document within an index
	 * 
	 * @param mixed $document 
	 * @access public
	 * @return void
	 */
	public function index($document, $overwrite = null, $commitWithin = 3000, $buffer = 1500)
	{
		return $this->adapter->index($document, $overwrite, $commitWithin, $buffer);
	}

	/**
	 * updateIndex - Update existing index item
	 * 
	 * @param mixed $document 
	 * @access public
	 * @return void
	 */
	public function updateIndex($document, $commitWithin = 3000)
	{
		return $this->adapter->updateIndex($document, $commitWithin);
	}

	/**
	 * delete - Deletes a document from the index
	 * 
	 * @param string $id 
	 * @access public
	 * @return void
	 */
	public function delete($id)
	{
		return $this->adapter->delete($id);
	}
}

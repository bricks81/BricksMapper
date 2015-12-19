<?php

/**
 * Bricks Framework & Bricks CMS
 * http://bricks-cms.org
 *
 * The MIT License (MIT)
 * Copyright (c) 2015 bricks-cms.org
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
 */

namespace Bricks\Mapper;

use Bricks\Config\ConfigInterface;
use Bricks\ClassLoader\ClassLoaderInterface;
use Bricks\Mapper\DefaultMapper;

class Mapper {
	
	/**
	 * @var \Bricks\ClassLoader\ClassLoaderInterface
	 */
	protected $classLoader;
	
	/**
	 * @var array
	 */
	protected $transactions = array();
	
	/**
	 * @param ClassLoaderInterface $classLoader
	 */
	public function setClassLoader(ClassLoaderInterface $classLoader){
		$this->classLoader = $classLoader;
	}
	
	/**
	 * @return ClassLoaderInterface
	 */
	public function getClassLoader(){
		return $this->classLoader;
	}
	
	/**
	 * @return \Bricks\Config\Config
	 */
	public function getConfig(){
		return $this->getClassLoader()->getConfig();
	}
	
	public function getAdapters($aliasOrClass){
		$class = $this->getClassLoader()->aliasToClass($aliasOrClass)?:$aliasOrClass;
		$class = $this->getClassLoader()->classOverwrite($class);
		$data = $this->getConfig()->get('BricksMapper.defaultAdapters');
		$data2 = $this->getConfig()->get('BricksMapper.adapters.'.$class);
		if($data2){
			$data = array_merge($data,$data2);
		}
		return $data;
	}
	
	/**
	 * @param string $aliasOrClass	 
	 * @return array
	 */
	public function get($aliasOrClass){
		$adapters = $adapters?:$this->getAdapters($aliasOrClass);
		return $this->getClassLoader()->singleton($aliasOrClass,array(
			'BricksMapper' => $this,
			'adapters' => $adapters,
		));
	}
	
	/**
	 * @param MapperInterface $mapper
	 */
	public function beginTransaction(MapperInterface $mapper){
		$writeAdapter = $mapper->getWriteAdapter();
		$hash = spl_object_hash($writeAdapter);
		if(!isset($this->transactions[$hash])){
			$writerAdapter->beginTransaction();
			$this->transactions[$hash] = $writeAdapter;				
		}
	}
	
	/**
	 * @param MapperInterface $mapper
	 */
	public function commit(MapperInterface $mapper){
		$writeAdapter = $mapper->getWriteAdapter();
		$hash = spl_object_hash($writeAdapter);
		if(isset($this->transactions[$hash])){
			$writeAdapter->commit();
			unset($this->translactions[$hash]);
		}
	}
	
	/**
	 * @param MapperInterface $mapper
	 */
	public function rollback(MapperInterface $mapper){
		$writeAdapter = $mapper->getWriteAdapter();
		$writeAdapter->rollback();		
	}
	
}
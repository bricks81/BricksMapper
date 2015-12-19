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

use Bricks\Model\ModelInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\RowGateway\RowGateway;
use Zend\Db\Adapter\AdapterInterface;

class DefaultMapper 
implements AdapterAwareInterface, MapperInterface {
	
	/**
	 * @var \Zend\Db\Adapter\AdapterInterface
	 */
	protected $readAdapter = null;
	
	/**
	 * @var \Zend\Db\Adapter\AdapterInterface
	 */
	protected $writeAdapter = null;
	
	/**
	 * @var \Zend\Db\Adapter\AdapterInterface
	 */
	protected $installAdapter = null;
	
	/**
	 * {@inheritDoc}
	 * @see \Bricks\Mapper\AdapterAwareInterface::setReadAdapter()
	 */
	public function setReadAdapter(AdapterInterface $adapter){
		$this->readAdapter = $adapter;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Bricks\Mapper\AdapterAwareInterface::getReadAdapter()
	 */
	public function getReadAdapter(){
		return $this->readAdapter;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Bricks\Mapper\AdapterAwareInterface::setWriteAdapter()
	 */
	public function setWriteAdapter(AdapterInterface $adapter){
		$this->writeAdapter = $adapter;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Bricks\Mapper\AdapterAwareInterface::getWriteAdapter()
	 */
	public function getWriteAdapter(){
		return $this->writeAdapter;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Bricks\Mapper\AdapterAwareInterface::setInstallAdapter()
	 */
	public function setInstallAdapter(AdapterInterface $adapter){
		$this->installAdapter = $adapter;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Bricks\Mapper\AdapterAwareInterface::getInstallAdapter()
	 */
	public function getInstallAdapter(){
		return $this->installAdapter;
	}
	
	/**
	 * @return Select
	 */
	public function select(){
		return $this->getTable()->getSql()->select($this->getTable()->getTable());
	}
	
	/**
	 * @param array $pkeys
	 * @param Select
	 * @retrun null;
	 */
	public function selectByPk(array $pkeys,Select $select){
		foreach($pkeys AS $key => $value){
			$select->where($key.' = ?',$value);
		}
	}
	
	/**
	 * @param Select $select
	 * @return
	 */
	public function load($modelName,$select){
		$rowset = $this->getTable()->selectWith($select);
		foreach($rowset AS $row){
			
		}
	}
	
	/**
	 * @param ModelInterface $model	 
	 */
	public function save(ModelInterface $model){
		$namespace = $model->getNamespace();
		$class = get_class($model);
		$pkeys = $this->getPrimaryKeys($model);
		$data = $model->extract();
		if(false === array_search($pkeys,$data)){
			$this->insert($model);
		} else {
			$this->update($model);
		}
	}
	
	/**
	 * @param ModelInterface $model
	 */
	public function insert(ModelInterface $model){
		$namespace = $model->getNamespace();
		$data = $this->mapData($model);
		$data = $this->getAdapter()->insert($data);
		foreach($data AS $key => $value){
			$model->$key = $value;
		}	
	}
	
	/**
	 * @param ModelInterface $model
	 */
	public function update($model){
		$namespace = $model->getNamespace();
		$data = $this->mapData($model);
		$data = $this->getAdapter()->update($model);
		foreach($data AS $key => $value){
			$model->$key = $value;
		}				
	}
	
	/**
	 * @param ModelInterface $model
	 */
	public function delete($model){
		$namespace = $model->getNamespace();
		$data = $this->getPrimaryKeys($model);
		$this->getAdapter()->delete($model);
	}
	
}
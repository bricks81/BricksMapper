<?php

return array(
	'service_manager' => array(
		'factories' => array(			
			'BricksMapper' => 'Bricks\Mapper\Mapper',
		),		
		'aliases' => array(
			'bricksReadAdapter' => 'Zend\Db\Adapter\Adapter',
			'bricksWriteAdapter' => 'Zend\Db\Adapter\Adapter',			
			'bricksInstallAdapter' => 'Zend\Db\Adapter\Adapter',
		),
	),	
	'BricksConfig' => array(
		'__DEFAULT_NAMESPACE__' => array(			
			'BricksClassLoader' => array(
				'aliasMap' => array(
					'BricksMapper' => array(						
						'mapperClass' => 'Bricks\Mapper\Mapper',						
						'defaultMapper' => 'Bricks\Mapper\DefaultMapper',
						
					),
				),
				'classMap' => array(
					'Bricks\Mapper\Database' => array(
						'factories' => array(
							'Bricks\Mapper\Factory\DatabaseFactory'
						),
					),
				),
			),
			'BricksMapper' => array(
				'defaultMapper' => 'Bricks\Mapper\defaultMapper',
				'defaultAdapters' => array(
					'read' => 'bricksReadAdapter',
					'write' => 'bricksWriteAdapter',
					'install' => 'bricksInstallAdapter',
				),
				'adapters' => array(
					'Bricks\Mapper\defaultMapper' => array(
						'read' => 'bricksReadAdapter',
						'write' => 'bricksWriteAdapter',
						'install' => 'bricksInstallAdapter',
					),
				),
				'map' => array(
					'Bricks\Model\DefaultModel' => array(
						'BricksMapper.defaultMapper', // or a class
					),
				),
				'databases' => array(
					'__DEFAULT_SCHEMA__' => array(
						'table_prefix' => '',
						'tables' => array(
							'config' => array(
								'columns' => array(),
								'constraints' => array(),
							),
						),
					),
				),
			),
		),		
	),
);
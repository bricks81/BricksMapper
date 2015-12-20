<?php

return array(
	'service_manager' => array(
		'factories' => array(			
			'BricksMapper' => 'Bricks\Mapper\Mapper',
			'BricksMapperAdapter' => 'Bricks\Mapper\Adapter',
			'BricksMapperDatabase' => 'Bricks\Mapper\Database',
		),		
		'aliases' => array(
			'bricksReadAdapter' => 'Zend\Db\Adapter\Adapter',
			'bricksWriteAdapter' => 'Zend\Db\Adapter\Adapter',			
			'bricksInstallAdapter' => 'Zend\Db\Adapter\Adapter',
		),
	),	
	'BricksConfig' => array(
		'__DEFAULT_NAMESPACE__' => array(			
			'BricksMapper' => array(
				'defaultMapper' => 'Bricks\Mapper\defaultMapper',
				'defaultAdapters' => array(
					'read' => 'bricksReadAdapter',
					'write' => 'bricksWriteAdapter',
					'install' => 'bricksInstallAdapter',
				),
				/*
				'adapters' => array(
					'Bricks\Mapper\defaultMapper' => array(
						'read' => 'bricksReadAdapter',
						'write' => 'bricksWriteAdapter',
						'install' => 'bricksInstallAdapter',
					),
				),
				*/
				/*
				'map' => array(
					'Bricks\Model\DefaultModel' => array(
						'Bricks\Mapper\Mappers\DefaultMapper',
					),
				),
				*/
				/*
				'databases' => array(
					'__DEFAULT_SCHEMA__' => array(
						'tablePrefix' => '',
						'tables' => array(
							'config' => array(
								'columns' => array(),
								'constraints' => array(),
							),
						),
					),
				),
				*/
			),
		),		
	),
);
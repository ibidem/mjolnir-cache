<?php namespace ibidem\theme;

use \ibidem\types\Enum_Requirement as Requirement;

return array
	(
		'ibidem\cache' => array
			(
				 'extension=php_apc' => function ()
					{
						if (\extension_loaded('APC'))
						{
							return Requirement::available;
						}
						
						return Requirement::failed;
					},
				
				'extension=php_memcache' => function ()
					{
						if (\extension_loaded('memcached'))
						{
							return Requirement::available;
						}
						
						return Requirement::failed;
					}
			),
	);

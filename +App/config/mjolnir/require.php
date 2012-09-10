<?php namespace mjolnir\theme;

use \mjolnir\types\Enum_Requirement as Requirement;

return array
	(
		'mjolnir\stash' => array
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
						if(\class_exists('Memcache'))
						{
							return Requirement::available;
						}
						
						return Requirement::failed;
					}
			),
	);

<?php namespace mjolnir\theme;

return array
	(
		'mjolnir\stash' => array
			(
				 'extension=php_apc' => function ()
					{
						if (\extension_loaded('APC'))
						{
							return 'available';
						}

						return 'failed';
					},

				'extension=php_memcache' => function ()
					{
						if(\class_exists('Memcache'))
						{
							return 'available';
						}

						return 'failed';
					}
			),
	);

<?php return array
	(
		'default.cache' => 'File',

	## Cache settings
	
		'Memcached' => array
			(
				'lifetime.default' => 3600,
				'compression' => true, // Use Zlib compression
			
				'timeout.recv' => 3000,
				'timeout.send' => 1000,
				'tcp.nodelay' => true,
				'prefix' => 'memcached_',
			
				// giving instances a persistence id won't terminate
				// the connection after use.
				'persistent_id' => 'memcached_1',
			
				'servers' => array
					(
						array
						(
							'host' => 'localhost',
							'port' => 11211,
							'weight' => 1,
							 
						),
					),
			),
	
		'APC' => array
			(
				'default_expire' => 3600,
			),
	
		'File' => array
			(
				'cache.dir' => APPPATH.'cache'.DIRECTORY_SEPARATOR,
				'lifetime.default' => 3600,
			),
	);

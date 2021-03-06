<?php return array
	(
		'default.cache' => 'File',

	## Cache settings

		'APC' => array
			(
				'enabled' => false,
				'lifetime.default' => 3600,
			),

		'Memcached' => array
			(
				'enabled' => false,
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

		'Memcache' => array
			(
				'enabled' => false,
				'lifetime.default' => 3600,
				'host' => 'localhost',
				'port' => 11211,
			),

		'TempMemory' => array
			(
				'enabled' => true,
				'lifetime.default' => 3600,
			),

		'File' => array
			(
				'enabled' => true, # intentionally enabled, with everything else disabled
				'cache.dir' => \app\Env::key('etc.path').'cache/file.cache/',
				'lifetime.default' => 3600,

				// truecking indicates if failure to remove a cache file
				// (typically caused by faulty permissions) should NOT halt the
				// system operations; checks will still be made but no
				// exceptions are thrown. Set this to false if integrity is very
				// important
				'trucking' => true,
			),
	);

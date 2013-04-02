<?php namespace mjolnir\theme;

$cache_config = \app\CFS::config('mjolnir/cache');

return array
	(
		'mjolnir\cache' => array
			(
				 'extension=php_apc' => function () use ($cache_config)
					{
						if ( ! $cache_config['APC']['enabled'])
						{
							return [ 'satisfied' => 'disabled' ];
						}

						if (\extension_loaded('APC'))
						{
							return 'satisfied';
						}

						return 'failed';
					},

				'extension=php_memcache' => function () use ($cache_config)
					{
						if ( ! $cache_config['Memcache']['enabled'])
						{
							return [ 'satisfied' => 'disabled' ];
						}

						if (\class_exists('Memcache'))
						{
							return 'satisfied';
						}

						return 'failed';
					},

				'extension=php_memcached' => function () use ($cache_config)
					{
						if ( ! $cache_config['Memcached']['enabled'])
						{
							return [ 'satisfied' => 'disabled' ];
						}

						if (\class_exists('Memcached'))
						{
							return 'satisfied';
						}

						return 'failed';
					},

				'ETCPATH/cache' => function () use ($cache_config)
					{
						if ( ! $cache_config['File']['enabled'])
						{
							return [ 'failed' => 'disabled' ];
						}

						if ( ! \file_exists(\app\Env::key('etc.path').'cache/'))
						{
							return 'error';
						}

						if ( ! \is_writable(\app\Env::key('etc.path').'cache/'))
						{
							return 'error';
						}

						return 'satisfied';
					},
			),
	);

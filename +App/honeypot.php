<?php namespace app;

// This is a IDE honeypot. :)

// HowTo: minion honeypot -n "ibidem\\cache"

class Cache_APC extends \ibidem\cache\Cache_APC { /** @return \ibidem\cache\Cache_APC */ function instance() {} }
class Cache_File extends \ibidem\cache\Cache_File { /** @return \ibidem\cache\Cache_File */ function instance() {} }
class Cache_Memcached extends \ibidem\cache\Cache_Memcached { /** @return \ibidem\cache\Cache_Memcached */ function instance() {} }
class Cache_SQLite extends \ibidem\cache\Cache_SQLite { /** @return \ibidem\cache\Cache_SQLite */ function instance() {} }
class Cache_WinCache extends \ibidem\cache\Cache_WinCache { /** @return \ibidem\cache\Cache_WinCache */ function instance() {} }
class Cache_XCache extends \ibidem\cache\Cache_XCache { /** @return \ibidem\cache\Cache_XCache */ function instance() {} }
class Cache_eAccelerator extends \ibidem\cache\Cache_eAccelerator { /** @return \ibidem\cache\Cache_eAccelerator */ function instance() {} }
class Cache extends \ibidem\cache\Cache { /** @return \ibidem\cache\Cache */ function instance() {} }

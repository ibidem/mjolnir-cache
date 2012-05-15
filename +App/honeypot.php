<?php namespace app;

// This is a IDE honeypot. :)

// HowTo: minion honeypot -n "ibidem\\cache"

class Cache_APC extends \ibidem\cache\Cache_APC { /** @return \ibidem\cache\Cache_APC */ static function instance() { return parent::instance(); } }
class Cache_File extends \ibidem\cache\Cache_File { /** @return \ibidem\cache\Cache_File */ static function instance() { return parent::instance(); } }
class Cache_Memcached extends \ibidem\cache\Cache_Memcached { /** @return \ibidem\cache\Cache_Memcached */ static function instance() { return parent::instance(); } }
class Cache extends \ibidem\cache\Cache { /** @return \ibidem\cache\Cache */ static function instance() { return parent::instance(); } }

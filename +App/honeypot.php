<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\cache'

class Stash_File extends \mjolnir\cache\Stash_File { /** @return \mjolnir\cache\Stash_File */ static function instance() { return parent::instance(); } }
class Stash_Memcache extends \mjolnir\cache\Stash_Memcache { /** @return \mjolnir\cache\Stash_Memcache */ static function instance() { return parent::instance(); } }
class Stash_Memcached extends \mjolnir\cache\Stash_Memcached { /** @return \mjolnir\cache\Stash_Memcached */ static function instance() { return parent::instance(); } }
class Stash_Null extends \mjolnir\cache\Stash_Null { /** @return \mjolnir\cache\Stash_Null */ static function instance() { return parent::instance(); } }
class Stash extends \mjolnir\cache\Stash {}
class ViewStash extends \mjolnir\cache\ViewStash {}

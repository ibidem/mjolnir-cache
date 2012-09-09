<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\cache'

class SQLStash extends \mjolnir\cache\SQLStash { /** @return \mjolnir\cache\SQLStash */ static function instance() { return parent::instance(); } }
class Stash_APC extends \mjolnir\cache\Stash_APC { /** @return \mjolnir\cache\Stash_APC */ static function instance() { return parent::instance(); } }
class Stash_Base extends \mjolnir\cache\Stash_Base { /** @return \mjolnir\cache\Stash_Base */ static function instance() { return parent::instance(); } }
class Stash_File extends \mjolnir\cache\Stash_File { /** @return \mjolnir\cache\Stash_File */ static function instance() { return parent::instance(); } }
class Stash_Memcache extends \mjolnir\cache\Stash_Memcache { /** @return \mjolnir\cache\Stash_Memcache */ static function instance() { return parent::instance(); } }
class Stash_Memcached extends \mjolnir\cache\Stash_Memcached { /** @return \mjolnir\cache\Stash_Memcached */ static function instance() { return parent::instance(); } }
class Stash extends \mjolnir\cache\Stash { /** @return \mjolnir\cache\Stash */ static function instance() { return parent::instance(); } }
trait Trait_TaggedStash { use \mjolnir\cache\Trait_TaggedStash; }
class ViewStash extends \mjolnir\cache\ViewStash {}

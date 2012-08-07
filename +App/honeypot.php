<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'ibidem\cache'

class SQLStash extends \ibidem\cache\SQLStash { /** @return \ibidem\cache\SQLStash */ static function instance() { return parent::instance(); } }
class Stash_APC extends \ibidem\cache\Stash_APC { /** @return \ibidem\cache\Stash_APC */ static function instance() { return parent::instance(); } }
class Stash_Base extends \ibidem\cache\Stash_Base { /** @return \ibidem\cache\Stash_Base */ static function instance() { return parent::instance(); } }
class Stash_File extends \ibidem\cache\Stash_File { /** @return \ibidem\cache\Stash_File */ static function instance() { return parent::instance(); } }
class Stash_Memcached extends \ibidem\cache\Stash_Memcached { /** @return \ibidem\cache\Stash_Memcached */ static function instance() { return parent::instance(); } }
class Stash extends \ibidem\cache\Stash { /** @return \ibidem\cache\Stash */ static function instance() { return parent::instance(); } }
trait Trait_TaggedStash { use \ibidem\cache\Trait_TaggedStash; }
class ViewStash extends \ibidem\cache\ViewStash {}

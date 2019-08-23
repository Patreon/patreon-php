<?php
namespace Patreon\Tests;

use ParagonIE\HiddenString\HiddenString;
use Patreon\OAuth;
use PHPUnit\Framework\TestCase;

/**
 * Class OAuthTest
 * @package Patreon\Tests
 */
class OAuthTest extends TestCase
{
    public function testOAuthConstructor()
    {
        $this->assertInstanceOf(
            OAuth::class,
            new OAuth('a', 'b')
        );

        $this->assertInstanceOf(
            OAuth::class,
            new OAuth(new HiddenString('a'), new HiddenString('b'))
        );
    }
}

<?php
namespace Patreon\Tests;

use Patreon\AuthUrl;
use PHPUnit\Framework\TestCase;

class AuthUrlTest extends TestCase
{
    public function testBasics()
    {
        $url = new AuthUrl('EXAMPLE');
        $clone = $url->withClientId('DIFFERENT');
        $this->assertNotEquals($url->buildUrl(), $clone->buildUrl());

        $full = (new AuthUrl('exampleClientId', 'http://localhost'))
            ->withAddedScope('identity')
            ->withAddedScope('identity[email]')
            ->withState(['secret' => 'agent man'])
            ->with('fursona', 'dhole');

        $this->assertSame(
            'https://www.patreon.com/oauth2/authorize?response_type=code&client_id=exampleClientId&state%5Bsecret%5D=agent+man&state%5Bfursona%5D=dhole&scope=identity+identity%5Bemail%5D',
            (string) $full
        );
    }
}

<?php

namespace Unit\NaMi;

use Tests\UnitTestCase;
use App\Services\NaMi\NaMiService;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
use \Mockery as M;

class NaMiLoginTest extends UnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    private function createNamiUser($user, $password) {
        $resolver = M::mock(NaMiUserResolver::class);
        $resolver->shouldReceive('user')->andReturn($user);
        $resolver->shouldReceive('password')->andReturn($password);
        $this->app->instance(NaMiUserResolver::class, $resolver);
    }

    /** @test */
    public function it_loggs_in_to_nami_successful() {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([
            new Response(200, [], ''),
            new Response(200, [], '{"servicePrefix":null,"methodCall":null,"response":null,"statusCode":0,"statusMessage":"","apiSessionName":"JSESSIONID","apiSessionToken":"PXU5-ewCjYv9i7f7mudhebje","minorNumber":2,"majorNumber":1}')
        ]);

        $stack = HandlerStack::create($mock);
        $stack->push($history);

        $client = new Client(['handler' => $stack]);
        $this->app->instance(Client::class, $client);

        $user = $this->createNamiUser('90166', 'PW');

        $nami = app(NaMiService::class);
        /** @todo Mock userResolver and add this to the Service */
        $nami->setUser('90166');
        $nami->setPassword('PW');
        $nami->newSession();

        $this->assertEquals('https://nami.dpsg.de/ica/pages/login.jsp', $container[0]['request']->getUri());
        $this->assertEquals('https://nami.dpsg.de/ica/rest/nami/auth/manual/sessionStartup', $container[1]['request']->getUri());
        $this->assertEquals(
            'Login=API&redirectTo=.%2Fapp.jsp&username=90166&password=PW',
            (string) $container[1]['request']->getBody()
        );
    }

    /** @test */
    public function it_throws_an_error_when_no_user_given() {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler();
        $stack = HandlerStack::create($mock);
        $stack->push($history);

        $client = new Client(['handler' => $stack]);
        $this->app->instance(Client::class, $client);

        $nami = app(NaMiService::class);
        /** @todo Mock userResolver and add this to the Service */
        $nami->setUser('');
        $nami->setPassword('');

        try {
            $nami->newSession();
            $this->assertTrue(false, 'NaMi sollte eine Exception werfen, wenn Benutzer und Passwort nicht gesetzt.');
        } catch(NaMiLoginException $e) {}

        $this->assertCount(0, $container);
    }
}

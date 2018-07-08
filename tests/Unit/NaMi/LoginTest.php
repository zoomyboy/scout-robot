<?php

namespace Unit\NaMi;

use App\NaMi\Exceptions\LoginException;
use App\NaMi\Interfaces\UserResolver;
use App\Services\NaMi\NaMiService;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Tests\UnitTestCase;
use \Mockery as M;

class NaMiLoginTest extends UnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    private function createNamiUser($user, $password) {
        $resolver = M::mock(UserResolver::class);
        $resolver->shouldReceive('getUsername')->andReturn($user);
        $resolver->shouldReceive('getPassword')->andReturn($password);
        $this->app->instance(UserResolver::class, $resolver);

        return $resolver;
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

         $this->createNamiUser('90166', 'PW');

        $nami = app(NaMiService::class);
        $nami->newSession();

        $this->assertEquals('https://nami.dpsg.de/ica/pages/login.jsp', $container[0]['request']->getUri());
        $this->assertEquals('https://nami.dpsg.de/ica/rest/nami/auth/manual/sessionStartup', $container[1]['request']->getUri());
        $this->assertEquals(
            'Login=API&redirectTo=.%2Fapp.jsp&username=90166&password=PW',
            (string) $container[1]['request']->getBody()
        );
    }

    /**
     * @test
     * @expectedException App\NaMi\Exceptions\LoginException
     */
    public function it_throws_an_error_when_credentials_are_wrong() {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([
            new Response(200, [], ''),
            new Response(200, [], '{"servicePrefix":null,"methodCall":null,"response":null,"statusCode":3000,"statusMessage":"Benutzer nicht gefunden oder Passwort falsch.","apiSessionName":"JSESSIONID","apiSessionToken":"yxLjt9nY-eD7L8IZeXWSHLyt","minorNumber":0,"majorNumber":0}')
        ]);

        $stack = HandlerStack::create($mock);
        $stack->push($history);

        $client = new Client(['handler' => $stack]);
        $this->app->instance(Client::class, $client);

         $this->createNamiUser('90166', 'PW');

        $nami = app(NaMiService::class);
        $nami->newSession();
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

        $this->createNamiUser('', '');

        $nami = app(NaMiService::class);

        try {
            $nami->newSession();
            $this->assertTrue(false, 'NaMi sollte eine Exception werfen, wenn Benutzer und Passwort nicht gesetzt.');
        } catch(LoginException $e) {}

        $this->assertCount(0, $container);
    }
}

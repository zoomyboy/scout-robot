<?php

namespace Unit\NaMi;

use App\NaMi\Exceptions\LoginException;
use App\Services\NaMi\NaMiService;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Tests\UnitTestCase;
use Tests\Unit\NaMiTestCase;
use \Mockery as M;

class LoginTest extends NaMiTestCase {
    public function setUp() {
        parent::setUp();
    }

    /** @test */
    public function it_loggs_in_to_nami_successful() {
        $this->fakeGuzzle([
            new Response(200, [], ''),
            new Response(200, [
                'Set-Cookie' => 'JSESSIONID=d4-CjOOIMAxKVOVSsVSy23CD.srv-nami06; path=/ica'
            ], '{"servicePrefix":null,"methodCall":null,"response":null,"statusCode":0,"statusMessage":"","apiSessionName":"JSESSIONID","apiSessionToken":"PXU5-ewCjYv9i7f7mudhebje","minorNumber":2,"majorNumber":1}')
        ]);

        $this->createNamiUser('90166', 'PW');

        $nami = app(NaMiService::class);
        $nami->newSession();

        $this->assertEquals('https://nami.dpsg.de/ica/pages/login.jsp', $this->container[0]['request']->getUri());
        $this->assertEquals('https://nami.dpsg.de/ica/rest/nami/auth/manual/sessionStartup', $this->container[1]['request']->getUri());
        $this->assertEquals(
            'Login=API&redirectTo=.%2Fapp.jsp&username=90166&password=PW',
            (string) $this->container[1]['request']->getBody()
        );

        $this->assertEquals(
            'd4-CjOOIMAxKVOVSsVSy23CD.srv-nami06',
            $nami->cookie->getCookieByName('JSESSIONID')->getValue()
        );
    }

    /**
     * @test
     * @expectedException App\NaMi\Exceptions\LoginException
     */
    public function it_throws_an_error_when_credentials_are_wrong() {
        $this->fakeGuzzle([
            new Response(200, [], ''),
            new Response(200, [], '{"servicePrefix":null,"methodCall":null,"response":null,"statusCode":3000,"statusMessage":"Benutzer nicht gefunden oder Passwort falsch.","apiSessionName":"JSESSIONID","apiSessionToken":"yxLjt9nY-eD7L8IZeXWSHLyt","minorNumber":0,"majorNumber":0}')
        ]);

        $this->createNamiUser('90166', 'PW');

        $nami = app(NaMiService::class);
        $nami->newSession();
    }

    /**
     * @test
     * @expectedException App\NaMi\Exceptions\LoginException
     */
    public function it_throws_an_error_when_account_is_locked() {
        $this->fakeGuzzle([
            new Response(200, [], ''),
            new Response(200, [], '{"servicePrefix":null,"methodCall":null,"response":null,"statusCode":3000,"statusMessage":"Die höchste Anzahl von Login-Versuchen wurde erreicht. Ihr Konto ist für 15 Minuten gesperrt worden. Nach Ablauf dieser Zeitspanne wird ihr Zugang wieder freigegeben.","apiSessionName":"JSESSIONID","apiSessionToken":"rd3dJUxmi098jbCy507jEg_X","minorNumber":0,"majorNumber":0}')
        ]);

        $this->createNamiUser('90166', 'PW');

        $nami = app(NaMiService::class);
        $nami->newSession();
    }

    /** @test */
    public function it_throws_an_error_when_no_user_given() {
        $this->fakeGuzzle([]);
        $this->createNamiUser('', '');

        $nami = app(NaMiService::class);

        try {
            $nami->newSession();
            $this->assertTrue(false, 'NaMi sollte eine Exception werfen, wenn Benutzer und Passwort nicht gesetzt.');
        } catch(LoginException $e) {}

        $this->assertCount(0, $this->container);
    }
}

<?php

 namespace Tests\Unit\Nami;

use App\Nami\Service;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Tests\UnitTestCase;
use Tests\Unit\NamiTestCase;

 class ServiceTest extends NamiTestCase {
    public function setUp() {
        parent::setUp();

        $this->createNamiUser('philipp', 'izz', 4);
    }

    /** @test */
    public function it_gets_all_members_from_nami() {
        $this->fakeGuzzle([
            new Response(200, [], ''),
            new Response(200, [
                'Set-Cookie' => 'JSESSIONID=d4-CjOOIMAxKVOVSsVSy23CD.srv-nami06; path=/ica'
            ], '{"servicePrefix":null,"methodCall":null,"response":null,"statusCode":0,"statusMessage":"","apiSessionName":"JSESSIONID","apiSessionToken":"PXU5-ewCjYv9i7f7mudhebje","minorNumber":2,"majorNumber":1}'),
            new Response(200, [], '[
                {"id": 3},
                {"id": 6}
            ]')
        ]);

        $members = app(Service::class)->get('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/3/2334');

        $this->assertEquals(3, $members[0]->id);
        $this->assertEquals(6, $members[1]->id);
        $this->assertInstanceOf(Collection::class, $members);
    }
 }

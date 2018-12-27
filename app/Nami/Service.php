<?php

namespace App\Nami;

use App\Conf;
use App\Nami\Exceptions\TooManyLoginAttemptsException;
use App\Nami\Exceptions\LoginException;
use App\Nami\Interfaces\UserResolver;
use GuzzleHttp\Client as GuzzleClient;

class Service {

    public $cookie;
    protected $baseUrl;
    private $client;

    public function __construct(GuzzleClient $client, UserResolver $user) {
        $this->baseUrl = config('nami.baseurl');
        $this->user = $user;
        $this->client = $client;
        $this->cookie = new \GuzzleHttp\Cookie\CookieJar();
    }

    public function setUser(UserResolver $user) {
        $this->user = $user;
    }

    public function login() {
        if (!$this->user->hasCredentials()) {
            throw new LoginException('Benutzer oder passwort für NaMi nicht gesetzt.');
        }

        $r = $this->client->request('GET', $this->baseUrl.'/ica/pages/login.jsp', ['cookies' => $this->cookie]);

        $login = $this->client->request(
            'POST', $this->baseUrl.'/ica/rest/nami/auth/manual/sessionStartup', [
                'form_params' => [
                    'Login' => 'API',
                    'redirectTo' => './app.jsp',
                    'username' => $this->user->getUsername(),
                    'password' => $this->user->getPassword()
                ],
                'cookies' => $this->cookie
            ]
        );
        $login = json_decode(utf8_encode((string)$login->getBody()));

        if ($login->statusCode !== 0) {
            throw new LoginException($login->statusMessage);
        }
    }

    /** @todo testen mit guzzle fake */
    public function isSuccess($response) {
        return isset ($response->success) && $response->success === true
            && isset ($response->responseType) && $response->responseType == 'OK';
    }

    public function checkCredentials() {
        try {
            $this->login();
        } catch (LoginException $e) {
            return false;
        }

        return true;
    }

    public function get($url) {
        $this->login();

        $response = $this->client->request('GET', $this->baseUrl.$url, [
            'http_errors' => false,
            'cookies' => $this->cookie
        ]);

        $json = json_decode((string) $response->getBody());

        return collect($json);
    }

    public function post($url, $fields) {
        $this->login();

        $response = $this->client->request('POST', $this->baseUrl.$url, [
            'http_errors' => false,
            'cookies' => $this->cookie,
            'headers' => [
                'Accept' => '*/*',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
            ],
            'form_params' => $fields
        ]);

        $json = json_decode((string) $response->getBody());

        return collect($json);
    }

    public function put($url, $fields) {
        $this->login();

        $response = $this->client->request('PUT', $this->baseUrl.$url, [
            'http_errors' => false,
            'cookies' => $this->cookie,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => $fields
        ]);

        $json = json_decode((string) $response->getBody());

        if (is_null($json)) {
            \Log::critical('Api gibt kein JSON zurück', [
                'response' => (string) $response->getBody(),
                'fields' => $fields,
                'url' => $url
            ]);

            return null;
        }

        if (!$json->success || $json->success == false) {
            \Log::critical('Fehler beim Update', [
                'response' => (string) $response->getBody(),
                'fields' => $fields,
                'url' => $url
            ]);

            return null;
        }

        return collect($json);
    }
}

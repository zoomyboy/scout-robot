<?php

namespace App\Services\NaMi;

use App\Conf;
use App\Exceptions\NaMi\TooManyLoginAttemptsException;
use App\NaMi\Exceptions\LoginException;
use App\NaMi\Interfaces\UserResolver;
use GuzzleHttp\Client as GuzzleClient;

class NaMiService {

    public $cookie;
    protected $baseUrl;
    private $client;

    /** @var int Anzahl erlaubter Loginversuche */
    private $times = 3;

    public function __construct(GuzzleClient $client, UserResolver $user) {
        $this->baseUrl = config('nami.baseurl');
        $this->user = $user;
        $this->client = $client;
        $this->cookie = new \GuzzleHttp\Cookie\CookieJar();
    }

    /**
     * Generates a new Session when expired
     * @todo in login umbenennen
     */
    public function newSession() {
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
                    'password' => $this->password()
                ],
                'cookies' => $this->cookie
            ]
        );
        $login = json_decode(utf8_encode((string)$login->getBody()));

        if ($login->statusCode !== 0) {
            throw new LoginException($login->statusMessage);
        }
    }

    public function password() {
        return $this->user->getPassword();
    }

    /** @todo testen mit guzzle fake */
    public function isSuccess($response) {
        return isset ($response->success) && $response->success === true
            && isset ($response->responseType) && $response->responseType == 'OK';
    }

    /** @todo evtl beim resolving ausführen */
    public function checkCredentials($user, $password) {
        $this->setPassword($password);
        $this->setUser($user);
        try {
            $this->newSession();
        } catch (LoginException $e) {
            return false;
        }

        return true;
    }

    /** @todo mit guzzle lösen */
    public function get($url) {
        return $this->login(function() use ($url) {
            $handle = curl_init($this->getBaseUrl().$url);

            curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
            if (!env('NAMI_SSL')) {
                curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
            }
            curl_setopt($handle, CURLOPT_POSTFIELDS, '');
            curl_setopt ($handle, CURLOPT_POST, 0);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($handle, CURLOPT_COOKIEJAR, $this->getCookie());
            curl_setopt ($handle, CURLOPT_COOKIEFILE, $this->getCookie());
            $body = curl_exec($handle);
            curl_close($handle);

            if(config('nami.log')) {\Log::debug('NaMi-Response: '.$body);}

            return json_decode($body);
        });
    }

    /** @todo mit guzzle lösen */
    public function post($url, $fields) {
        return $this->login(function() use ($url, $fields) {
            $handle = curl_init($this->getBaseUrl().$url);

            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'POST');
            if (!env('NAMI_SSL')) {
                curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
            }
            curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($handle, CURLOPT_COOKIEJAR, $this->getCookie());
            curl_setopt ($handle, CURLOPT_COOKIEFILE, $this->getCookie());
            curl_setopt ($handle, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            $body = curl_exec($handle);
            curl_close($handle);

            if(config('nami.log')) {\Log::debug('NaMi-Response: '.$body);}

            return json_decode($body);
        });
    }

    public function put($url, $fields) {
        return $this->login(function() use ($url, $fields) {
            $handle = curl_init($this->getBaseUrl().$url);

            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
            if (!env('NAMI_SSL')) {
                curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
            }
            curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($handle, CURLOPT_COOKIEJAR, $this->getCookie());
            curl_setopt ($handle, CURLOPT_COOKIEFILE, $this->getCookie());
            curl_setopt ($handle, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            $body = curl_exec($handle);
            curl_close($handle);

            if(config('nami.log')) {\Log::debug('NaMi-Response: '.$body);}

            return json_decode($body);
        });
    }
}

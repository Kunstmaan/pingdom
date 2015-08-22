<?php

namespace Pingdom;

/**
 * Client object for executing commands on a web service.
 */
class Client
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $token;

    /**
     * @param string $username
     * @param string $password
     * @param string $token
     * @return Client
     */
    public function __construct($username, $password, $token)
    {
        $this->username = $username;
        $this->password = $password;
        $this->token = $token;

        return $this;
    }

    /**
     * Returns a list overview of all checks
     *
     * @return array
     * @throws \Exception
     */
    public function getAllChecks()
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.pingdom.com/api/2.0/']);

        try {
            $response = $client->get('https://api.pingdom.com/api/2.0/checks', [
                'auth' => [$this->username, $this->password],
                'headers' => [
                    'App-Key' => $this->token
                ]
            ]);
            $response = json_decode($response->getBody(), true);
            return $response['checks'];
        } catch (\Exception $e) {
            var_dump($e->getResponse()->getBody()->getContents());
            throw $e;
        }
    }

    /**
     * Returns a list of all Pingdom probe servers
     *
     * @return Probe\Server[]
     */
    public function getProbes()
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.pingdom.com/api/2.0/']);
        try {
            $response = $client->get('https://api.pingdom.com/api/2.0/probes', [
                'auth' => [$this->username, $this->password],
                'headers' => [
                    'App-Key' => $this->token
                ]
            ]);
            $response = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            var_dump($e->getResponse()->getBody()->getContents());
            throw $e;
        }
        $probes = array();

        foreach ($response['probes'] as $attributes) {
            $probes[] = new Probe\Server($attributes);
        }

        return $probes;
    }

    /**
     * Return a list of raw test results for a specified check
     *
     * @param int $checkId
     * @param int $limit
     * @param array|null $probes
     * @return array
     */
    public function getResults($checkId, $limit = 100, array $probes = null)
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.pingdom.com/api/2.0/']);

        $query = ['limit' => $limit];
        if (is_array($probes)) {
            $query['probes'] = implode(',', $probes);
        }

        try {
            $response = $client->get('https://api.pingdom.com/api/2.0/results/' . $checkId, [
                'query' => $query,
                'auth' => [$this->username, $this->password],
                'headers' => [
                    'App-Key' => $this->token
                ]
            ]);
            $response = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            var_dump($e->getResponse()->getBody()->getContents());
            throw $e;
        }
        return $response['results'];
    }

    /**
     * Get Intervals of Average Response Time and Uptime During a Given Interval
     *
     * @param int $checkId
     * @param string $resolution
     * @return array
     */
    public function getPerformanceSummary($checkId, $resolution = 'hour')
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.pingdom.com/api/2.0/']);
        $query = ['resolution' => $resolution, 'includeuptime' => 'true'];
        try {
            $response = $client->get('https://api.pingdom.com/api/2.0/summary.performance/' . $checkId, [
                'query' => $query,
                'auth' => [$this->username, $this->password],
                'headers' => [
                    'App-Key' => $this->token
                ]
            ]);
            return $response['summary'][$resolution . 's'];
        } catch (\Exception $e) {
            var_dump($e->getResponse()->getBody()->getContents());
            throw $e;
        }
    }

    /**
     * Adds a new HTTPCheck with the given parameters
     * @param string $name
     * @param string $host
     * @param string $url
     * @param string $sendtoemail
     * @param string $sendtoiphone
     * @param string $sendtoandroid
     * @param array $contactids
     * @return string
     */
    public function addHTTPCheck($name, $host, $url, $sendtoemail, $sendtoiphone, $sendtoandroid, $contactids)
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.pingdom.com/api/2.0/']);
        $query = ['name' => $name, 'host' => $host, 'type' => 'http', 'url' => $url, 'sendtoemail' => $sendtoemail, 'sendtoiphone' => $sendtoiphone, 'sendtoandroid' => $sendtoandroid, 'contactids' => implode(",", $contactids), 'use_legacy_notifications' => 'true'];
        try {
            $response = $client->post('https://api.pingdom.com/api/2.0/checks', [
                'query' => $query,
                'auth' => [$this->username, $this->password],
                'headers' => [
                    'App-Key' => $this->token
                ]
            ]);
            $response = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            var_dump($e->getResponse()->getBody()->getContents());
            throw $e;
        }
        return $response;
    }

    /**
     * Updates the HTTPcheck $checkid with the given parameters
     * @param int $checkId
     * @param string $name
     * @param string $host
     * @param string $url
     * @param string $sendtoemail
     * @param string $sendtoiphone
     * @param string $sendtoandroid
     * @param array $contactids
     * @return string
     */
    public function updateHTTPCheck($checkId, $name, $host, $url, $sendtoemail, $sendtoiphone, $sendtoandroid, $contactids)
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.pingdom.com/api/2.0/']);
        $query = ['name' => $name, 'host' => $host, 'type' => 'http', 'url' => $url, 'sendtoemail' => $sendtoemail, 'sendtoiphone' => $sendtoiphone, 'sendtoandroid' => $sendtoandroid, 'contactids' => implode(",", $contactids), 'use_legacy_notifications' => 'true'];
        try {
            $response = $client->put('https://api.pingdom.com/api/2.0/checks/' . $checkId, [
                'query' => $query,
                'auth' => [$this->username, $this->password],
                'headers' => [
                    'App-Key' => $this->token
                ]
            ]);
            $response = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            var_dump($e->getResponse()->getBody()->getContents());
            throw $e;
        }
        return $response;
    }

    /**
     * Remove a check with the given id
     * @param int $checkId
     * @return string
     */
    public function removeCheck($checkId)
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.pingdom.com/api/2.0/']);
        try {
            $response = $client->delete('https://api.pingdom.com/api/2.0/checks/' . $checkId, [
                'auth' => [$this->username, $this->password],
                'headers' => [
                    'App-Key' => $this->token
                ]
            ]);
            $response = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            var_dump($e->getResponse()->getBody()->getContents());
            throw $e;
        }
        return $response;
    }

    /**
     * Returns the id of the check with $name
     * @param string $name
     * @return int
     */
    public function getCheck($name)
    {
        $response = $this->getAllChecks();
        foreach ($response as $value) {
            if ($value['name'] == $name) {
                return $value['id'];
            }
        }
    }
}

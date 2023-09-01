<?php

namespace Justimmo\Api;

use Justimmo\Cache\CacheInterface;
use Justimmo\Cache\NullCache;
use Justimmo\Curl\CurlRequest;
use Justimmo\Exception\InvalidRequestException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class JustimmoApi
 * class for justimmo api
 */
class JustimmoApi implements JustimmoApiInterface
{
    /**
     * api versions supported
     *
     * @var array
     */
    protected $supportedVersions = ['v1'];

    /**
     * base url where the justimmo api is located
     *
     * @var string
     */
    protected $baseUrl = 'https://api.justimmo.at/rest';

    /**
     * version of api to be called
     *
     * @var string
     */
    protected $version = 'v1';

    /**
     * logger interface for logging
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * username of api access
     *
     * @var string
     */
    protected $username;

    /**
     * password for api access
     *
     * @var string
     */
    protected $password;

    /**
     * Cache class for caching results
     *
     * @var \Justimmo\Cache\CacheInterface
     */
    protected $cache;

    /**
     * culture for api calls to set if not explicetely set on call
     *
     * @var string
     */
    protected $culture = 'de';

    /**
     * options for php curl request
     *
     * @var array
     */
    protected $curlOptions = [
        CURLOPT_CONNECTTIMEOUT_MS => 2500,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
    ];

    /**
     * @param  LoggerInterface  $logger
     * @param  CacheInterface  $cache
     * @param  string  $version
     * @param  string  $culture
     */
    public function __construct($username, $password, LoggerInterface $logger = null, CacheInterface $cache = null, $version = 'v1', $culture = 'de')
    {
        $this
            ->setLogger(($logger ?: new NullLogger()))
            ->setCache(($cache ?: new NullCache()))
            ->setCulture($culture)
            ->setUsername($username)
            ->setPassword($password)
            ->setVersion($version);
    }

    /**
     * {@inheritdoc}
     */
    public function callRealtyList(array $params = [])
    {
        $params['showDetails'] = 1;

        return $this->call('objekt/list', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callRealtyIds(array $params = [])
    {
        return $this->call('objekt/ids', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callRealtyDetail($pk, array $params = [])
    {
        $params['objekt_id'] = $pk;

        return $this->call('objekt/detail', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callEmployeeDetail($pk)
    {
        return $this->call('team/detail', ['id' => $pk]);
    }

    /**
     * {@inheritdoc}
     */
    public function callProjectDetail($pk, array $params = [])
    {
        $params['id'] = $pk;

        return $this->call('projekt/detail', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callEmployeeList(array $params = [])
    {
        return $this->call('team/list', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callProjectList(array $params = [])
    {
        return $this->call('projekt/list', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callCountries(array $params = [])
    {
        return $this->call('objekt/laender', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callFederalStates(array $params = [])
    {
        return $this->call('objekt/bundeslaender', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callZipCodes(array $params = [])
    {
        return $this->call('objekt/plzsUndOrte', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callRegions(array $params = [])
    {
        return $this->call('objekt/regionen', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callRealtyTypes(array $params = [])
    {
        return $this->call('objekt/objektarten', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callRealtyCategories(array $params = [])
    {
        return $this->call('objekt/kategorien', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callTenant(array $params = [])
    {
        return $this->call('main/tenant', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callExpose($pk, $type = 'Default')
    {
        return $this->call('objekt/expose', ['objekt_id' => $pk, 'expose' => $type]);
    }

    /**
     * {@inheritdoc}
     */
    public function postRealtyInquiry(array $params = [])
    {
        return $this->call('objekt/anfrage', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callEmployeeIds(array $params = [])
    {
        return $this->call('team/ids', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function callProjectIds(array $params = [])
    {
        return $this->call('projekt/ids', $params);
    }

    /**
     * generates a url for an api request
     *
     *
     * @return string
     */
    public function generateUrl($call, array $params = [])
    {
        $url = $this->baseUrl.'/'.$this->version.'/'.$call;
        if (count($params) > 0) {
            $queryString = http_build_query($params, '', '&');
            $queryString = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $queryString);
            $url .= '?'.$queryString;
        }

        return $url;
    }

    /**
     * Makes a call to the justimmo api
     *
     *
     * @return mixed
     */
    public function call($call, array $params = [])
    {
        $startTime = microtime(true);

        if (! array_key_exists('culture', $params)) {
            $params['culture'] = $this->culture;
        }

        $url = $this->generateUrl($call, $params);
        $this->logger->debug('call start', [
            'url' => $url,
        ]);

        $key = $this->cache->generateCacheKey($url);
        $content = $this->cache->get($key);
        if ($content !== false) {
            $this->logger->debug('call end', [
                'url' => $url,
                'cache' => true,
                'time' => microtime(true) - $startTime,
                'response' => $content,
            ]);

            return $content;
        }

        $request = $this->createRequest($url);

        if (! ini_get('open_basedir') && filter_var(ini_get('safe_mode'), FILTER_VALIDATE_BOOLEAN) === false) {
            $request->setOption(CURLOPT_FOLLOWLOCATION, true);
        }

        $response = $request->get();

        if ($request->getError()) {
            $this->throwError('The Api call returned an error: "'.$request->getError().'"');
        }

        if ($request->getStatusCode() == 401) {
            $this->throwError('Bad Username / Password '.$request->getStatusCode(), '\Justimmo\Exception\AuthenticationException');
        }

        if ($request->getStatusCode() == 404) {
            $this->throwError('Api call not found: '.$request->getStatusCode(), '\Justimmo\Exception\NotFoundException');
        }

        if ($request->getStatusCode() >= 400 && $request->getStatusCode() < 500) {
            $exception = new InvalidRequestException('The Api call returned status code '.$request->getStatusCode());
            $exception->setResponse($request->getContent());
            $this->logger->error($exception->getMessage());

            throw $exception;
        }

        if ($request->getStatusCode() != 200) {
            $this->throwError('The Api call returned status code '.$request->getStatusCode(), '\Justimmo\Exception\StatusCodeException');
        }

        $this->cache->set($key, $response);

        $this->logger->debug('call end', [
            'url' => $url,
            'cache' => false,
            'time' => microtime(true) - $startTime,
            'response' => $response,
        ]);

        return $response;
    }

    /**
     * throws and logs an error
     *
     * @param  string  $exceptionClass
     *
     * @throws \InvalidArgumentException
     */
    protected function throwError($message, $exceptionClass = '\InvalidArgumentException')
    {
        $this->logger->error($message);
        throw new $exceptionClass($message);
    }

    /**
     * @param  string  $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param  string  $password
     * @return $this
     */
    public function setPassword($password)
    {
        if (mb_strlen($password) == 0) {
            $this->throwError('Password not set');
        }

        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param  string  $username
     * @return $this
     */
    public function setUsername($username)
    {
        if (mb_strlen($username) == 0) {
            $this->throwError('Username not set');
        }

        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param  string  $version
     * @return $this
     */
    public function setVersion($version)
    {
        if (! in_array($version, $this->supportedVersions)) {
            $this->throwError('The version '.$version.' is not supported by this library');
        }

        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return $this
     */
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @param  string  $value
     * @return $this
     */
    public function setCulture($value)
    {
        $this->culture = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getCulture()
    {
        return $this->culture;
    }

    /**
     * @param  array  $curlOptions
     * @return $this
     */
    public function setCurlOptions($curlOptions)
    {
        $this->curlOptions = $curlOptions;

        return $this;
    }

    /**
     * sets a specific option for curl requests
     *
     *
     * @return $this
     */
    public function setCurlOption($key, $value)
    {
        $this->curlOptions[$key] = $value;

        return $this;
    }

    protected function createRequest($url)
    {
        return new CurlRequest($url, [
            CURLOPT_USERPWD => $this->username.':'.$this->password,
            CURLOPT_HTTPAUTH => CURLAUTH_ANY,
        ] + $this->curlOptions);
    }
}

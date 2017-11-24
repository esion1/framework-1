<?php
namespace Swoft\Swagger;

/**
 * The api data output action.
 */
class SwaggerApiAction
{
    /**
     * @var string|array|\Symfony\Component\Finder\Finder The directory(s) or filename(s).
     * If you configured the directory must be full path of the directory.
     */
    public $scanDir;
    /**
     * @var string api key, if configured will perform the authentication.
     */
    public $api_key;
    /**
     * @var string Query param to get api key.
     */
    public $apiKeyParam = 'api_key';
    /**
     * @var array The options passed to `Swagger`, Please refer the `Swagger\scan` function for more information.
     */
    public $scanOptions = [];
    /**
     * @var Cache|string|null the cache object or the ID of the cache application component that is used to store
     * Cache the \Swagger\Scan
     */
    public $cache = null;
    /**
     * @var string Cache key
     * [[cache]] must not be null
     */
    public $cacheKey = 'api-swagger-cache';

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->initCors();

        $request = App::getRequest();

        $requestApiKey = $request->getHeader($this->apiKeyParam, $request->getHeader($this->apiKeyParam));

        if (null !== $this->api_key
            && $this->api_key != $requestApiKey
        ) {
            return $this->getNeedAuthResponse();
        }

        $swagger = $this->getSwagger();

        return $swagger;
    }

    /**
     * Init cors.
     */
    protected function initCors()
    {
        $response = App::getResponse();
        $response->withHeader('Access-Control-Allow-Headers', 'Content-Type, api_key, Authorization');
        $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT');
        $response->withHeader('Access-Control-Allow-Origin', '*');
        $response->withHeader('Allow', 'OPTIONS,HEAD,GET');
    }

    /**
     * @return array
     */
    protected function getNeedAuthResponse()
    {
        return [
            'securityDefinitions' => [
                'api_key' => ['in' => 'header', 'type' => 'apiKey', 'name' => 'api_key'],
            ],
            'swagger' => '2.0',
            'schemes' => ['http'],
            'info' => [
                'title' => 'Please take authentication firstly.',
            ],
        ];
    }

    /**
     * Get swagger object
     *
     * @return \Swagger\Annotations\Swagger
     */
    protected function getSwagger()
    {
        return \Swagger\scan($this->scanDir, $this->scanOptions);
    }
}

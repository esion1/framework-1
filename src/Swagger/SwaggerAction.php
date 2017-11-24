<?php
namespace Swoft\Swagger;

/**
 * The document display action.
 */
class SwaggerAction
{
    use Swoft\Web\ViewRendererTrait;
    /**
     * @var string The rest url configuration.
     */
    public $restUrl;

    public function run()
    {
        return $this->renderPartial(__DIR__ . '/index.php', ['rest_url' => $this->restUrl]);
//         $result = App::getBean('renderer')->renderPartial(__DIR__ . '/index.php', ['rest_url' => $this->restUrl]);
        //         RequestContext::getResponse()->setResponseContent($result);
    }

}

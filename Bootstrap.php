<?php
/**
 * @author Ganjar
 * @link   http://sli.su/
 * @link   https://github.com/ganjar/sli
 */

namespace Sli;

use yii\base\BootstrapInterface;


/**
 * Class Bootstrap
 * @package SLI
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        \Yii::$app->on(\yii\base\Application::EVENT_BEFORE_REQUEST, function (\yii\base\Event $event) {

        });

        \Yii::$app->response->on(\yii\web\Response::EVENT_BEFORE_SEND, function (\yii\base\Event $event) {
            /** @var \yii\web\Response $response */
            $response = $event->sender;
            if ($response->format === \yii\web\Response::FORMAT_HTML) {
                if (!empty($response->data)) {
                    $response->data = 1;
                }
            }
        });
    }
}

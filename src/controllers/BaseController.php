<?php
namespace Lmr\AutoTranslator\controllers;

use Craft;
use craft\web\Controller;

use yii\web\Response;
use yii\web\ServerErrorHttpException;
use Lmr\AutoTranslator\Plugin;

class BaseController extends Controller
{
    public function actionIndex()
    {
        $url = "auto-translator/plugin-settings";

        return $this->redirect($url);
    }

    public function actionPlugin()
    {
        $config = Plugin::getInstance()->getSettings();
        $services = $config->services;

        $serviceSelect = array_combine(array_keys($services), array_map(function ($value) {
            return ucfirst($value);
        }, array_keys($services)));

        $languages = array_unique(array_map(function ($site) {
            return $site->language;
        }, Craft::$app->sites->getAllSites()));

        return $this->renderTemplate(
            'auto-translator/plugin-settings',
            [
                'settings' => $config,
                'serviceOptions' => $serviceSelect,
                'languageOptions' => array_combine($languages, $languages),
            ],
        );
    }
}

<?php
namespace Lmr\AutoTranslator\controllers;

use Craft;
use craft\web\Controller;

use Lmr\AutoTranslator\Plugin;

class BaseController extends Controller
{
    public function actionIndex()
    {
        $url = "auto-translator/content-settings";

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

    public function actionContent() {
        $config = Plugin::getInstance()->getSettings();
        $fieldInfo = Plugin::getInstance()->fieldService->getElementInfo();

        return $this->renderTemplate('auto-translator/content-settings', [
            'settings' => $config,
            'fieldInfo' => $fieldInfo,
        ]);
    }
}

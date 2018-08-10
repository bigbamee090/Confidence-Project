<?php
namespace app\components;

use dosamigos\ckeditor\CKEditor;
use iutbay\yii2kcfinder\KCFinder;
use iutbay\yii2kcfinder\KCFinderAsset;
use yii\helpers\ArrayHelper;

class TRichTextEditor extends CKEditor
{

    public $enableKCFinder = false;

    /**
     * Registers CKEditor plugin
     */
    protected function registerPlugin()
    {
        if ($this->enableKCFinder) {
            $this->registerKCFinder();
        }
        
        parent::registerPlugin();
    }

    /**
     * Registers KCFinder
     */
    protected function registerKCFinder()
    {
        $register = KCFinderAsset::register($this->view);
        $kcfinderUrl = $register->baseUrl;
        
        $browseOptions = [
            'filebrowserBrowseUrl' => $kcfinderUrl . '/browse.php?opener=ckeditor&type=files',
            'filebrowserUploadUrl' => $kcfinderUrl . '/upload.php?opener=ckeditor&type=files'
        ];
        
        $this->clientOptions = ArrayHelper::merge($browseOptions, $this->clientOptions);
        
        // kcfinder options
        // http://kcfinder.sunhater.com/install#dynamic
        $kcfOptions = array_merge(KCFinder::$kcfDefaultOptions, [
            'uploadURL' => \Yii::getAlias('@web') . "/files",
            'uploadDir' => \Yii::getAlias('@webroot') . "/protected/uploads",
            'access' => [
                'files' => [
                    'upload' => true,
                    'delete' => false,
                    'copy' => false,
                    'move' => false,
                    'rename' => false
                ],
                'dirs' => [
                    'create' => true,
                    'delete' => false,
                    'rename' => false
                ]
            ]
        ]);
        
        // Set kcfinder session options
        \Yii::$app->session->set('KCFINDER', $kcfOptions);
    }

    public $preset;
}
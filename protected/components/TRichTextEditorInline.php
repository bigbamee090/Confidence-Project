<?php
namespace app\components;

use dosamigos\ckeditor\CKEditorInline;
use yii\helpers\ArrayHelper;
use iutbay\yii2kcfinder\KCFinderAsset;
use iutbay\yii2kcfinder\KCFinder;

class TRichTextEditorInline extends CKEditorInline
{

    public $saveUrl = '';

    public $enableKCFinder = true;

    /**
     * Registers CKEditor plugin
     */
    protected function registerPlugin()
    {
        $clientOptions = [
            'preset' => 'full',
            'extraPlugins' => 'inlinesave',
            // 'filebrowserBrowseUrl' => $model->getUrl ( 'browse' ),
            // 'filebrowserUploadUrl' => $model->getUrl ( 'upload' ),
            'inlinesave' => [
                'postUrl' => $this->saveUrl,
                // 'postData' => '{test: true}',
                'onSave' => "function(editor) { console.log('clicked save', editor); return true; }",
                'onSuccess' => "function(editor, data) { console.log('save successful', editor, data); }",
                'onFailure' => "function(editor, status, request) { console.log('save failed', editor, status, request); }",
                'successMessage' => 'Yay we saved it!',
                'errorMessage' => 'Something went wrong ',
                'useJSON' => false,
                'useColorIcon' => true
            ]
        ];
        
        $this->clientOptions = ArrayHelper::merge($clientOptions, $this->clientOptions);
        
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
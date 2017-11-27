<?php

namespace app\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SiteController extends ActiveController
{

    public $modelClass = false;
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            /*'error' => [
                'class' => 'yii\web\ErrorAction',
            ]*/
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionError(){
        $this->layout = false;

        //Yii::$app->response->statusCode=404;

        $response=Yii::$app->response;

        //$response->format= Response::FORMAT_JSON;
        $response->statusCode = 404;
        //Yii::$app->getResponse()->setStatusCodeByException($this->findException());

        $response->data = [
            'code' => 404,
            'message'=> 'Not Found'
        ];
        //Yii::$app->response->setStatusCode(404);
        //echo "error 404";

        return $response;

    }

    /*protected function findException()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

        return $exception;
    }*/
}

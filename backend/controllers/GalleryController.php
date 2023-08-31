<?php


namespace backend\controllers;


use common\models\Gallery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
class GalleryController extends Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'=>['delete'],
                        'allow'=>true,
                        'roles'=>['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionDelete(){
        if (Yii::$app->request->isAjax) {
            if(!is_null(Yii::$app->request->post('key')))
            {
                $id = Yii::$app->request->post('key');
                $model = Gallery::findOne($id);
                $thumbPath = Yii::$app->basePath .
                    Yii::getAlias('@houseUploadPath') . '/' .
                    'thumb' . '/' . $model->image_web_filename;
                $normalPath = Yii::$app->basePath .
                    Yii::getAlias('@houseUploadPath') . '/' .
                    $model->image_web_filename;

                if (isset($thumbPath) && file_exists($thumbPath)) {
                    unlink($thumbPath);
                }
                if (isset($normalPath) && file_exists($normalPath)) {
                    unlink($normalPath);
                }
                $model->delete();
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            }
        }
    }
}
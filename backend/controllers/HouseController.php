<?php
namespace backend\controllers;

use common\models\Gallery;
use common\models\House;
use backend\models\HouseSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\UploadedFile;


class HouseController extends Controller
{

    public function behaviors()
    {
        return [
          'access'=>[
              'class'=>AccessControl::className(),
              'rules'=>[
                  [
                      'actions'=>['index','view'],
                      'allow'=>true,
                      'roles'=>['@']
                  ],
                  [
                      'actions'=>['create'],
                      'allow'=>true,
                      'roles'=>['createHouse']
                  ],
                  [
                      'actions'=>['delete'],
                      'allow'=>true,
                      'roles'=>['deleteHouse'],
                      'roleParams' => function() {
                          return ['post' =>
                              House::findOne(
                                  [
                                      'id' => Yii::$app->request->get('id')
                                  ])
                          ];
                      }
                  ],
                  [
                      'actions'=>['status'],
                      'allow'=>true,
                      'roles'=>['activeHouse'],
                      'roleParams' => function() {
                          return ['post' =>
                              House::findOne(
                                  [
                                      'id' => Yii::$app->request->get('id')
                                  ])
                          ];
                      }
                  ],
                  [
                      'actions'=>['update'],
                      'allow'=>true,
                      'roles' => ['updateHouse'],
                      'roleParams' => function() {
                          return ['post' =>
                              House::findOne(
                                  [
                                      'id' => Yii::$app->request->get('id')
                                  ])
                          ];
                      },
                  ],
              ]
          ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'status'=>  ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(){
     $searchModel = new HouseSearch();
     $dataProvider = $searchModel->search(Yii::$app->request->get());
        return $this->render('index',[
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new House();
        $model->lease_type = House::SELL_TYPE;
        if ($model->load(Yii::$app->request->post())) {
            $model->images = UploadedFile::getInstances($model, 'images');
            if ($model->saveModel($_POST['House']['features'])){
                return $this->redirect(['index']);
            }
        }
        return $this->render('form', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = House::findOne($id);
            if ($model->load(Yii::$app->request->post())) {
                $model->images = UploadedFile::getInstances($model, 'images');
                if ($model->saveModel($_POST['House']['features'])) {
                    return $this->redirect(['index']);
                }
            }
            return $this->render('form', ['model' => $model]);
    }

    public function actionStatus($id)
    {
        $model = House::findOne($id);
        if ($model->status == House::STATUS_ACTIVE) {
            $model->status = House::STATUS_INACTIVE;
        } else {
            $model->status = House::STATUS_ACTIVE;
        }
        $model->save();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    public function actionDelete($id){
        $galleryList = Gallery::find()
            ->where(['house_id'=>$id])
            ->all();
        if (isset($galleryList) && !empty($galleryList)){
            foreach ($galleryList as $gallery){
                $thumbPath =
                    Yii::$app->basePath
                    .Yii::getAlias('@houseUploadPath')
                    .'/'.'thumb'.'/'.$gallery->image_web_filename;
                if (isset($thumbPath) && file_exists($thumbPath) ){
                    unlink($thumbPath);
                }
                $normalPath =
                    Yii::$app->basePath
                    .Yii::getAlias('@houseUploadPath')
                    .'/'.$gallery->image_web_filename;
                if (isset($normalPath) && file_exists($normalPath) ){
                    unlink($normalPath);
                }
            }
        }
        $model = House::findOne($id);
        $model->delete();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }




}
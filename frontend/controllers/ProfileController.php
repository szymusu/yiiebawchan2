<?php

namespace frontend\controllers;

use Yii;
use common\models\Profile;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProfileController implements the CRUD actions for Profile model.
 */
class ProfileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'switch' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ]
        ];
    }

    /**
     * Lists all Profile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Profile::find()
                ->onlyMine()
                ->select(['link', 'name', 'description'])
                ->orderBy(['last_login' => SORT_DESC, 'name' => SORT_ASC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Profile model.
     * @param string $link
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($link)
    {
        $model = $this->findModelByLink($link);
        $view = $model->isMine() ? 'view_owner' : 'view_guest';

        return $this->render($view, [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Profile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new Profile();

        if ($model->load(Yii::$app->request->post()) && $model->saveNew()) {
            return $this->redirect(['view', 'link' => $model->link]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $link
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($link)
    {
        $model = $this->findModelByLink($link);
        if (!($model->isMine())) return $this->goBack();

        if ($model->load(Yii::$app->request->post()) && $model->linkChange($link) && $model->save()) {
	        return $this->redirect(['view', 'link' => $model->link]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Profile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $link
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($link)
    {
        $model = $this->findModelByLink($link);

        if ($model->isMine())
        {
            $model->delete();
        }

        return $this->redirect(['index']);
    }

	/**
	 * @param string $link
	 * @param string $group
	 * @return mixed
	 * @throws ForbiddenHttpException
	 * @throws NotFoundHttpException
	 */
    public function actionSwitch($link, $group = null)
    {
        Yii::$app->profile->switchTo($this->findModelByLink($link));
        if ($group === null)
        {
	        return $this->goBack();
        }
        return $this->redirect(Yii::$app->user->returnUrl);
//        return $this->goBack();
//        return $this->redirect(['/group/view', 'link' => $group]);
    }

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Profile::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $link
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelByLink($link)
    {
        if (($model = Profile::findByLink($link)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

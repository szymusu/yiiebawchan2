<?php

namespace frontend\controllers;

use common\models\Reaction;
use Yii;
use common\models\Post;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'react' => ['post'],
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
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Post::find()->orderBy(['created_at' => SORT_DESC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
	    $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        if (!(Yii::$app->profile->getIsLogged()))
        {
            throw new MethodNotAllowedHttpException('You must be using a profile to post!');
        }

        $model = new Post();

        if ($model->load(Yii::$app->request->post()) && $model->saveNew(Yii::$app->profile->get())) {
            return $this->redirect(['view', 'id' => $model->post_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id)
    {
	    $model = $this->findModel($id);
        if (!($model->isMine())) $this->goBack();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->post_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->isMine())
        {
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * @param $id string
     * @param $type int
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException if user has no access
     */
    public function actionReact($id, $type = 1)
    {
        $model = $this->findModel($id);
        if (!($model->canIAccess()))
        {
            throw new ForbiddenHttpException();
        }
        $reactionQuery = Reaction::find()->specific($id, $type, Yii::$app->profile->getId());
        if ($reactionQuery->exists())
        {
            $reactionQuery->one()->delete();
        }
        else
        {
            $reaction = new Reaction(['type' => $type, 'post_id' => $model->post_id, 'profile_id' => Yii::$app->profile->getId()]);
            $reaction->save();
        }
        return $this->renderAjax('_reaction_bar', ['model' => $model]);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

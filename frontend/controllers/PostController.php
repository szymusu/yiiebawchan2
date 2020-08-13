<?php

namespace frontend\controllers;

use common\exceptions\FileUploadException;
use common\models\Comment;
use common\models\File;
use common\models\Group;
use common\models\Reaction;
use Yii;
use common\models\Post;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

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
            ],
	        [
		        'class' => 'yii\filters\ContentNegotiator',
		        'only' => ['comment'],
		        'formats' => ['application/json' => Response::FORMAT_JSON]
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
            'query' => Post::find()->latest(),
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
     * @param string $group
     * @return mixed
     * @throws Exception
     */
    public function actionCreate($group)
    {
    	$groupModel = Group::findByLink($group);
        if (!($groupModel->isAllowedToPost(Yii::$app->profile->getId())))
        {
            throw new ForbiddenHttpException("You can't post here");
        }

        $model = new Post();

        if ($model->load(Yii::$app->request->post()) && $model->saveNew(Yii::$app->profile->get(), $groupModel))
        {
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
	 * @throws Exception
	 */
    public function actionEdit($id)
    {
	    $model = $this->findModel($id);

	    $file = UploadedFile::getInstanceByName('upload');
	    if ($file)
	    {
	    	$fileModel = new File();
	    	$fileModel->source_id = $model->post_id;
		    try
		    {
			    $fileModel->upload($file);
		    }
		    catch (FileUploadException $e)
		    {
			    Yii::$app->session->setFlash('fileUploadError', $e->getMessage());
			    return $this->render('update', [
				    'model' => $model,
			    ]);
		    }
	    }

        if (!($model->isMine()))
        {
	        return $this->goBack();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
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
	 * @return mixed
	 * @throws ForbiddenHttpException if user has no access
	 * @throws NotFoundHttpException if the model cannot be found
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
	 * @param $id string
	 * @return mixed
	 * @throws NotFoundHttpException
	 */
	public function actionGetReplyForm($id)
	{
		$comment = Comment::find()->andWhere(['original_comment_id' => $id]);
		if (!($comment->exists()))
		{
			throw new NotFoundHttpException('Original comment missing');
		}
		else
		{
			$comment = $comment->one();
			return $this->renderAjax('_comment_form', [
				'comment' => $comment,
				'model' => $comment->post,
			]);
		}
    }

	/**
	 * @param $id string
	 * @return mixed
	 * @throws ForbiddenHttpException if user has no access
	 * @throws NotFoundHttpException if the model cannot be found
	 * @throws Exception
	 */
	public function actionComment($id)
	{
		$post = $this->findModel($id);
		if (!($post->canIAccess()))
		{
			throw new ForbiddenHttpException();
		}
		$comment = new Comment();
		if ($comment->load(Yii::$app->request->post()) && $comment->saveNew($id, $comment->original_comment_id ?? false))
		{
			return $this->renderAjax('_comment_item', ['model' => $comment]);
		}
		else
		{
			return 'ERROR' . print_r($comment->errors);
		}
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
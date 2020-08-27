<?php

namespace frontend\controllers;

use common\models\GroupMember;
use common\models\MemberAcceptForm;
use common\models\Post;
use common\models\Profile;
use Error;
use Yii;
use common\models\Group;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroupController implements the CRUD actions for Group model.
 */
class GroupController extends Controller
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
        ];
    }

    /**
     * Lists all Group models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Group::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Group model.
     * @param string $link
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($link)
    {
    	$model = $this->findModelByLink($link);
    	if ($model->isAllowedMember(Yii::$app->profile->getId()))
	    {
		    $view = 'view_full';
		    $dataProvider = new ActiveDataProvider([
			    'query' => Post::find()->group($model->group_id)->latest(),
		    ]);
	    }
    	else
	    {
		    $view = 'view_limited';
		    $dataProvider = new ActiveDataProvider([
			    'query' => Profile::find()
				    ->innerJoin(GroupMember::tableName(), 'profile.profile_id = group_member.profile_id')
				    ->andWhere(['group_member.group_id' => $model->group_id])
		            ->andWhere('group_member.type >= 1')
			        ->onlyMine()
		    ]);
	    }


        return $this->render($view, [
            'model' => $model,
	        'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Group model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Group();

        if ($model->load(Yii::$app->request->post()) && $model->saveNew(Yii::$app->profile->get())) {


            return $this->redirect(['view', 'link' => $model->link]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

	/**
	 * Updates an existing Group model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $link
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 * @throws ForbiddenHttpException
	 */
    public function actionUpdate($link)
    {
        $model = $this->findModelByLink($link);
	    if (!($model->isAdmin(Yii::$app->profile->getId())))
	    {
		    throw new ForbiddenHttpException('You are not allowed here');
	    }

        if ($model->load(Yii::$app->request->post()) && $model->linkChange($link) && $model->save()) {
	        return $this->redirect(['view', 'link' => $model->link]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

	/**
	 * Deletes an existing Group model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 * @throws ForbiddenHttpException
	 */
    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
	    if (!($model->isOwner(Yii::$app->profile->getId())))
	    {
		    throw new ForbiddenHttpException('You are not allowed here');
	    }

	    $model->delete();
        return $this->redirect(['index']);
    }

	/**
	 * @param string $link
	 * @param bool|string $profileId
	 * @param bool $cancelRequest
	 * @return mixed
	 * @throws NotFoundHttpException
	 */
	public function actionJoin($link, $profileId = false, $cancelRequest = false)
	{
		$model = $this->findModelByLink($link);
		if (!$profileId)
		{
			$profileId = Yii::$app->profile->getId();
		}
		else
		{
			$profile = Profile::findById($profileId);
			if (!($profile->isMine()))
			{
				return $this->redirect(['view', 'link' => $link]);
			}
		}

		if ($cancelRequest)
		{
			try
			{
				GroupMember::find()->joinRequest($model->group_id, $profileId)->one()->delete();
			}
			catch (Error $e)
			{
				throw new NotFoundHttpException('Cannot cancel not existing request');
			}
		}
		else if (!($model->hasJoinRequest($profileId)) && !($model->isMember($profileId)))
		{
			$member = new GroupMember();
			$member->newJoinRequest($model->group_id, $profileId);
		}
		return $this->redirect(['view', 'link' => $link]);
    }

	/**
	 * @param string $link
	 * @return mixed
	 * @throws NotFoundHttpException
	 */
	public function actionLeave($link)
	{
        $model = $this->findModelByLink($link);
        $memberModel = GroupMember::find()->member($model->group_id, Yii::$app->profile->getId())->one();
        if ($memberModel && $memberModel->typeName() != 'banned')
        {
        	$memberModel->delete();
        }
        return $this->redirect(['/group/view', 'link' => $link]);
    }


	/**
	 * @param string $link
	 * @return mixed
	 * @throws NotFoundHttpException
	 * @throws ForbiddenHttpException
	 */
	public function actionPanel($link)
	{
		$model = $this->findModelByLink($link);
		if (!($model->isModerator(Yii::$app->profile->getId())))
		{
			throw new ForbiddenHttpException('You are not allowed here');
		}

		$dataProvider = new ActiveDataProvider([
			'query' => GroupMember::find()->joinRequest($model->group_id)
		]);
		return $this->render('panel', [
			'model' => $model,
			'dataProvider' => $dataProvider,
		]);
    }

	/**
	 * @param string $link
	 * @return mixed
	 * @throws NotFoundHttpException
	 * @throws ForbiddenHttpException
	 */
	public function actionAcceptMember($link)
	{
		$model = $this->findModelByLink($link);
		if (!($model->isModerator(Yii::$app->profile->getId())))
		{
			throw new ForbiddenHttpException('You are not allowed here');
		}

		$formModel = new MemberAcceptForm();
		if ($formModel->load(Yii::$app->request->post()))
		{
			if ($memberModel = GroupMember::findJoinRequest($model->group_id, $formModel->profile_id))
			{
				if ($formModel->type)
				{
					$memberModel->type = GroupMember::getTypeNumber('member');
					$memberModel->save();
				}
				else
				{
					$memberModel->delete();
				}
			}
		}
		return $this->redirect(['/group/panel', 'link' => $link]);
    }

    /**
     * Finds the Group model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Group the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Group::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

	/**
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $link
	 * @return Group the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModelByLink($link)
	{
		if (($model = Group::findOne(['link' => $link])) !== null) {
			return $model;
		}
		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
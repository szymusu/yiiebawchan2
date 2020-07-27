<?php

namespace common\models\query;

use common\models\Comment;
use common\models\Post;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\Comment]].
 *
 * @see \common\models\Comment
 */
class CommentQuery extends ActiveQuery
{
	use ActiveQueryX;

    /**
     * {@inheritdoc}
     * @return \common\models\Comment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Comment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

	/**
	 * @param $post Post
	 * @return CommentQuery
	 */
	public function onPost($post)
	{
		return $this->andWhere(['post_id' => $post->post_id]);
    }

	/**
	 * @param $value bool
	 * @return CommentQuery
	 */
	public function reply($value = true)
	{
		return $this->andWhere(['is_reply' => $value]);
    }

	/**
	 * @param $comment Comment
	 * @return CommentQuery
	 */
	public function replyTo($comment)
	{
		return $this->reply()->andWhere(['original_comment_id' => $comment->comment_id]);
    }
}

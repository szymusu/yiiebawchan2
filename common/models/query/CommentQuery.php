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
     * @return Comment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Comment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function onPost(Post $post): CommentQuery
    {
		return $this->andWhere(['post_id' => $post->post_id]);
    }

    public function reply(bool $value = true): CommentQuery
    {
		return $this->andWhere(['is_reply' => $value]);
    }

    public function replyTo(Comment $comment): CommentQuery
    {
		return $this->reply()->andWhere(['original_comment_id' => $comment->comment_id]);
    }
}

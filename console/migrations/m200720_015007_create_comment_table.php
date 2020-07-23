<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%profile}}`
 * - `{{%comment}}`
 */
class m200720_015007_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
	        'comment_id' => $this->string(16)->notNull(),
	        'post_id' => $this->string(16)->notNull(),
            'profile_id' => $this->string(16)->notNull(),
            'original_comment_id' => $this->string(16),
	        'content' => $this->text(),
	        'is_reply' => $this->boolean(),
        ]);

	    $this->addPrimaryKey('PK_comment_comment_id', '{{%comment}}', 'comment_id');

        // creates index for column `profile_id`
        $this->createIndex(
            '{{%idx-comment-profile_id}}',
            '{{%comment}}',
            'profile_id'
        );

        // add foreign key for table `{{%profile}}`
        $this->addForeignKey(
            '{{%fk-comment-profile_id}}',
            '{{%comment}}',
            'profile_id',
            '{{%profile}}',
            'profile_id',
            'CASCADE'
        );

	    // creates index for column `post_id`
	    $this->createIndex(
		    '{{%idx-comment-post_id}}',
		    '{{%comment}}',
		    'post_id'
	    );

	    // add foreign key for table `{{%post}}`
	    $this->addForeignKey(
		    '{{%fk-comment-post_id}}',
		    '{{%comment}}',
		    'post_id',
		    '{{%post}}',
		    'post_id',
		    'CASCADE'
	    );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%profile}}`
        $this->dropForeignKey(
            '{{%fk-comment-profile_id}}',
            '{{%comment}}'
        );

        // drops index for column `profile_id`
        $this->dropIndex(
            '{{%idx-comment-profile_id}}',
            '{{%comment}}'
        );

	    // drops foreign key for table `{{%post}}`
	    $this->dropForeignKey(
		    '{{%fk-comment-post_id}}',
		    '{{%comment}}'
	    );

	    // drops index for column `post_id`
	    $this->dropIndex(
		    '{{%idx-comment-post_id}}',
		    '{{%comment}}'
	    );

        $this->dropTable('{{%comment}}');
    }
}

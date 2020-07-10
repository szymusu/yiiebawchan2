<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%profile}}`
 */
class m200709_014114_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'post_id' => $this->string(16)->notNull(),
            'user_id' => $this->integer(11),
            'profile_id' => $this->string(16)->notNull(),
            'content' => $this->text(),
        ]);
        $this->addPrimaryKey('PK_post_post_id', '{{%post}}', 'post_id');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-post-user_id}}',
            '{{%post}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-post-user_id}}',
            '{{%post}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `profile_id`
        $this->createIndex(
            '{{%idx-post-profile_id}}',
            '{{%post}}',
            'profile_id'
        );

        // add foreign key for table `{{%profile}}`
        $this->addForeignKey(
            '{{%fk-post-profile_id}}',
            '{{%post}}',
            'profile_id',
            '{{%profile}}',
            'profile_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-post-user_id}}',
            '{{%post}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-post-user_id}}',
            '{{%post}}'
        );

        // drops foreign key for table `{{%profile}}`
        $this->dropForeignKey(
            '{{%fk-post-profile_id}}',
            '{{%post}}'
        );

        // drops index for column `profile_id`
        $this->dropIndex(
            '{{%idx-post-profile_id}}',
            '{{%post}}'
        );

        $this->dropTable('{{%post}}');
    }
}

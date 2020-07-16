<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reaction}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%post}}`
 * - `{{%profile}}`
 */
class m200712_001832_create_reaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reaction}}', [
            'reaction_id' => $this->primaryKey(),
            'post_id' => $this->string(16),
            'profile_id' => $this->string(16),
            'created_at' => $this->integer(11),
        ]);

        // creates index for column `post_id`
        $this->createIndex(
            '{{%idx-reaction-post_id}}',
            '{{%reaction}}',
            'post_id'
        );

        // add foreign key for table `{{%post}}`
        $this->addForeignKey(
            '{{%fk-reaction-post_id}}',
            '{{%reaction}}',
            'post_id',
            '{{%post}}',
            'post_id',
            'CASCADE'
        );

        // creates index for column `profile_id`
        $this->createIndex(
            '{{%idx-reaction-profile_id}}',
            '{{%reaction}}',
            'profile_id'
        );

        // add foreign key for table `{{%profile}}`
        $this->addForeignKey(
            '{{%fk-reaction-profile_id}}',
            '{{%reaction}}',
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
        // drops foreign key for table `{{%post}}`
        $this->dropForeignKey(
            '{{%fk-reaction-post_id}}',
            '{{%reaction}}'
        );

        // drops index for column `post_id`
        $this->dropIndex(
            '{{%idx-reaction-post_id}}',
            '{{%reaction}}'
        );

        // drops foreign key for table `{{%profile}}`
        $this->dropForeignKey(
            '{{%fk-reaction-profile_id}}',
            '{{%reaction}}'
        );

        // drops index for column `profile_id`
        $this->dropIndex(
            '{{%idx-reaction-profile_id}}',
            '{{%reaction}}'
        );

        $this->dropTable('{{%reaction}}');
    }
}

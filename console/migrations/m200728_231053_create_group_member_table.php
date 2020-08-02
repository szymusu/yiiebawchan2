<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group_member}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%group}}`
 * - `{{%profile}}`
 */
class m200728_231053_create_group_member_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%group_member}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->string(16),
            'profile_id' => $this->string(16),
            'type' => $this->integer(11),
	        'created_at' => $this->integer(11),
        ]);

        // creates index for column `group_id`
        $this->createIndex(
            '{{%idx-group_member-group_id}}',
            '{{%group_member}}',
            'group_id'
        );

        // add foreign key for table `{{%group}}`
        $this->addForeignKey(
            '{{%fk-group_member-group_id}}',
            '{{%group_member}}',
            'group_id',
            '{{%group}}',
            'group_id',
            'CASCADE'
        );

        // creates index for column `profile_id`
        $this->createIndex(
            '{{%idx-group_member-profile_id}}',
            '{{%group_member}}',
            'profile_id'
        );

        // add foreign key for table `{{%profile}}`
        $this->addForeignKey(
            '{{%fk-group_member-profile_id}}',
            '{{%group_member}}',
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
        // drops foreign key for table `{{%group}}`
        $this->dropForeignKey(
            '{{%fk-group_member-group_id}}',
            '{{%group_member}}'
        );

        // drops index for column `group_id`
        $this->dropIndex(
            '{{%idx-group_member-group_id}}',
            '{{%group_member}}'
        );

        // drops foreign key for table `{{%profile}}`
        $this->dropForeignKey(
            '{{%fk-group_member-profile_id}}',
            '{{%group_member}}'
        );

        // drops index for column `profile_id`
        $this->dropIndex(
            '{{%idx-group_member-profile_id}}',
            '{{%group_member}}'
        );

        $this->dropTable('{{%group_member}}');
    }
}

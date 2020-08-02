<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%post}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%group}}`
 */
class m200729_164310_add_group_id_column_to_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%post}}', 'group_id', $this->string(16));

        // creates index for column `group_id`
        $this->createIndex(
            '{{%idx-post-group_id}}',
            '{{%post}}',
            'group_id'
        );

        // add foreign key for table `{{%group}}`
        $this->addForeignKey(
            '{{%fk-post-group_id}}',
            '{{%post}}',
            'group_id',
            '{{%group}}',
            'group_id',
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
            '{{%fk-post-group_id}}',
            '{{%post}}'
        );

        // drops index for column `group_id`
        $this->dropIndex(
            '{{%idx-post-group_id}}',
            '{{%post}}'
        );

        $this->dropColumn('{{%post}}', 'group_id');
    }
}

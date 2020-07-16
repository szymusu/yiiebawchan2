<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%reaction}}`.
 */
class m200715_231416_add_type_column_to_reaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%reaction}}', 'type', $this->integer(11)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%reaction}}', 'type');
    }
}

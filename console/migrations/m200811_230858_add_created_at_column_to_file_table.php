<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%file}}`.
 */
class m200811_230858_add_created_at_column_to_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%file}}', 'created_at', $this->integer(11));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%file}}', 'created_at');
    }
}

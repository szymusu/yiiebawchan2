<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%unique_id}}`.
 */
class m200807_162507_create_unique_id_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%unique_id}}', [
            'id' => $this->string(16)
        ]);
		$this->addPrimaryKey('PK_unique_id_id', '{{%unique_id}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%unique_id}}');
    }
}

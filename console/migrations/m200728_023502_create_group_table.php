<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group}}`.
 */
class m200728_023502_create_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%group}}', [
            'group_id' => $this->string(16)->notNull(),
            'link' => $this->string(32)->unique(),
	        'name' => $this->string(64)->notNull(),
	        'description' => $this->text(),
	        'type' => $this->integer(11),
	        'created_at' => $this->integer(11),
        ]);
	    $this->addPrimaryKey('PK_profile_group_id', '{{%group}}', 'group_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%group}}');
    }
}

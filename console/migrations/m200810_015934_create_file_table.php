<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%file}}`.
 */
class m200810_015934_create_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%file}}', [
	        'id' => $this->primaryKey(),
            'extension' => $this->string(8)->notNull(),
            'md5' => $this->string(32)->notNull(),
            'source_id' => $this->string(16)->notNull(),
            'type' => $this->integer(11),
        ]);

	    // creates index for column `source_id`
	    $this->createIndex(
		    '{{%idx-file-source_id}}',
		    '{{%file}}',
		    'source_id'
	    );

	    // add foreign key for table `{{%unique_id}}`
	    $this->addForeignKey(
		    '{{%fk-file-source_id}}',
		    '{{%file}}',
		    'source_id',
		    '{{%unique_id}}',
		    'source_id',
		    'CASCADE'
	    );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    // drops foreign key for table `{{%unique_id}}`
	    $this->dropForeignKey(
		    '{{%fk-file-source_id}}',
		    '{{%file}}'
	    );

	    // drops index for column `source_id`
	    $this->dropIndex(
		    '{{%idx-file-source_id}}',
		    '{{%file}}'
	    );

        $this->dropTable('{{%file}}');
    }
}

<?php

use think\migration\Migrator;

class AddIsReadToChatServiceDialogueRecord extends Migrator
{
    public function change()
    {
        if (!$this->hasTable('chat_service_dialogue_record')) return;
        $table = $this->table('chat_service_dialogue_record');
        if (!$table->hasColumn('is_read')) {
            $table->addColumn('is_read', 'boolean', [
                'limit' => 1,
                'default' => 0,
                'comment' => '是否已读 0=未读 1=已读'
            ])->addIndex(['to_user_id', 'user_id', 'is_read']);
            $table->update();
        }
    }
}

<?php
namespace FreePBX\modules\Motif;
use FreePBX\modules\Backup as Base;
class Restore Extends Base\RestoreBase{
  public function runRestore($jobid){
    $configs = $this->getConfigs();
    $fields = ['id', 'authmode', 'phonenum', 'username', 'password', 'settings', 'statusmessage', 'priority', 'refresh_token', 'oauth_secret', 'oauth_clientid'];
    foreach($configs as $motif){
        if(isset($motif['phone'])){
            $motif['phonenum'] = $motif['phone'];
            unset($motif['phone']);
        }
        $insert = [];
        foreach($fields as $field){
            if(isset($motif[$field])){
                $insert[$field] = $motif[$field];
                continue;
            }
            $insert[$field] = '';
        }
        $this->FreePBX->Motif->upsert($insert);
    }
  }
}
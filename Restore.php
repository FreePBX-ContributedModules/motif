<?php
namespace FreePBX\modules\Motif;
use FreePBX\modules\Backup as Base;
class Restore Extends Base\RestoreBase{
  public function runRestore($jobid){
    $configs = $this->getConfigs();
    foreach($configs as $motif){
        $this->FreePBX->Motif->upsert($motif['id'], $motif['phonenum'], $motif['username'], $motif['password'], $motif['settings'], $motif['statusmessage'], $motif['priority']);
    }
  }
}
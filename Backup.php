<?php
namespace FreePBX\modules\Motif;
use FreePBX\modules\Backup as Base;
class Backup Extends Base\BackupBase{
  public function runBackup($id,$transaction){
    $files = [];
    $dirs = [];
    $configs = $this->FreePBX->Motif->getAllAccounts();
    $this->addDependency('sipsettings');
    $this->addConfigs($configs);
  }
}
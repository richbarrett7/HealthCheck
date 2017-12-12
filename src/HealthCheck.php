<?PHP

namespace richbarrett\HealthCheck;

class HealthCheck {
    
  /* Disk Space /*/
  
  public function diskSpacePercentageUsed() {
    
    extract((array) $this->diskSpaceInfoBytes());  
    return round(($used / $total) * 100, 1);
    
  }
  
  public function diskSpaceFree($unit = 'GB') {
    
    $i = $this->diskSpaceInfoBytes();
    $u = $this->bytesAsOtherUnits($i->free);
    return $u->$unit;
    
  }
  
  public function diskSpaceTotal($unit = 'GB') {
    
    $i = $this->diskSpaceInfoBytes();
    $u = $this->bytesAsOtherUnits($i->total);
    return $u->$unit;
    
  }
  
  public function diskSpaceUsed($unit = 'GB') {
    
    $i = $this->diskSpaceInfoBytes();
    $u = $this->bytesAsOtherUnits($i->used);
    return $u->$unit;
    
  }
  
  private function diskSpaceInfoBytes() {
    
    $r['total']  = disk_total_space('.');
    $r['free']   = disk_free_space('.');
    $r['used']   = $r['total'] - $r['free'];
    
    return (object) $r;
    
  }
  
  
  /* Memory */
  
  public function memoryInfo() {

    $free = shell_exec('free');
    
    if(!$free) {
      throw new \Exception('Memory info not available on mac');
    }
    
  	$free = (string)trim($free);
  	$free_arr = explode("\n", $free);
  	$mem = explode(" ", $free_arr[1]);
  	$mem = array_filter($mem);
  	$mem = array_merge($mem);
  	
  	$r['total'] = $mem[1] * 1024;
  	$r['used'] = $mem[2] * 1024;
  	$r['free'] = $mem[3] * 1024;
  	$r['shared'] = $mem[4] * 1024;
  	$r['buffers'] = $mem[5] * 1024;
  	$r['cached'] = $mem[6] * 1024;
  	
  	return (object) $r;
  	
  }
  
  public function totalMemory($unit = 'MB') {
    
    $i = $this->memoryInfo();    
    $ret = $this->bytesAsOtherUnits($i->total);
    return $ret->$unit;
    
  }
  
  public function memoryPhysicallyFree($unit = 'MB') {
    
    $i = $this->memoryInfo();    
    $r = $i->total - ($i->used - $i->buffers - $i->cached);
    $ret = $this->bytesAsOtherUnits($r);
    return $ret->$unit;

  }
  
  public function memoryPhysicallyUsed($unit = 'MB') {
    
    $i = $this->memoryInfo();    
    $r = $i->used - $i->buffers - $i->cached;
    $ret = $this->bytesAsOtherUnits($r);
    return $ret->$unit;
    
  }
  
  public function memoryUsedPercentage() {
    
    $total = $this->totalMemory();
    $used = $this->memoryPhysicallyUsed();
    $perc = ($used->bytes / $total->bytes) * 100;
    return round($perc, 2);
    
  }
  
  
  /* CPU */
  
  public function loadAverages() {
    
    $avg = sys_getloadavg();
    
    $r[1] = round($avg[0],3);
    $r[5] = round($avg[1], 3);
    $r[15] = round($avg[2], 3);
    
    return $r;
    
  }
  
  
  /* Helper functions */
  
  private function bytesAsOtherUnits($bytes, $precision = 1) {
    
    $bytes = ceil($bytes);
    
    $r['B'] = $bytes;
    $r['KB'] = round($bytes/pow(1024,1), $precision, PHP_ROUND_HALF_UP);
    $r['MB'] = round($bytes/pow(1024,2), $precision, PHP_ROUND_HALF_UP);
    $r['GB'] = round($bytes/pow(1024,3), $precision, PHP_ROUND_HALF_UP);
    $r['TB'] = round($bytes/pow(1024,4), $precision, PHP_ROUND_HALF_UP);
    
    return (object) $r;    
    
  }
  
}

?>
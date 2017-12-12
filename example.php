<?PHP

include_once('src/HealthCheck.php');

$hc = new \richbarrett\HealthCheck\HealthCheck;

echo 'Disk currently '. $hc->diskSpacePercentageUsed().'% full ('.$hc->diskSpaceFree().'gb available)';

?>
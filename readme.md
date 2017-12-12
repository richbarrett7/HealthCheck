# Introduction
This is a very lightweight tool for checking some server health metrics (designed to work with Ubuntu).

# Methods available
- diskSpacePercentageUsed
- diskSpaceFree
- diskSpaceTotal
- diskSpaceUsed
- memoryInfo
- totalMemory
- memoryPhysicallyFree
- memoryPhysicallyUsed
- memoryUsedPercentage
- loadAverages

# Installation
You can install this with composer:
`composer require richbarrett/HealthCheck`

# Usage
```php
include_once('vendor/autoload.php');
$hc = new \richbarrett\HealthCheck\HealthCheck;
echo 'Disk currently '. $hc->diskSpacePercentageUsed().'% full ('.$hc->diskSpaceFree().'gb available)';
```
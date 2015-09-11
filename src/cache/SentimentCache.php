<?php

namespace BenPoulson\Sentiment\Cache;

use Camspiers\StatisticalClassifier\DataSource\DataArray;

abstract class SentimentCache extends DataArray {
    public abstract function read();
    public abstract function write();
}

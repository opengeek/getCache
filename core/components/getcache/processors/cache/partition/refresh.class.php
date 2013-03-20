<?php
class CachePartitionRefreshProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('empty_cache');
    }

    public function process() {
        $partitions = $this->getProperty('partitions', false);
        if (empty($partitions)) {
            return $this->failure('No partitions specified to refresh');
        }
        if (is_string($partitions)) $partitions = explode(',', $partitions);
        $providers = array();
        foreach ($partitions as $partition) {
            $providers[$partition] = array();
        }
        $results = array();
        $this->modx->getCacheManager()->refresh($providers, $results);

        sleep(1);

        foreach ($results as $partKey => $partResults) {
            if (is_bool($partResults)) {
                $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('refreshing_partition', array('partition' => $partKey)) . $this->modx->lexicon('refresh_' . ($partResults ? 'success' : 'failure')));
            } elseif (is_array($partResults)) {
                $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('refreshing_partition', array('partition' => $partKey)) . print_r($partResults, true));
            }
        }

        $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');
        return $this->success('');
    }
}
return 'CachePartitionRefreshProcessor';

<?php

namespace Overdose\Elastic\Model\Elasticsearch\SearchAdapter;

use Magento\Elasticsearch7\SearchAdapter\Mapper as OriginalBuilder;
use Magento\Framework\Search\RequestInterface;


class Mapper
{

    /**
     * Add source from ES to results
     *
     * @param OriginalBuilder $subject
     * @param $result
     * @param RequestInterface $request
     * @return mixed
     */
    public function afterBuildQuery(OriginalBuilder $subject, $result, RequestInterface $request)
    {
        if (strpos($result['index'], 'cms_pages_index') !== false) {
            $result['body']['stored_fields'][] = '_source';
        }

        return $result;
    }
}

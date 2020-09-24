<?php

namespace Overdose\Elastic\Model\Elasticsearch;

use Magento\Elasticsearch\SearchAdapter\DocumentFactory as DocumentFactoryOriginal;
use Magento\Framework\Api\Search\Document;

class DocumentFactory
{

    /**
     * Add source from ES to results
     *
     * @param DocumentFactoryOriginal $subject
     * @param Document $result
     * @param $rawDocument
     * @return Document
     */
    public function afterCreate(DocumentFactoryOriginal $subject, Document $result, $rawDocument)
    {
        if (strpos($rawDocument['_index'], 'cms_pages_index') !== false && isset($rawDocument['_source'])) {
            $result->setCustomAttribute('source', $rawDocument['_source']);
        }

        return $result;
    }
}

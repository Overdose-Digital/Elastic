<?php

namespace Overdose\Elastic\Block\CatalogSearch;

use Magento\Framework\View\Element\Template;

class CmsPage extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Overdose\Elastic\Model\Search
     */
    private $search;

    public function __construct(
        \Overdose\Elastic\Model\Search $search,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->search = $search;
    }

    public function getCollection()
    {
        $query = $this->getRequest()->getParam(\Magento\Search\Model\QueryFactory::QUERY_VAR_NAME);
        return $this->search->search($query);
    }
}


<?php

namespace Overdose\Elastic\Model;

use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection\SearchCriteriaResolverInterface;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection\SearchCriteriaResolverFactory;

class Search
{
    /**
     * @var \Magento\Framework\Api\Search\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\CollectionFactory
     */
    private $cmsCollectionFactory;
    /**
     * @var SearchCriteriaResolverFactory
     */
    private $searchCriteriaResolverFactory;
    /**
     * @var \Magento\Search\Api\SearchInterface
     */
    private $search;
    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var array
     */
    protected $searchResult;

    /**
     * Search constructor.
     * @param \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SearchCriteriaResolverFactory $searchCriteriaResolverFactory
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Search\Api\SearchInterface $search
     * @param \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $cmsCollectionFactory
     */
    public function __construct(
        \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder,
        SearchCriteriaResolverFactory $searchCriteriaResolverFactory,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Search\Api\SearchInterface $search,
        \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $cmsCollectionFactory
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->cmsCollectionFactory = $cmsCollectionFactory;
        $this->searchCriteriaResolverFactory = $searchCriteriaResolverFactory;
        $this->search = $search;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Retrieve search result from elastic
     * IMPORTANT: it works only with ES engine and may create issues with MySQL

     *
     * @param $query
     * @return \Magento\Framework\Api\Search\DocumentInterface[]
     */
    public function search($query)
    {
        $searchCriteria = $this->getSearchCriteriaResolver($query)->resolve();
        $this->searchResult =  $this->search->search($searchCriteria);

        return $this->searchResult->getItems();
    }

    /**
     * @param $query
     * @return SearchCriteriaResolverInterface
     */
    private function getSearchCriteriaResolver($query): SearchCriteriaResolverInterface
    {
        $this->filterBuilder->setField('search_term');
        $this->filterBuilder->setValue($query);
        $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());

        return $this->searchCriteriaResolverFactory->create(
            [
                'builder' => $this->searchCriteriaBuilder,
                'collection' => $this->cmsCollectionFactory->create(),
                'searchRequestName' => 'cms_search_container',
            ]
        );
    }
}

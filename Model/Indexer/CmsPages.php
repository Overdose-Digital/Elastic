<?php
namespace Overdose\Elastic\Model\Indexer;

use Magento\Elasticsearch\Model\Adapter\Elasticsearch as ElasticsearchAdapter;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Indexer\Model\ProcessManager;
use Magento\Store\Model\StoreDimensionProvider;

class CmsPages implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{

    /**
     * Indexer ID in configuration
     */
    const INDEXER_ID = 'cms_pages_index';

    /**
     * @var \Magento\Elasticsearch\Model\Indexer\IndexerHandler
     */
    private $indexerHandler;

    private $data;
    /**
     * @var DimensionProviderInterface
     */
    private $dimensionProvider;

    /**
     * @var ProcessManager
     */
    private $processManager;
    /**
     * @var ElasticsearchAdapter
     */
    private $adapter;
    /**
     * @var PageCollectionFactory
     */
    private $pageCollectionFactory;

    public function __construct(
        \Magento\Elasticsearch\Model\Indexer\IndexerHandlerFactory $indexerHandler,
        DimensionProviderInterface $dimensionProvider,
        \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $pageCollectionFactory,
        ElasticsearchAdapter $adapter,
        array $data,
        ProcessManager $processManager = null
    ) {
        $this->indexerHandler = $indexerHandler;
        $this->data = $data;
        $this->dimensionProvider = $dimensionProvider;
        $this->processManager = $processManager ?: ObjectManager::getInstance()->get(ProcessManager::class);

        $this->adapter = $adapter;
        $this->pageCollectionFactory = $pageCollectionFactory;
    }

    /*
     * Used by mview, allows process indexer in the "Update on schedule" mode
     */
    public function execute($ids)
    {
        foreach ($this->dimensionProvider->getIterator() as $dimension) {
            $this->executeByDimensions($dimension, new \ArrayIterator($ids));
        }
    }

    public function executeByDimensions(array $dimensions, \Traversable $entityIds = null)
    {
        if (null === $entityIds) {
            $data = $this->pageCollectionFactory->create()->getData();
            $this->adapter->cleanIndex(1, 'cms_pages_index');

            $this->adapter->addDocs(
                $data,
                1,
                'cms_pages_index'
            );

            $this->adapter->updateAlias(1, 'cms_pages_index');
        } else {
            // TODO: implement else :)
        }
    }

    /*
     * Will take all of the data and reindex
     * Will run when reindex via command line
     */
    public function executeFull()
    {
        $userFunctions = [];
        foreach ($this->dimensionProvider->getIterator() as $dimension) {
            $userFunctions[] = function () use ($dimension) {
                $this->executeByDimensions($dimension);
            };
        }
        $this->processManager->execute($userFunctions);
    }

    /*
     * Works with a set of entity changed (may be massaction)
     */
    public function executeList(array $ids)
    {
        //Works with a set of placed orders (mass actions and so on)
    }

    /*
     * Works in runtime for a single entity using plugins
     */
    public function executeRow($id)
    {
        $this->execute($id);
        //Works in runtime for a single order using plugins
    }
}

<?php

/**
 * A search service for the Search Framework library that only logs events.
 *
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt
 */

namespace Search\Server\Debug;

use Monolog\Logger;
use Search\Framework\Event\SearchCollectionEvent;
use Search\Framework\Event\SearchDocumentEvent;
use Search\Framework\Event\SearchFieldEvent;
use Search\Framework\SearchCollectionAbstract;
use Search\Framework\SearchEvents;
use Search\Framework\SearchServiceAbstract;
use Search\Framework\SearchIndexDocument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Integrates the Solarium library with the Search Framework.
 */
class DebugSearchServer extends SearchServiceAbstract implements EventSubscriberInterface
{
    /**
     * The Logger instance that logs the various events
     *
     * @var Logger
     */
    protected $_log;

    /**
     * Constructs a DebugSearchServer object.
     *
     * @param Logger $log
     *   The Logger instance that logs the various events.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Logger $log)
    {
        $this->_log = $log;
        $this->getDispatcher()->addSubscriber($this);
    }

    /**
     * Implements EventSubscriberInterface::getSubscribedEvents().
     */
    public static function getSubscribedEvents()
    {
        return array(
            SearchEvents::COLLECTION_PRE_INDEX => array('preIndexCollection'),
            SearchEvents::FIELD_ENRICH => array('fieldEnrich'),
            SearchEvents::FIELD_NORMALIZE => array('fieldNormalize'),
            SearchEvents::DOCUMENT_PRE_INDEX => array('preIndexDocument'),
            SearchEvents::DOCUMENT_POST_INDEX => array('postIndexDocument'),
            SearchEvents::COLLECTION_POST_INDEX => array('postIndexCollection'),
        );
    }

    /**
     * Logs a debug message.
     *
     * @see Logger::debug()
     */
    public function log($message, array $context = array())
    {
        $this->_log->debug($message, $context);
    }

    /**
     * Implements Search::Server::SearchServiceAbstract::createIndex().
     */
    public function createIndex($name, array $options = array())
    {
        $this->log('Create index operation executed: ' . $name);
    }

    /**
     * Listener for the SearchEvents::COLLECTION_PRE_INDEX event.
     *
     * @param SearchCollectionEvent $event
     */
    public function preIndexCollection(SearchCollectionEvent $event)
    {
        $event_name = SearchEvents::COLLECTION_PRE_INDEX;
    }

    /**
     * Listener for the SearchEvents::DOCUMENT_PRE_INDEX event.
     */
    public function preIndexDocument(SearchDocumentEvent $event)
    {
        $event_name = SearchEvents::DOCUMENT_PRE_INDEX;
        $this->log('Event thrown: ' . $event_name);
    }

    /**
     * Listener for the SearchEvents::FIELD_ENRICH event.
     */
    public function fieldEnrich(SearchFieldEvent $event)
    {
        $event_name = SearchEvents::FIELD_ENRICH;
        $this->log('Event thrown: ' . $event_name);
    }

    /**
     * Listener for the SearchEvents::FIELD_NORMALIZE event.
     */
    public function fieldNormalize(SearchFieldEvent $event)
    {
        $event_name = SearchEvents::FIELD_NORMALIZE;
        $this->log('Event thrown: ' . $event_name);
    }

    /**
     * Implements Search::Server::SearchServiceAbstract::indexDocument().
     */
    public function indexDocument(SearchCollectionAbstract $collection, SearchIndexDocument $document)
    {
        $this->log('The indexDocument() hook invoked for collection: ' . $collection::id());
    }

    /**
     * Listener for the SearchEvents::DOCUMENT_POST_INDEX event.
     */
    public function postIndexDocument(SearchDocumentEvent $event)
    {
        $event_name = SearchEvents::DOCUMENT_POST_INDEX;
        $this->log('Event thrown: ' . $event_name);
    }

    /**
     * Listener for the SearchEvents::COLLECTION_POST_INDEX event.
     *
     * @param SearchCollectionEvent $event
     */
    public function postIndexCollection(SearchCollectionEvent $event)
    {
        $event_name = SearchEvents::COLLECTION_POST_INDEX;
        $collection = $event->getCollection();
        $this->log("Event $event_name thrown for collection: " . $collection::id());
    }

    /**
     * Implements Search::Server::SearchServiceAbstract::search().
     *
     * @return array()
     */
    public function search($keywords, array $options = array())
    {
        $this->log('Search operation executed using keywords: ' . $keywords);
        return array();
    }

    /**
     * Implements Search::Server::SearchServiceAbstract::delete().
     */
    public function delete()
    {
        $this->log('Delete index operation executed.');
    }
}

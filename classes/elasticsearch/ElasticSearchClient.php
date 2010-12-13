<?php // vim:set ts=4 sw=4 et:

/*
Source: https://github.com/nervetattoo/elasticsearch
*/

require_once dirname(__FILE__) . '/lib/ElasticSearchException.php';
require_once dirname(__FILE__) . '/lib/ElasticSearchDSLStringify.php';

require_once dirname(__FILE__) . '/lib/builder/ElasticSearchDSLBuilder.php';

require_once dirname(__FILE__) . '/lib/transport/ElasticSearchTransport.php';
require_once dirname(__FILE__) . '/lib/transport/ElasticSearchTransportHTTP.php';
require_once dirname(__FILE__) . '/lib/transport/ElasticSearchTransportMemcached.php';

class ElasticSearchClient {

    private $transport, $index, $type;
    
    /**
     * Construct search client
     *
     * @return ElasticSearch
     * @param ElasticSearchTransport $transport
     * @param string $index
     * @param string $type
     */
    public function __construct($transport, $index, $type) {
        $this->index = $index;
        $this->type = $type;
        $this->transport = $transport;
        $this->transport->setIndex($index);
        $this->transport->setType($type);
    }
    
    /**
     * Change what index to go against
     * @return void
     * @param mixed $index
     */
    public function setIndex($index) {
        if (is_array($index))
            $index = implode(",", array_filter($index));
        $this->index = $index;
        $this->transport->setIndex($index);
    }
    
    /**
     * Change what types to act against
     * @return void
     * @param mixed $type
     */
    public function setType($type) {
        if (is_array($type))
            $type = implode(",", array_filter($type));
        $this->type = $type;
        $this->transport->setType($type);
    }
    
    /**
     * Fetch a document by its id
     *
     * @return array
     * @param mixed $id Optional
     */
    public function get($id, $verbose=false) {
        $response = $this->transport->request(array($this->type, $id), "GET");
        return ($verbose)
            ? $response
            : $response['_source'];
    }
    
    /**
     * Perform a request
     *
     * @return array
     * @param mixed $id Optional
     */
    public function request($path, $method, $payload, $verbose=false) {
        $path = array_merge((array) $this->type, (array) $path);

        $response = $this->transport->request($path, $method, $payload);
        return ($verbose)
            ? $response
            : $response['_source'];
    }

    /**
     * Index a new document or update it if existing
     *
     * @return array
     * @param array $document
     * @param mixed $id Optional
     */
    public function index($document, $id=false) {
        return $this->transport->index($document, $id);
    }

    /**
     * Perform search, this is the sweet spot
     *
     * @return array
     * @param array $document
     */
    public function search($query) {
        $start = $this->getMicroTime();
        $result = $this->transport->search($query);
        $result['time'] = $this->getMicroTime() - $start;
        return $result;
    }
    
    /**
     * Flush this index/type combination
     *
     * @return array
     * @param mixed $id If id is supplied, delete that id for this index
     *                  if not wipe the entire index
     */
    public function delete($id=false) {
        return $this->transport->delete($id);
    }
    
    /**
     * Flush this index/type combination
     *
     * @return array
     * @param mixed $query Text or array based query to delete everything that matches
     */
    public function deleteByQuery($query) {
        return $this->transport->deleteByQuery($query);
    }

    private function getMicroTime() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

}

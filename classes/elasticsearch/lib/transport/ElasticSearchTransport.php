<?php

abstract class ElasticSearchTransport {
    protected $index, $type;

    abstract public function index($document, $id=false);
    abstract public function request($path, $method="GET", $payload=false);
    abstract public function delete($id=false);
    abstract public function search($query);

    public function setIndex($index) {
        $this->index = $index;
    }
    public function setType($type) {
        $this->type = $type;
    }
}

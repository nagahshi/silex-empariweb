<?php

namespace EmpariWeb\Service;

abstract class Service implements ServiceInterface {

    protected $app;
    protected $em;
    protected $cache;

    public function setApp($app) {
        $this->app = $app;
    }

    public function setEm($em) {
        $this->em = $em;
    }

    public function setCache($cache) {
        $this->cache = $cache;
    }

}

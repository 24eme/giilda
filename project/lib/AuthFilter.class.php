<?php 

class AuthFilter extends sfFilter
{
    protected $filter = null;

    public function __construct($context, $parameters = array())
    {
        parent::__construct($context, $parameters);

        $this->filter = new acHttpAuth2ADFilter($context, array());
    }

    public function execute($filterChain)
    {

        return $this->filter->execute($filterChain);
    }
} 
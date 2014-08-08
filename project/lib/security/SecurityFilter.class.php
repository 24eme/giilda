<?php 

class SecurityFilter extends sfBasicSecurityFilter
{
    protected $filter = null;

    public function __construct($context, $parameters = array())
    {
        parent::__construct($context, $parameters);

        $auth = sfConfig::get("app_auth_mode");

        if($auth == "HTTP_AD") {
            $this->filter = new HttpAuth2ADSecurityFilter($context, array());
        } elseif($auth == "CAS") {
            $this->filter = new CASSecurityFilter($context, array());
        } else {
            $this->filter = new sfBasicSecurityFilter($context, array());
        }
    }

    public function execute($filterChain)
    {

        return $this->filter->execute($filterChain);
    }
} 
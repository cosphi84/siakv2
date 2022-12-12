<?php

defined('_JEXEC') or die;

class SiakRouter implements JComponentRouterInterface
{
    public function build(&$query)
    {
        $segments = [];

        unset($query['view']);

        return $segments;
    }

    public function parse(&$segments)
    {
        $vars = [];

        return $vars;
    }

    public function preprocess($query)
    {
        return $query;
    }
}

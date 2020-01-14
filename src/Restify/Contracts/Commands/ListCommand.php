<?php namespace ChicoRei\Packages\Restify\Contracts\Commands;

/**
 * Interface ListCommand
 *
 * @package ChicoRei\Packages\Restify\Contracts\Commands
 */
interface ListCommand
{
    /**
     * @return mixed
     */
    public function getPerPage();

    /**
     * @return mixed
     */
    public function getPage();
}

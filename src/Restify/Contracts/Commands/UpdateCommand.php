<?php namespace ChicoRei\Packages\Restify\Contracts\Commands;

/**
 * Interface UpdateCommand
 *
 * @package ChicoRei\Packages\Restify\Contracts\Commands
 */
interface UpdateCommand
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getAttributes();
}

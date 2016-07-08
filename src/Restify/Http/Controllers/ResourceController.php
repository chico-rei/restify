<?php namespace ChicoRei\Packages\Restify\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ChicoRei\Packages\Restify\Factories\CommandFactory;

/**
 * Created by PhpStorm.
 * User: bendia
 * Date: 4/26/15
 * Time: 8:59 PM
 */
class ResourceController extends BaseController
{
    /**
     * @return Response
     */
    public function index()
    {
        $command = CommandFactory::create($this->resourceClassName(), '', 'index');

        return $this->dispatch($command);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $rules = $this->validationRulesFactory->create($this->resourceClassName(), 'store');

        $this->validate($request, $rules);

        $command = CommandFactory::create($this->resourceClassName(), '', 'store');

        return $this->dispatch($command);
    }

    /**
     * @return Response
     */
    public function show()
    {
        $command = CommandFactory::create($this->resourceClassName(), '', 'show');

        return $this->dispatch($command);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $rules = $this->validationRulesFactory->create($this->resourceClassName(), 'update');

        $this->validate($request, $rules);

        $command = CommandFactory::create($this->resourceClassName(), '', 'update');

        return $this->dispatch($command);
    }

    /**
     * @return Response
     */
    public function destroy()
    {
        $command = CommandFactory::create($this->resourceClassName(), '', 'destroy');

        return $this->dispatch($command);
    }
}

<?php namespace ChicoRei\Packages\Restify\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ChicoRei\Packages\Restify\Factories\CommandFactory;

/**
 * Class NestedResourceController
 *
 * @package ChicoRei\Packages\Restify\Http\Controllers
 */
class NestedResourceController extends BaseController
{
    /**
     * @return Response
     */
    public function index()
    {
        $command = CommandFactory::create(
            $this->resourceClassName(),
            $this->nestedResourceClassName(),
            'index'
        );

        return $this->dispatch($command);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $rules = $this->validationRulesFactory->create(
            $this->resourceClassName() . $this->nestedResourceClassName(),
            'store'
        );

        $this->validate($request, $rules);

        $command = CommandFactory::create(
            $this->resourceClassName(),
            $this->nestedResourceClassName(),
            'store'
        );

        return $this->dispatch($command);
    }

    /**
     * @return Response
     */
    public function show()
    {
        $command = CommandFactory::create(
            $this->resourceClassName(),
            $this->nestedResourceClassName(),
            'show'
        );

        return $this->dispatch($command);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $rules = $this->validationRulesFactory->create(
            $this->resourceClassName() . $this->nestedResourceClassName(),
            'update'
        );

        $this->validate($request, $rules);

        $command = CommandFactory::create(
            $this->resourceClassName(),
            $this->nestedResourceClassName(),
            'update'
        );

        return $this->dispatch($command);
    }

    /**
     * @return Response
     */
    public function destroy()
    {
        $command = CommandFactory::create(
            $this->resourceClassName(),
            $this->nestedResourceClassName(),
            'destroy'
        );

        return $this->dispatch($command);
    }
}

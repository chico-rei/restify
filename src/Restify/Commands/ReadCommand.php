<?php namespace ChicoRei\Packages\Restify\Commands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ChicoRei\Packages\Restify\Contracts\Commands\ReadCommand as ReadCommandContract;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReadCommand extends BaseCommand implements ReadCommandContract
{
    /**
     * Handle the command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Model $modelClass */
        if (!$modelClass = $this->nestedModelClassName())
        {
            $modelClass = $this->modelClassName();
        }

        $id = $this->getNestedId() ?: $this->getId();

        try
        {
            return (new $modelClass)->newQueryWithoutScopes()->findOrFail($id);
        } catch (ModelNotFoundException $e)
        {
            throw new NotFoundHttpException(trans('restify.errors.not_found', [
                'model' => $this->nestedModelDisplayName() ?: $this->modelDisplayName()
            ]));
        }
    }
}

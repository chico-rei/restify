<?php namespace ChicoRei\Packages\Restify\Commands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ChicoRei\Packages\Restify\Contracts\Commands\ReadCommand as ReadCommandContract;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReadCommand
 *
 * @package ChicoRei\Packages\Restify\Commands
 */
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
            $modelInstance = (new $modelClass);

            if ($this->request->get('without_scopes', true)) {
                $modelInstance = $modelInstance->newQueryWithoutScopes();
            }

            if ($with = $this->request->get('with')) {
                $modelInstance = $modelInstance->with($with);
            }

            return $modelInstance->findOrFail($id);
        } catch (ModelNotFoundException $e)
        {
            throw new NotFoundHttpException(trans('restify.errors.not_found', [
                'model' => $this->nestedModelDisplayName() ?: $this->modelDisplayName()
            ]), $e);
        }
    }
}

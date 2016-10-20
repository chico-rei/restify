<?php namespace ChicoRei\Packages\Restify\Commands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ChicoRei\Packages\Restify\Contracts\Commands\UpdateCommand as UpdateCommandContract;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateCommand extends BaseCommand implements UpdateCommandContract
{
    /**
     * Handle the command.
     *
     * @return mixed
     */
    public function handle()
    {
        try
        {
            $modelClass = $this->modelClassName();
            $nestedRelationship = $this->nestedRelationshipName();
            $model = (new $modelClass)->newQueryWithoutScopes()->findOrFail($this->getId());

            /** @var Model $object */
            $object = $nestedRelationship ? $model->{$nestedRelationship}()->findOrFail($this->getNestedId()) : $model;
            $object->fill($this->getAttributes());
            $object->save();

            return $object;
        } catch (ModelNotFoundException $e)
        {
            throw new NotFoundHttpException(trans('restify.errors.not_found', [
                'model' => $this->nestedModelDisplayName() ?: $this->modelDisplayName()
            ]), $e);
        }
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->request->except('token');
    }
}

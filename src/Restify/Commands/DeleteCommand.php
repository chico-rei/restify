<?php namespace ChicoRei\Packages\Restify\Commands;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use ChicoRei\Packages\Restify\Contracts\Commands\DeleteCommand as DeleteCommandContract;
use ChicoRei\Packages\Restify\Exceptions\DeleteResourceException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteCommand extends BaseCommand implements DeleteCommandContract
{
    /**
     * Handle the command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modelClass = $this->modelClassName();
        $id = $this->getId();

        try
        {
            if ($nestedRelationship = $this->nestedRelationshipName())
            {
                $relationship = $modelClass::findOrFail($this->getId())->{$nestedRelationship}();

                /** @var Model $object */
                $object = $relationship->findOrFail($this->getNestedId());

                if ($relationship instanceof BelongsToMany) return $relationship->detach($object->{$object->getKeyName()});

                return $object->delete();
            }

            return (new $modelClass)->newQueryWithoutScopes()->findOrFail($this->getId())->delete();
        } catch (ModelNotFoundException $e)
        {
            throw new NotFoundHttpException(trans('restify.errors.not_found', [
                'model' => $this->nestedModelDisplayName() ?: $this->modelDisplayName(),
                'id' => $id
            ]), $e);
        } catch (Exception $e)
        {
            throw new DeleteResourceException(trans('restify.errors.delete', [
                'model' => $this->nestedModelDisplayName() ?: $this->modelDisplayName(),
                'id' => $id
            ]), null, $e);
        }
    }
}

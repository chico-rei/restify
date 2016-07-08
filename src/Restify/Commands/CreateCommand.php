<?php namespace Restify\Commands;

use Illuminate\Database\Eloquent\Model;
use Restify\Contracts\Commands\CreateCommand as CreateCommandContract;

class CreateCommand extends BaseCommand implements CreateCommandContract
{
    /**
     * Handle the command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Model $modelClass */
        $modelClass = $this->modelClassName();

        if ($nestedRelationship = $this->nestedRelationshipName())
        {
            return $modelClass::findOrFail($this->getId())
                ->{$nestedRelationship}()
                ->create($this->getAttributes());
        }

        return $modelClass::create($this->getAttributes());
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->request->except('token');
    }
}

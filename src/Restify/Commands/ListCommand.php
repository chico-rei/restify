<?php namespace ChicoRei\Packages\Restify\Commands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use ChicoRei\Packages\Restify\Contracts\Commands\ListCommand as ListCommandContract;

class ListCommand extends BaseCommand implements ListCommandContract
{
    /**
     * Handle the command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = $this->query();
        $columns = $this->getColumns();

        return $this->getPaginate()
            ? $query->paginate($this->getPerPage(), $columns)
            : $query->get($columns);
    }

    /**
     * Returns the query that will be executed by the command.
     *
     * @return Builder
     */
    public function query()
    {
        /** @var Model $modelClass */
        $modelClass = $this->modelClassName();

        if ($nestedRelationship = $this->nestedRelationshipName())
        {
            return $modelClass::findOrFail($this->getId())->{$nestedRelationship}();
        }

        /** @var Builder $query */
        $query = $modelClass::query();

        // Filter only fillable properties
        foreach ($this->getFilters() as $key => $value)
        {
            if (!$value) continue;

            // Treat embedded document filters for MongoDB, since PHP replaces '.' with '_' they should be sent with '__' separators.
            // http://ca.php.net/variables.external#language.variables.external.dot-in-names
            $key = str_replace('__', '.', $key);

            is_array($value) ? $query->whereIn($key, $value) : $query->where($key, $value);
        }

        // Apply available scopes
        foreach ($this->getScopes() as $json)
        {
            // Decode scopes
            $data = json_decode($json, true);

            $scopeName = isset($data['name']) ? $data['name'] : null;
            $value = isset($data['value']) ? $data['value'] : null;
            $values = isset($data['values']) ? $data['values'] : null;

            // Invalid scope name
            if (!$scopeName || $scopeName == '') continue;

            if ($value)
            {
                $query->$scopeName($value);
            }
            elseif ($values)
            {
                call_user_func_array([$query, $scopeName], $values);
            }
            else
            {
                $query->$scopeName();
            }
        }

        // Add order by
        if ($orderBy = $this->getOrderBy())
        {
            $orderBy = is_array($orderBy) ? $orderBy : [$orderBy];

            foreach ($orderBy as $column)
            {
                $desc = $column[0] == '-';
                $direction = $desc ? 'desc' : 'asc';
                $column = $desc ? ltrim($column, '-') : $column;

                $query->orderBy($column, $direction);
            }
        }

        return $query;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->request->get('perPage');
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->request->get('page');
    }

    /**
     * @return bool
     */
    public function getPaginate()
    {
        return $this->request->get('paginate', true);
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        $modelClass = $this->modelClassName();
        $filterable = $modelClass::getModel()->getFillable();

        return $this->request->only($filterable, []);
    }

    /**
     * @return array
     */
    public function getScopes()
    {
        return $this->request->get('scopes', []);
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->request->get('orderBy', []);
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->request->get('columns', ['*']);
    }
}

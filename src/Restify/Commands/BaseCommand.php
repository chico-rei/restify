<?php namespace ChicoRei\Packages\Restify\Commands;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class BaseCommand
 *
 * @package ChicoRei\Packages\Restify\Commands
 */
class BaseCommand
{
    /**
     * @var string
     */
    protected $pluralizedModels;

    /**
     * @var string
     */
    protected $pluralizedRoutes;

    /**
     * @var string
     */
    protected $modelPath;

    /**
     * @var string
     */
    protected $modelName;

    /**
     * @var string
     */
    protected $nestedModelName;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param string $modelName
     * @param string $nestedModelName
     */
    public function __construct($modelName, $nestedModelName = null)
    {
        $this->pluralizedModels = config('restify.paths.pluralized_models');
        $this->pluralizedRoutes = config('restify.paths.pluralized_routes');
        $this->modelPath = config('restify.paths.models');

        $this->modelName = $modelName;
        $this->nestedModelName = $nestedModelName;
        $this->request = request();
    }

    /**
     * @return string Model display name.
     */
    protected function modelDisplayName()
    {
        if (!$modelClassName = $this->modelClassName()) return null;

        return trans('restify.display_name.' . $modelClassName);
    }

    /**
     * @return string Nested model display name.
     */
    protected function nestedModelDisplayName()
    {
        if (!$nestedModelClassName = $this->nestedModelClassName()) return null;

        return trans('restify.display_name.' . $nestedModelClassName);
    }

    /**
     * Gets the respective model class name.
     *
     * @return mixed Fully-qualified name of the model class.
     */
    protected function modelClassName()
    {
        if (!$this->isValidModelName()) return null;

        return str_replace('{model}', $this->modelName, $this->modelPath);
    }

    /**
     * Gets the respective nested model class name.
     *
     * @return mixed Fully-qualified name of the nested model class.
     */
    protected function nestedModelClassName()
    {
        if (!$this->isValidNestedModelName()) return null;

        return str_replace('{model}', $this->nestedModelName, $this->modelPath);
    }

    /**
     * @return null|string Nested model relationship name.
     */
    protected function nestedRelationshipName()
    {
        if (!$this->isValidNestedModelName()) return null;

        $pluralized = $this->pluralizedModels ? Str::plural($this->nestedModelName) : $this->nestedModelName;

        return Str::camel($pluralized);
    }

    /**
     * @return null|string Nested model relationship name.
     */
    protected function nestedRelationshipColumn()
    {
        if (!$this->isValidNestedModelName()) return null;

        $singular = $this->pluralizedModels ? Str::singular($this->nestedModelName) : $this->nestedModelName;

        return Str::snake($singular) . '_id';
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        if (!$this->isValidModelName()) return null;

        $param = Str::snake($this->modelName);

        $value = $this->request->route($param);

        return $value ?: $this->request->route(Str::plural($param));
    }

    /**
     * @return mixed
     */
    public function getNestedId()
    {
        if (!$this->isValidNestedModelName()) return null;

        $param = Str::snake($this->nestedModelName);

        $value = $this->request->route($param);

        return $value ?: $this->request->route(Str::plural($param));
    }

    /**
     * @return bool Flag indicating if the model name is valid.
     */
    protected function isValidModelName()
    {
        return $this->modelName && $this->modelName != '';
    }

    /**
     * @return bool Flag indicating if the nested model name is valid.
     */
    protected function isValidNestedModelName()
    {
        return $this->nestedModelName && $this->nestedModelName != '';
    }
}

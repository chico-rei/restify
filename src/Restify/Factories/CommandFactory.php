<?php namespace ChicoRei\Packages\Restify\Factories;

use ChicoRei\Packages\Restify\Commands\BaseCommand;
use ChicoRei\Packages\Restify\Commands\CreateCommand;
use ChicoRei\Packages\Restify\Commands\DeleteCommand;
use ChicoRei\Packages\Restify\Commands\ListCommand;
use ChicoRei\Packages\Restify\Commands\ReadCommand;
use ChicoRei\Packages\Restify\Commands\UpdateCommand;

/**
 * Created by PhpStorm.
 * User: bendia
 * Date: 5/4/15
 * Time: 9:20 PM
 */
class CommandFactory
{
    /** @var array Map controller actions to command that should be executed */
    private static $actionCommandMapping = [
        'index' => 'List',
        'store' => 'Create',
        'show' => 'Read',
        'update' => 'Update',
        'destroy' => 'Delete',
    ];

    private static $restifyCommands = [
        CreateCommand::class,
        DeleteCommand::class,
        ListCommand::class,
        ReadCommand::class,
        UpdateCommand::class
    ];

    /**
     * Create a command to handle the given resource action.
     *
     * @param string $modelName
     * @param string $nestedResource
     * @param string $action
     * @return BaseCommand
     */
    public static function create($modelName, $nestedResource, $action)
    {
        $commandClass = static::commandClass($modelName, $nestedResource, $action);

        return in_array($commandClass, static::$restifyCommands)
            ? new $commandClass($modelName, $nestedResource)
            : new $commandClass($modelName);
    }

    /**
     * @param string $action
     * @return string
     */
    private static function prefix($action)
    {
        if (array_key_exists($action, static::$actionCommandMapping))
        {
            return static::$actionCommandMapping[$action];
        }

        return '';
    }

    /**
     * @param string $modelName
     * @param string $nestedResource
     * @param string $action
     * @return string
     */
    private static function commandClass($modelName, $nestedResource, $action)
    {
        $path = config('restify.paths.commands');

        $prefix = static::prefix($action);

        $path = str_replace('{model}', $modelName, $path);
        $path = str_replace('{nestedModel}', $nestedResource, $path);
        $path = str_replace('{prefix}', $prefix, $path);

        return class_exists($path) ? $path : "ChicoRei\\Packages\\Restify\\Commands\\" . $prefix . "Command";
    }
}

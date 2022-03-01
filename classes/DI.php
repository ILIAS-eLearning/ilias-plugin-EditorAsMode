<?php declare(strict_types=1);

namespace CaT\Plugins\EditorAsMode;

use Pimple\Container;
use ILIAS\GlobalScreen\Scope\Layout\Builder\StandardPageBuilder;

trait DI
{
    protected function buildDic(\ArrayAccess $dic) : Container
    {
        $container = new Container();

        $container['plugin.active'] = function ($c) use ($dic) {   
            $id = \ilEditorAsModePlugin::PLUGIN_ID;
            
            if($dic->offsetExists("component.repository")) {
                //ilias 8 
                $component_repository = $dic["component.repository"];
                return $component_repository->hasPluginId($id)
                    && $component_repository->getPluginById($id)->isActive();
            } else {
                // ilias 7
                return \ilPluginAdmin::isPluginActive($id);
            }
        };

        $container['gs'] = function ($c) use ($dic) {
            return $dic->globalScreen();
        };
        $container['gs.context.data'] = function ($c) {
            return $c['gs']->tool()->context()->current()->getAdditionalData();
        };


        $container['collection.parameter_name'] = function ($c) {
            return \ilCOPageEditGSToolProvider::SHOW_EDITOR;
        };

        $container['lng'] = function ($c) use ($dic) {
            return $dic['lng'];
        };

        $container['ctrl'] = function ($c) use ($dic) {
            return $dic['ilCtrl'];
        };

        $container['ui.factory'] = function ($c) use ($dic) {
            return $dic['ui.factory'];
        };

        $container['data.factory'] = function ($c) {
            $f = new \ILIAS\Data\Factory();
            return $f;
        };

        $container['ilias.baseurl'] = function ($c) {
            return ILIAS_HTTP_PATH;
        };
        $container['pagebuilder'] = function ($c) {
            return new StandardPageBuilder();
        };

        return $container;
    }
}

<?php declare(strict_types=1);

use ILIAS\GlobalScreen\ScreenContext\Stack\ContextCollection;
use ILIAS\GlobalScreen\ScreenContext\Stack\CalledContexts;
use ILIAS\GlobalScreen\Scope\Layout\Provider\AbstractModificationProvider;
use ILIAS\GlobalScreen\Scope\Layout\Provider\ModificationProvider;
use ILIAS\GlobalScreen\Scope\Layout\Provider\PagePart\PagePartProvider;
use ILIAS\GlobalScreen\Scope\Layout\Factory\MainBarModification;
use ILIAS\GlobalScreen\Scope\Layout\Factory\PageBuilderModification;
use ILIAS\UI\Component\MainControls\MainBar;
use ILIAS\UI\Component\MainControls\ModeInfo;
use ILIAS\UI\Component\Layout\Page\Page;

/**
 * Class ilEditorAsModeGSLayoutProvider
  */
class ilEditorAsModeGSLayoutProvider extends AbstractModificationProvider implements ModificationProvider
{
    use CaT\Plugins\EditorAsMode\DI;

    /**
     * @var null | \Pimple\Container
     */
    protected $plugin_dic = null;

    protected function getPluginDIC() : \Pimple\Container
    {
        if(! $this->plugin_dic) {
            $this->plugin_dic = $this->buildDic($this->dic);
        }
        return $this->plugin_dic;
    }

    protected function isInEditorMode() : bool
    {
        $dic = $this->getPluginDIC();

        if(! $dic['plugin.active']){
            return false;
        }

        $collection = $dic['gs']->tool()->context()->current()->getAdditionalData();
        $param = $dic['collection.parameter_name'];

        if ($collection->exists($param)) {
            return $collection->is($param, true);
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isInterestedInContexts() : ContextCollection
    {
        return $this->context_collection->main()->repository();
    }

    /**
     * @inheritDoc
     */
    public function getMainBarModification(CalledContexts $screen_context_stack) : ?MainBarModification
    {
        if (!$this->isInEditorMode()) {
            return null;
        }

        $dic = $this->getPluginDIC();

        return $dic['gs']->layout()->factory()->mainbar()
            ->withModification($this->getToolsToEntriesClosure())
            ->withHighPriority();
    }

    /**
     * @inheritDoc
     */
    public function getPageBuilderDecorator(CalledContexts $screen_context_stack) : ?PageBuilderModification
    {
        if (!$this->isInEditorMode()) {
            return null;
        }

        $dic = $this->getPluginDIC();

        $page_builder = $dic['pagebuilder'];
        $modeinfo = $this->getEditorModeInfo();

        return $this->factory->page()
            ->withModification(
                function (PagePartProvider $parts) use ($page_builder, $modeinfo) : Page {
                    $page = $page_builder->build($parts);
                    return $page->withModeInfo($modeinfo);
                }
            )
            ->withHighPriority();
    }

    protected function getToolsToEntriesClosure() : Closure
    {
        return function (MainBar $mainbar) : MainBar {
            $tools = $mainbar->getToolEntries();
            $mainbar = $mainbar->withClearedEntries();
            foreach ($tools as $key => $entry) {
                $mainbar = $mainbar->withAdditionalEntry($key, $entry);
            }
            return $mainbar;
        };
    }

    protected function getEditorModeInfo() : ModeInfo
    {
        $dic = $this->getPluginDIC();

        $label = $dic['lng']->txt('cont_finish_editing');
        $cmd = 'releasePageLock';
        //$cmd = 'finishEditing';

        $ctrl = $dic['ctrl'];
        $exitlink = $dic['data.factory']->uri(
            $dic['ilias.baseurl'] 
            . '/' 
            . $ctrl->getLinkTargetByClass($ctrl->getCurrentClassPath(), $cmd)
        );
        return $dic['ui.factory']->mainControls()->modeInfo($label, $exitlink);
    }
}

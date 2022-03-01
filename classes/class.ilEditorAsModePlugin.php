<?php declare(strict_types=1);

/**
 * Plugin base class. Keeps all information the plugin needs.
 */
class ilEditorAsModePlugin extends ilUserInterfaceHookPlugin
{
    /*
    const COMPONENT_TYPE = 'Services';
    const COMPONENT_NAME = 'UIComponent';
    const SLOT_ID = 'uihk';
    */
    const PLUGIN_ID = 'editormodeui';
    const PLUGIN_NAME = 'EditorAsMode';

    /**
     * @inheritDoc
     */
    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }
}

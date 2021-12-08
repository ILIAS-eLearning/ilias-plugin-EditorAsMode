<?php declare(strict_types=1);

/**
 * Plugin base class. Keeps all information the plugin needs.
 */
class ilEditorAsModePlugin extends ilUserInterfaceHookPlugin
{
    const PLUGINID = 'editormodeui';
    const PLUGINNAME = 'EditorAsMode';

    /**
     * @inheritDoc
     */
    public function getPluginName()
    {
        return self::PLUGINNAME;
    }
}

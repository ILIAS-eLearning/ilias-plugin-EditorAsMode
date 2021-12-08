<?php declare(strict_types=1);

namespace CaT\Plugins\EditorAsMode;

use PHPUnit\Framework\TestCase;
use \Pimple\Container;
use ILIAS\UI\Implementation\Component as UIComponent;

class TestingProvider extends \ilEditorAsModeGSLayoutProvider
{
    public function __construct(Container $dic)
    {
        $this->dic = $dic;
    }
    protected function getPluginDIC() : Container
    {
        return $this->dic;
    }
    public function pubIsInEditorMode() : bool
    {
        return $this->isInEditorMode();
    }
    public function pubGetToolsToEntriesClosure() : \Closure
    {
        return $this->getToolsToEntriesClosure();
    }
}

class ProviderTest extends TestCase
{
    public function testModeActivationPlugNotActive()
    {
        $c = new Container();
        $c['plugin.active'] = function ($c) {
            return false;
        };
        $c['gs.context.data'] = function ($c) {
            return new class() {
                public function is($a, $b): bool {
                    return true;
                }
            };
        };
        $p = new TestingProvider($c);
        $this->assertFalse($p->pubIsInEditorMode());
    }

    public function testModeActivationNoContext()
    {
        $c = new Container();
        $c['plugin.active'] = function ($c) {
            return true;
        };
        $c['collection.parameter_name'] = function ($c) {
            return 'nam';
        };
        $c['gs.context.data'] = function ($c) {
            return new class() {
                public function exists(): bool {
                    return true;
                }
                public function is($a, $b): bool {
                    return false;
                }

            };
        };
        $p = new TestingProvider($c);
        $this->assertFalse($p->pubIsInEditorMode());
    }

    public function testModeActivation()
    {
        $c = new Container();
        $c['plugin.active'] = function ($c) {
            return true;
        };
        $c['collection.parameter_name'] = function ($c) {
            return 'nam';
        };
        $c['gs.context.data'] = function ($c) {
            return new class() {
                public function exists(): bool {
                    return true;
                }
                public function is($a, $b): bool {
                    return true;
                }
            };
        };
        $p = new TestingProvider($c);
        $this->assertTrue($p->pubIsInEditorMode());
    }

    public function testToolsToEntries()
    {
        $p = new TestingProvider((new Container()));
        $c = $p->pubGetToolsToEntriesClosure();

        $sig_gen = new UIComponent\SignalGenerator();
        $icon_factory = new UIComponent\Symbol\Icon\Factory();
        $button_factory = new UIComponent\Button\Factory();
        $symbol = $icon_factory->custom('', '');
        $button = $button_factory->bulky($symbol, 'TestEntry', '#');
        $slate = $this->getMockBuilder(UIComponent\MainControls\Slate\Legacy::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mainbar = new UIComponent\MainControls\MainBar($sig_gen);
        $mainbar = $mainbar
            ->withAdditionalEntry('test1', $button)
            ->withAdditionalEntry('test2', $button)
            ->withToolsButton($button)
            ->withAdditionalToolEntry('tool1', $slate)
            ->withAdditionalToolEntry('tool2', $slate);

        $this->assertEquals(
            ['test1', 'test2'],
            array_keys($mainbar->getEntries())
        );
        $this->assertEquals(
            ['tool1', 'tool2'],
            array_keys($mainbar->getToolEntries())
        );

        $mainbar = $c($mainbar);

        $this->assertEquals(
            ['tool1', 'tool2'],
            array_keys($mainbar->getEntries())
        );
        $this->assertEquals(
            [],
            array_keys($mainbar->getToolEntries())
        );
    }
}

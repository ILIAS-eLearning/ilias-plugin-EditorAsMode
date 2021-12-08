<?php declare(strict_types=1);

use PHPUnit\Framework\TestSuite;
use CaT\Plugins\EditorAsMode;

class ilUserInterfaceHookEditorAsModeSuite extends TestSuite
{
    public static function suite()
    {
        $suite = new self();
        $suite->addTestSuite(EditorAsMode\ProviderTest::class);

        return $suite;
    }
}

# ilias-plugin-EditorAsMode
Sets ModeLayout when editing and turns mainbar-tools into sole entries

## Context
  starting the PageEditor sets a flag (lCOPageEditGSToolProvider::SHOW_EDITOR)
  in GlobalScreen's context.
  This plugin comes with a GlobalScreen ModificationProvider reacting to this
  flag  (and the additional activation of the plugin) to modify page's parts.

## Modifications
  - clearing mainbar
  - moving tools to mainbar-entries
  - removing breadcrumbs
  - removing footer
  - removing metabar-entries
  - using ModeInfo-component on the page to visualize editing-mode

## Supported Versions
* This plugin is compatible with ILIAS >= 7 and <= 8.

## Installation
This plugin should be installed using the [git](https://git-scm.com/)-command on the commandline.

* Open a command line and go to `./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/`.
* Clone the repository with `/path/to/git clone https://github.com/ILIAS-eLearning/ilias-plugin-EditorAsMode.git EditorAsMode`.
* Make sure the folder `EditorAsMode` and its contents are readable for the user your webserver runs as. We suggest to not give write access on this folder to this user.
* Change to your ILIAS root and run `/path/to/php setup/setup.php build-artifacts`.
* You can then finalize the installation and activate the plugin through the web interface under Administration -> Extending ILIAS -> Plugins.
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
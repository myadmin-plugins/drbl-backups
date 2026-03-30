---
name: plugin-requirement
description: Adds a $loader->add_requirement() call inside getRequirements() in src/Plugin.php. Use when user says 'load class', 'add requirement', 'register function', 'lazy-load', or adds a new .php/.inc.php to src/. Do NOT use for menu entries, settings changes, or event hook bindings.
---
# plugin-requirement

## Critical

- The identifier (first arg) must exactly match the class name (prefixed `class.`) or function name that other code will pass to `function_requirements()`. Using the wrong identifier silently breaks lazy-loading.
- The path (second arg) **must** start with `/../vendor/detain/myadmin-drbl-backups/src/` — never use an absolute path or omit the leading `/../`.
- Multiple identifiers from the same file each need their own `add_requirement()` line — do not combine them.
- Note: `tests/PluginTest.php` asserts the exact count of `add_requirement` calls via `substr_count`. After adding a new call, update `testGetRequirementsUsesLoaderAddRequirement` to match the new count.

## Instructions

1. **Identify the identifier and file.**
   - For a class: identifier uses `class.ClassName` format — see `src/Plugin.php` for existing patterns.
   - For a function: identifier is the function name; path is the filename within the package `src/` directory.
   - Verify the source file exists in `src/` before proceeding.

2. **Open `src/Plugin.php` and locate `getRequirements()`** (currently lines 53–63). The method body begins with:
   ```php
   $loader = $event->getSubject();
   ```
   Append your new line(s) after the last existing `add_requirement` call, before the closing `}`.

3. **Add the line using this exact format (tab-indented):**
   ```php
   		$loader->add_requirement('identifier', '/../vendor/detain/myadmin-drbl-backups/src/filename.php');
   ```
   Use double-tab indentation (method body indent level), consistent with the existing lines in `src/Plugin.php`.

4. **Update `tests/PluginTest.php`** — find `testGetRequirementsUsesLoaderAddRequirement` and change the expected count passed to `assertSame()` to reflect the new total number of `add_requirement` calls.

5. **Run tests to verify:**
   ```bash
   vendor/bin/phpunit
   ```
   All tests must pass before committing.

## Examples

**User says:** "Add a requirement for `backup_restore` function in `src/restore.inc.php`"

**Actions taken:**
1. Confirm `src/restore.inc.php` exists.
2. In `src/Plugin.php`, append inside `getRequirements()`:
   ```php
   		$loader->add_requirement('backup_restore', '/../vendor/detain/myadmin-drbl-backups/src/restore.inc.php');
   ```
3. In `tests/PluginTest.php`, update `testGetRequirementsUsesLoaderAddRequirement`:
   ```php
   $this->assertSame(
   	5,  // was 4
   	substr_count($source, 'add_requirement'),
   	'getRequirements should call add_requirement exactly 5 times'
   );
   ```
4. Run `vendor/bin/phpunit` — all tests pass.

**Result:** `backup_restore` is now lazy-loadable via `function_requirements('backup_restore')` anywhere in MyAdmin.

## Common Issues

- **Test failure: `assertSame(4, ...) failed, got 5`** — You added the `add_requirement` call but forgot to update the count assertion in `testGetRequirementsUsesLoaderAddRequirement`. Change the expected integer to match the new total.
- **Function/class not found at runtime despite registration** — The path is wrong. Confirm it starts with `/../vendor/detain/myadmin-drbl-backups/src/` and the filename matches exactly (case-sensitive). See existing calls in `src/Plugin.php` for reference.
- **`vendor/bin/phpunit` not found** — Run `composer install` first to install PHPUnit 9.6.
- **Identifier collision (requirement silently ignored)** — If the identifier already exists in another plugin's `getRequirements()`, the loader may skip it. Use a unique identifier; class identifiers use `class.ClassName` to avoid collisions with function names.

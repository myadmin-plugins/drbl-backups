---
name: plugin-event-handler
description: Adds a new static event handler method to src/Plugin.php and registers it in getHooks(). Use when user says 'add hook', 'new event handler', 'register event', or modifies getHooks(). Generates method stub + getHooks() entry. Do NOT use for editing existing handlers or modifying getRequirements()/getSettings().
---
# plugin-event-handler

## Critical

- ALL handler methods MUST be `public static` — no instance methods, no instance state
- Parameter MUST be type-hinted as `GenericEvent $event` (fully imported via `use`)
- The `use Symfony\Component\EventDispatcher\GenericEvent;` import already exists in `src/Plugin.php` — do NOT add a duplicate
- `getHooks()` entries use `[__CLASS__, 'methodName']` — never hardcode the class string
- Indentation: **tabs only** (enforced by `.scrutinizer.yml`)
- `testExpectedMethodsExist()` in `tests/PluginTest.php` hardcodes the expected method list — you MUST update it when adding a new method

## Instructions

1. **Identify the event name and handler method name.**  
   Event name: a string like `'drbl_some_action'`. Method name: camelCase, e.g. `handleSomeAction`.  
   Verify no existing method with that name exists in `src/Plugin.php` before proceeding.

2. **Add the method to `src/Plugin.php`** before the closing `}` of the class, after the last existing method (`getSettings`). Use this exact shape (tabs for indentation):
   ```php
   	/**
   	 * @param \Symfony\Component\EventDispatcher\GenericEvent $event
   	 */
   	public static function handleSomeAction(GenericEvent $event)
   	{
   		$subject = $event->getSubject();
   	}
   ```
   Adapt the docblock `@param` line to match exactly what the existing methods use.

3. **Register the hook in `getHooks()`** by adding an uncommented entry to the returned array:
   ```php
   	public static function getHooks()
   	{
   		return [
   			//'system.settings' => [__CLASS__, 'getSettings'],
   			//'ui.menu' => [__CLASS__, 'getMenu'],
   			'drbl_some_action' => [__CLASS__, 'handleSomeAction'],
   		];
   	}
   ```
   This step uses the method name from Step 1. Preserve existing commented-out entries.

4. **Update `tests/PluginTest.php` `testExpectedMethodsExist()`** — add the new method name to the `$expected` array:
   ```php
   $expected = ['__construct', 'getHooks', 'getMenu', 'getRequirements', 'getSettings', 'handleSomeAction'];
   ```
   Also update `testAllEventHandlersAreStatic()` if it lists handlers explicitly.

5. **Verify** by running `vendor/bin/phpunit` — all tests must pass.

## Examples

**User says:** "Add a hook for the `drbl_activate` event"

**Actions:**
- Method name chosen: `activate`
- Add to `src/Plugin.php`:
  ```php
  	/**
  	 * @param \Symfony\Component\EventDispatcher\GenericEvent $event
  	 */
  	public static function activate(GenericEvent $event)
  	{
  		$subject = $event->getSubject();
  	}
  ```
- Update `getHooks()` return array:
  ```php
  			'drbl_activate' => [__CLASS__, 'activate'],
  ```
- Update `$expected` in `testExpectedMethodsExist()`:
  ```php
  $expected = ['__construct', 'activate', 'getHooks', 'getMenu', 'getRequirements', 'getSettings'];
  ```
- Run `vendor/bin/phpunit` → all green

**Result:** `Plugin::activate(GenericEvent $event)` is registered and dispatched for the `drbl_activate` event.

## Common Issues

- **`testExpectedMethodsExist` fails with unexpected method count:** You added the method but forgot to update the `$expected` array in `tests/PluginTest.php:396`. Add the new method name to that array.
- **`testAllEventHandlersAreStatic` fails:** The new method was declared without `static`. Add `static` to the method signature.
- **`testGetHooksReturnsEmptyArray` fails after adding a hook:** This test (`tests/PluginTest.php:228`) asserts `getHooks()` is empty — it was written when all hooks were commented out. Update it to `assertNotEmpty` or add a specific assertion for your new key.
- **PHP parse error on indentation:** File uses tabs, not spaces. If your editor inserted spaces, replace with tabs: `unexpand --first-only src/Plugin.php`.
- **Duplicate `use` import error:** `GenericEvent` is already imported at line 5 of `src/Plugin.php`. Do not add another `use` statement.
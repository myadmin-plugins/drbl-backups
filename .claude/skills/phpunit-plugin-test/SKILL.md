---
name: phpunit-plugin-test
description: Creates or extends PHPUnit 9.6 tests in `tests/PluginTest.php` under namespace `Detain\MyAdminDrbl\Tests\`. Use when user says 'write test', 'add test case', 'test this method', or coverage is needed for `src/Plugin.php`. Follows bootstrap in `phpunit.xml.dist`. Do NOT use for integration tests against a real MyAdmin install or for testing files outside `src/Plugin.php`.
---
# PHPUnit Plugin Test

## Critical

- All tests go in `tests/PluginTest.php` — do NOT create new test files.
- Namespace must be `Detain\MyAdminDrbl\Tests\` with `use Detain\MyAdminDrbl\Plugin;`.
- Use `ReflectionClass` / `ReflectionMethod` for structural assertions — never call `getMenu()` or `getSettings()` directly (they depend on `$GLOBALS['tf']` and other MyAdmin globals).
- For behavioral tests of `getRequirements()` or `getSettings()`, pass an anonymous class as the subject via `new GenericEvent($anonymousObject)` — do NOT mock with `createMock()`.
- Indentation: tabs only (enforced by `.scrutinizer.yml`).
- Run tests per the configuration in `phpunit.xml.dist`; PHPUnit 9.6 is required (see `composer.json`).

## Instructions

1. **Read `tests/PluginTest.php` and `src/Plugin.php`** before adding any test. Verify the method or property you're testing exists in `Plugin` and no duplicate test method exists in `PluginTest`.

2. **Add the test method** inside `class PluginTest extends TestCase` following this structure:
 ```php
 /**
  * Short description of what is verified.
  *
  * @return void
  */
 public function testMyMethodDoesX(): void
 {
     // structural: use $this->reflection
     $method = $this->reflection->getMethod('myMethod');
     $this->assertTrue($method->isPublic());
     $this->assertTrue($method->isStatic());
 }
 ```

3. **Structural tests** (method exists, is static, param types, return type) use `$this->reflection` set up in `setUp()`:
 ```php
 $method = $this->reflection->getMethod('getRequirements');
 $param = $method->getParameters()[0];
 $this->assertSame('Symfony\\Component\\EventDispatcher\\GenericEvent', $param->getType()->getName());
 ```

4. **Behavioral tests** for `getRequirements()` — use an anonymous loader class, not a mock:
 ```php
 $loader = new class {
     public array $requirements = [];
     public function add_requirement(string $name, string $path): void {
         $this->requirements[] = [$name, $path];
     }
 };
 $event = new \Symfony\Component\EventDispatcher\GenericEvent($loader);
 Plugin::getRequirements($event);
 $this->assertCount(4, $loader->requirements);
 ```

5. **Source-level analysis tests** — use `file_get_contents($this->reflection->getFileName())` to assert strings present in `src/Plugin.php`:
 ```php
 $source = file_get_contents($this->reflection->getFileName());
 $this->assertStringContainsString('add_requirement', $source);
 ```

6. **Method-body scoped tests** — slice lines by `getStartLine()`/`getEndLine()`:
 ```php
 $method = $this->reflection->getMethod('getSettings');
 $source = file_get_contents($this->reflection->getFileName());
 $lines = array_slice(explode("\n", $source), $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1);
 $this->assertStringContainsString('getSubject()', implode("\n", $lines));
 ```

7. **Verify tests pass:** Run PHPUnit per `phpunit.xml.dist` — all tests must be green before finishing.

## Examples

**User says:** "Add a test that verifies `getHooks` returns an empty array."

**Actions:**
- Read `tests/PluginTest.php` — confirm `testGetHooksReturnsEmptyArray` does not exist.
- Read `src/Plugin.php` — confirm `getHooks()` returns `[]` (all entries commented out).
- Add inside `PluginTest`:
```php
public function testGetHooksReturnsEmptyArray(): void
{
    $result = Plugin::getHooks();
    $this->assertIsArray($result);
    $this->assertEmpty($result);
}
```
- Run PHPUnit per `phpunit.xml.dist` → green.

**Result:** New test added in the correct section, using direct static call (safe because `getHooks` has no `$GLOBALS` deps).

## Common Issues

- **"Call to undefined function function_requirements()"** when calling `Plugin::getMenu()` or `Plugin::getSettings()` directly: These methods depend on MyAdmin globals. Use `ReflectionMethod` for structural tests and anonymous-class `GenericEvent` subjects for behavioral ones — never call them bare.
- **"Class 'Symfony\\Component\\EventDispatcher\\GenericEvent' not found"** when running tests: Run `composer install` first; the Symfony EventDispatcher is a required dep in `composer.json`.
- **"No tests executed"** or wrong suite: Ensure the test class file is in `tests/` and the class name ends in `Test`. Config in `phpunit.xml.dist` scans `<directory>tests</directory>` for the `Unit` suite.
- **Scrutinizer indentation failure:** Use tabs, not spaces. If `.scrutinizer.yml` flags the file, replace space indentation: `sed -i 's/    /\t/g' tests/PluginTest.php` then verify manually.
- **"assertCount(4, ...) failed, got 3"** on `testGetRequirementsRegistersCorrectRequirements`: Check `src/Plugin.php` — if a requirement was added/removed, update the expected count in both the test and the source-level `substr_count` assertion.

<?php

namespace Detain\MyAdminDrbl\Tests;

use Detain\MyAdminDrbl\Plugin;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

/**
 * Test suite for the Detain\MyAdminDrbl\Plugin class.
 *
 * Covers class structure, static properties, method signatures,
 * return values, and source-level analysis for DB-heavy code paths.
 */
class PluginTest extends TestCase
{
    /**
     * @var ReflectionClass<Plugin>
     */
    private ReflectionClass $reflection;

    protected function setUp(): void
    {
        $this->reflection = new ReflectionClass(Plugin::class);
    }

    // ---------------------------------------------------------------
    // Class structure tests
    // ---------------------------------------------------------------

    /**
     * Verify the Plugin class exists and is instantiable.
     *
     * @return void
     */
    public function testClassExists(): void
    {
        $this->assertTrue(class_exists(Plugin::class));
        $this->assertTrue($this->reflection->isInstantiable());
    }

    /**
     * Verify the class is not abstract or final.
     *
     * @return void
     */
    public function testClassIsConcreteAndNotFinal(): void
    {
        $this->assertFalse($this->reflection->isAbstract());
        $this->assertFalse($this->reflection->isFinal());
    }

    /**
     * Verify the class resides in the correct namespace.
     *
     * @return void
     */
    public function testClassNamespace(): void
    {
        $this->assertSame('Detain\\MyAdminDrbl', $this->reflection->getNamespaceName());
    }

    /**
     * Verify the fully-qualified class name.
     *
     * @return void
     */
    public function testFullyQualifiedClassName(): void
    {
        $this->assertSame('Detain\\MyAdminDrbl\\Plugin', $this->reflection->getName());
    }

    // ---------------------------------------------------------------
    // Static property tests
    // ---------------------------------------------------------------

    /**
     * Verify the $name static property exists and has the expected value.
     *
     * @return void
     */
    public function testStaticPropertyName(): void
    {
        $this->assertTrue($this->reflection->hasProperty('name'));
        $prop = $this->reflection->getProperty('name');
        $this->assertTrue($prop->isPublic());
        $this->assertTrue($prop->isStatic());
        $this->assertSame('Drbl Plugin', Plugin::$name);
    }

    /**
     * Verify the $description static property exists and has the expected value.
     *
     * @return void
     */
    public function testStaticPropertyDescription(): void
    {
        $this->assertTrue($this->reflection->hasProperty('description'));
        $prop = $this->reflection->getProperty('description');
        $this->assertTrue($prop->isPublic());
        $this->assertTrue($prop->isStatic());
        $this->assertSame('Allows handling of Drbl based Backups', Plugin::$description);
    }

    /**
     * Verify the $help static property exists and is an empty string.
     *
     * @return void
     */
    public function testStaticPropertyHelp(): void
    {
        $this->assertTrue($this->reflection->hasProperty('help'));
        $prop = $this->reflection->getProperty('help');
        $this->assertTrue($prop->isPublic());
        $this->assertTrue($prop->isStatic());
        $this->assertSame('', Plugin::$help);
    }

    /**
     * Verify the $type static property exists and equals 'plugin'.
     *
     * @return void
     */
    public function testStaticPropertyType(): void
    {
        $this->assertTrue($this->reflection->hasProperty('type'));
        $prop = $this->reflection->getProperty('type');
        $this->assertTrue($prop->isPublic());
        $this->assertTrue($prop->isStatic());
        $this->assertSame('plugin', Plugin::$type);
    }

    /**
     * Verify the class has exactly four static properties.
     *
     * @return void
     */
    public function testStaticPropertyCount(): void
    {
        $staticProps = array_filter(
            $this->reflection->getProperties(),
            static fn(\ReflectionProperty $p) => $p->isStatic()
        );
        $this->assertCount(4, $staticProps);
    }

    // ---------------------------------------------------------------
    // Constructor tests
    // ---------------------------------------------------------------

    /**
     * Verify the constructor exists and is public.
     *
     * @return void
     */
    public function testConstructorIsPublic(): void
    {
        $constructor = $this->reflection->getConstructor();
        $this->assertNotNull($constructor);
        $this->assertTrue($constructor->isPublic());
    }

    /**
     * Verify the constructor takes no parameters.
     *
     * @return void
     */
    public function testConstructorHasNoParameters(): void
    {
        $constructor = $this->reflection->getConstructor();
        $this->assertNotNull($constructor);
        $this->assertCount(0, $constructor->getParameters());
    }

    /**
     * Verify the Plugin can be instantiated without errors.
     *
     * @return void
     */
    public function testInstantiation(): void
    {
        $plugin = new Plugin();
        $this->assertInstanceOf(Plugin::class, $plugin);
    }

    // ---------------------------------------------------------------
    // getHooks() tests
    // ---------------------------------------------------------------

    /**
     * Verify getHooks method exists and is public static.
     *
     * @return void
     */
    public function testGetHooksMethodSignature(): void
    {
        $this->assertTrue($this->reflection->hasMethod('getHooks'));
        $method = $this->reflection->getMethod('getHooks');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    /**
     * Verify getHooks takes no parameters.
     *
     * @return void
     */
    public function testGetHooksHasNoParameters(): void
    {
        $method = $this->reflection->getMethod('getHooks');
        $this->assertCount(0, $method->getParameters());
    }

    /**
     * Verify getHooks returns an array.
     *
     * @return void
     */
    public function testGetHooksReturnsArray(): void
    {
        $result = Plugin::getHooks();
        $this->assertIsArray($result);
    }

    /**
     * Verify getHooks currently returns an empty array (all hooks commented out).
     *
     * @return void
     */
    public function testGetHooksReturnsEmptyArray(): void
    {
        $result = Plugin::getHooks();
        $this->assertEmpty($result);
    }

    // ---------------------------------------------------------------
    // getMenu() method signature tests
    // ---------------------------------------------------------------

    /**
     * Verify getMenu method exists and is public static.
     *
     * @return void
     */
    public function testGetMenuMethodSignature(): void
    {
        $this->assertTrue($this->reflection->hasMethod('getMenu'));
        $method = $this->reflection->getMethod('getMenu');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    /**
     * Verify getMenu accepts exactly one parameter.
     *
     * @return void
     */
    public function testGetMenuParameterCount(): void
    {
        $method = $this->reflection->getMethod('getMenu');
        $this->assertCount(1, $method->getParameters());
    }

    /**
     * Verify getMenu parameter is type-hinted as GenericEvent.
     *
     * @return void
     */
    public function testGetMenuParameterType(): void
    {
        $method = $this->reflection->getMethod('getMenu');
        $param = $method->getParameters()[0];
        $this->assertSame('event', $param->getName());
        $this->assertTrue($param->hasType());
        $this->assertSame(
            'Symfony\\Component\\EventDispatcher\\GenericEvent',
            $param->getType()->getName()
        );
    }

    /**
     * Verify getMenu has void return type (implicit).
     *
     * @return void
     */
    public function testGetMenuReturnType(): void
    {
        $method = $this->reflection->getMethod('getMenu');
        $returnType = $method->getReturnType();
        // The method has no explicit return type declaration
        $this->assertNull($returnType);
    }

    // ---------------------------------------------------------------
    // getRequirements() method signature tests
    // ---------------------------------------------------------------

    /**
     * Verify getRequirements method exists and is public static.
     *
     * @return void
     */
    public function testGetRequirementsMethodSignature(): void
    {
        $this->assertTrue($this->reflection->hasMethod('getRequirements'));
        $method = $this->reflection->getMethod('getRequirements');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    /**
     * Verify getRequirements accepts exactly one parameter.
     *
     * @return void
     */
    public function testGetRequirementsParameterCount(): void
    {
        $method = $this->reflection->getMethod('getRequirements');
        $this->assertCount(1, $method->getParameters());
    }

    /**
     * Verify getRequirements parameter is type-hinted as GenericEvent.
     *
     * @return void
     */
    public function testGetRequirementsParameterType(): void
    {
        $method = $this->reflection->getMethod('getRequirements');
        $param = $method->getParameters()[0];
        $this->assertSame('event', $param->getName());
        $this->assertTrue($param->hasType());
        $this->assertSame(
            'Symfony\\Component\\EventDispatcher\\GenericEvent',
            $param->getType()->getName()
        );
    }

    // ---------------------------------------------------------------
    // getSettings() method signature tests
    // ---------------------------------------------------------------

    /**
     * Verify getSettings method exists and is public static.
     *
     * @return void
     */
    public function testGetSettingsMethodSignature(): void
    {
        $this->assertTrue($this->reflection->hasMethod('getSettings'));
        $method = $this->reflection->getMethod('getSettings');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    /**
     * Verify getSettings accepts exactly one parameter.
     *
     * @return void
     */
    public function testGetSettingsParameterCount(): void
    {
        $method = $this->reflection->getMethod('getSettings');
        $this->assertCount(1, $method->getParameters());
    }

    /**
     * Verify getSettings parameter is type-hinted as GenericEvent.
     *
     * @return void
     */
    public function testGetSettingsParameterType(): void
    {
        $method = $this->reflection->getMethod('getSettings');
        $param = $method->getParameters()[0];
        $this->assertSame('event', $param->getName());
        $this->assertTrue($param->hasType());
        $this->assertSame(
            'Symfony\\Component\\EventDispatcher\\GenericEvent',
            $param->getType()->getName()
        );
    }

    // ---------------------------------------------------------------
    // Method completeness tests
    // ---------------------------------------------------------------

    /**
     * Verify the class declares exactly the expected set of methods.
     *
     * @return void
     */
    public function testExpectedMethodsExist(): void
    {
        $expected = ['__construct', 'getHooks', 'getMenu', 'getRequirements', 'getSettings'];
        $actual = array_map(
            static fn(ReflectionMethod $m) => $m->getName(),
            $this->reflection->getMethods(ReflectionMethod::IS_PUBLIC)
        );
        sort($expected);
        sort($actual);
        $this->assertSame($expected, $actual);
    }

    /**
     * Verify all event handler methods are static.
     *
     * @return void
     */
    public function testAllEventHandlersAreStatic(): void
    {
        $handlers = ['getMenu', 'getRequirements', 'getSettings', 'getHooks'];
        foreach ($handlers as $handler) {
            $method = $this->reflection->getMethod($handler);
            $this->assertTrue(
                $method->isStatic(),
                "Method {$handler} should be static"
            );
        }
    }

    /**
     * Verify all GenericEvent handler methods accept exactly one GenericEvent parameter.
     *
     * @return void
     */
    public function testEventHandlerSignaturesAreConsistent(): void
    {
        $handlers = ['getMenu', 'getRequirements', 'getSettings'];
        foreach ($handlers as $handler) {
            $method = $this->reflection->getMethod($handler);
            $params = $method->getParameters();
            $this->assertCount(
                1,
                $params,
                "Method {$handler} should have exactly 1 parameter"
            );
            $this->assertSame(
                'Symfony\\Component\\EventDispatcher\\GenericEvent',
                $params[0]->getType()->getName(),
                "Method {$handler} parameter should be GenericEvent"
            );
        }
    }

    // ---------------------------------------------------------------
    // Source-level static analysis tests
    // ---------------------------------------------------------------

    /**
     * Verify the source file uses the correct namespace declaration.
     *
     * @return void
     */
    public function testSourceFileNamespace(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertIsString($source);
        $this->assertStringContainsString('namespace Detain\\MyAdminDrbl;', $source);
    }

    /**
     * Verify the source file imports GenericEvent.
     *
     * @return void
     */
    public function testSourceFileImportsGenericEvent(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString(
            'use Symfony\\Component\\EventDispatcher\\GenericEvent;',
            $source
        );
    }

    /**
     * Verify getMenu references the GLOBALS tf->ima admin check.
     *
     * @return void
     */
    public function testGetMenuReferencesAdminCheck(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString("\\MyAdmin\App::ima() == 'admin'", $source);
    }

    /**
     * Verify getMenu references the has_acl function.
     *
     * @return void
     */
    public function testGetMenuReferencesAclCheck(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString("has_acl('client_billing')", $source);
    }

    /**
     * Verify getHooks has commented-out system.settings and ui.menu entries.
     *
     * @return void
     */
    public function testGetHooksContainsCommentedHookEntries(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString("'system.settings'", $source);
        $this->assertStringContainsString("'ui.menu'", $source);
    }

    /**
     * Verify the source file has a proper PHP opening tag.
     *
     * @return void
     */
    public function testSourceFileStartsWithPhpTag(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringStartsWith('<?php', $source);
    }

    /**
     * Verify the source file contains a class docblock.
     *
     * @return void
     */
    public function testSourceFileHasClassDocblock(): void
    {
        $docComment = $this->reflection->getDocComment();
        $this->assertIsString($docComment);
        $this->assertStringContainsString('Class Plugin', $docComment);
        $this->assertStringContainsString('@package', $docComment);
    }

    /**
     * Verify getSettings retrieves the subject from the event.
     *
     * @return void
     */
    public function testGetSettingsUsesGetSubject(): void
    {
        $method = $this->reflection->getMethod('getSettings');
        $startLine = $method->getStartLine();
        $endLine = $method->getEndLine();
        $source = file_get_contents($this->reflection->getFileName());
        $lines = array_slice(explode("\n", $source), $startLine - 1, $endLine - $startLine + 1);
        $methodBody = implode("\n", $lines);
        $this->assertStringContainsString('getSubject()', $methodBody);
    }

    /**
     * Verify that getMenu uses getSubject() to extract the menu.
     *
     * @return void
     */
    public function testGetMenuUsesGetSubject(): void
    {
        $method = $this->reflection->getMethod('getMenu');
        $startLine = $method->getStartLine();
        $endLine = $method->getEndLine();
        $source = file_get_contents($this->reflection->getFileName());
        $lines = array_slice(explode("\n", $source), $startLine - 1, $endLine - $startLine + 1);
        $methodBody = implode("\n", $lines);
        $this->assertStringContainsString('getSubject()', $methodBody);
    }

    /**
     * Verify that getRequirements uses getSubject() to extract the loader.
     *
     * @return void
     */
    public function testGetRequirementsUsesGetSubject(): void
    {
        $method = $this->reflection->getMethod('getRequirements');
        $startLine = $method->getStartLine();
        $endLine = $method->getEndLine();
        $source = file_get_contents($this->reflection->getFileName());
        $lines = array_slice(explode("\n", $source), $startLine - 1, $endLine - $startLine + 1);
        $methodBody = implode("\n", $lines);
        $this->assertStringContainsString('getSubject()', $methodBody);
    }

    // ---------------------------------------------------------------
    // getRequirements() behavioral test with anonymous loader
    // ---------------------------------------------------------------

    /**
     * Verify every source getRequirements registers resolves to a file that exists.
     *
     * function_requirements() require_once's whatever path it finds in the loader
     * table, so a registered path that points at nothing is a fatal waiting for the
     * first request that needs that identifier. This replaces the previous
     * testGetRequirementsReferencedPaths / testGetRequirementsVendorPaths /
     * testGetRequirementsUsesLoaderAddRequirement /
     * testGetRequirementsRegistersCorrectRequirements /
     * testGetRequirementsRegistersCorrectPaths, all of which only inspected the
     * registration table (or the source text) and never the filesystem -- so they
     * stayed green for years while all four registrations pointed at src/Drbl.php
     * and src/abuse.inc.php, files that have never existed in this package.
     *
     * The registration table is now empty, so this passes vacuously today. That is
     * the correct result: it asserts a property of whatever is registered, and it
     * will fail the moment a dangling path is added back.
     *
     * Registered values are written relative to core's INCLUDE_ROOT and take the
     * shape '/../vendor/<composer-name>/<tail>', climbing back out of include/ into
     * vendor/ and landing inside this package, so <tail> is resolved against the
     * package root. Anything else is resolved against a core include/ directory when
     * this package is running from inside a core checkout.
     *
     * @return void
     */
    public function testEveryRegisteredRequirementSourceExistsOnDisk(): void
    {
        $loader = new class {
            /** @var array<int, array{0: string, 1: string}> */
            public array $requirements = [];

            public function add_requirement(string $name, string $path): void
            {
                $this->requirements[] = [$name, $path];
            }
        };

        $event = new \Symfony\Component\EventDispatcher\GenericEvent($loader);
        Plugin::getRequirements($event);

        $packageDir = dirname($this->reflection->getFileName(), 2);
        $selfPrefix = '/../vendor/detain/myadmin-drbl-backups/';
        $includeRoot = dirname($packageDir, 3).'/include';

        foreach ($loader->requirements as [$name, $path]) {
            if (strpos($path, $selfPrefix) === 0) {
                $resolved = $packageDir.'/'.substr($path, strlen($selfPrefix));
            } else {
                if (!is_dir($includeRoot)) {
                    // Not running from inside a core checkout; nothing to resolve against.
                    continue;
                }
                $resolved = $includeRoot.'/'.ltrim($path, '/');
            }

            $this->assertFileExists(
                $resolved,
                "Requirement '{$name}' registers '{$path}', which resolves to '{$resolved}' -- no such file"
            );
        }

        // Guard against the loop above being silently empty of assertions.
        $this->assertIsArray($loader->requirements);
    }

    // ---------------------------------------------------------------
    // getSettings() behavioral test with anonymous settings object
    // ---------------------------------------------------------------

    /**
     * Verify getSettings extracts the subject from the event without error.
     *
     * @return void
     */
    public function testGetSettingsExtractsSubject(): void
    {
        $settings = new class {
            public bool $accessed = false;
        };

        $event = new \Symfony\Component\EventDispatcher\GenericEvent($settings);
        // getSettings should not throw; it only retrieves the subject
        Plugin::getSettings($event);

        // Verify the event subject is still our settings object
        $this->assertSame($settings, $event->getSubject());
    }
}

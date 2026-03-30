# MyAdmin DRBL Backups Plugin

Composer plugin providing DRBL backup service integration for the MyAdmin control panel via Symfony EventDispatcher hooks.

## Commands

```bash
composer install              # install deps including phpunit/phpunit ^9.6
vendor/bin/phpunit            # run tests (uses phpunit.xml.dist)
```

## Architecture

**Entry:** `src/Plugin.php` · namespace `Detain\MyAdminDrbl\` · PSR-4 via `composer.json`
**Tests:** `tests/PluginTest.php` · namespace `Detain\MyAdminDrbl\Tests\` · bootstrap `vendor/autoload.php`
**CI/CD:** `.github/` contains workflows for automated testing and deployment pipelines
**IDE Config:** `.idea/` contains inspectionProfiles, deployment.xml, and encodings.xml for project setup

`Plugin` static methods — all receive `Symfony\Component\EventDispatcher\GenericEvent $event`:
- `getHooks()` — returns array of event hook bindings
- `getMenu($event)` — registers admin menu entries (checks `$GLOBALS['tf']->ima == 'admin'` + `has_acl`)
- `getRequirements($event)` — calls `$loader->add_requirement('identifier', 'path')` for class/function lazy-loads
- `getSettings($event)` — integrates with MyAdmin settings via `$event->getSubject()`

## Conventions

- All plugin methods are `public static` — no instance state
- ACL checks: `function_requirements('has_acl')` before `has_acl('client_billing')`
- Tests use PHPUnit 9.6; config in `phpunit.xml.dist`
- Indentation: tabs (enforced by `.scrutinizer.yml`)
- Commit messages: lowercase, descriptive

## Adding a New Requirement

```php
// in src/Plugin.php getRequirements()
$loader->add_requirement('my_function', '/../vendor/[package]/src/file.inc.php');
```

## Adding a New Hook

```php
// in getHooks() return array:
return [
    'drbl_event_name' => ['\\Detain\\MyAdminDrbl\\Plugin', 'myHandler'],
];
```

<!-- caliber:managed:pre-commit -->
## Before Committing

**IMPORTANT:** Before every git commit, you MUST ensure Caliber syncs agent configs with the latest code changes.

First, check if the pre-commit hook is already installed:
```bash
grep -q "caliber" .git/hooks/pre-commit 2>/dev/null && echo "hook-active" || echo "no-hook"
```

- If **hook-active**: the hook handles sync automatically — just commit normally. Tell the user: "Caliber will sync your agent configs automatically via the pre-commit hook."
- If **no-hook**: run Caliber manually before committing:
  1. Tell the user: "Caliber: Syncing agent configs with your latest changes..."
  2. Run: `caliber refresh && git add CLAUDE.md .claude/ .cursor/ .cursorrules .github/copilot-instructions.md .github/instructions/ AGENTS.md CALIBER_LEARNINGS.md 2>/dev/null`
  3. After it completes, briefly tell the user what Caliber updated. Then proceed with the commit.

If `caliber` is not found, tell the user: "This project uses Caliber for agent config sync. Run /setup-caliber to get set up."
<!-- /caliber:managed:pre-commit -->

<!-- caliber:managed:learnings -->
## Session Learnings

Read `CALIBER_LEARNINGS.md` for patterns and anti-patterns learned from previous sessions.
These are auto-extracted from real tool usage — treat them as project-specific rules.
<!-- /caliber:managed:learnings -->

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\Route as RouteObject;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ExportRoutesForRagCommand extends Command
{
    protected $signature = 'rag:export-routes
        {--output=resources/markdown/hiring_hall_routes_reference.md : Path relative to the project base path}';

    protected $description = 'Export web routes as markdown (Q/A + index) for Ask Hill AI RAG';

    public function handle(): int
    {
        $basePath = base_path();
        $relative = (string) $this->option('output');
        $outPath = Str::startsWith($relative, DIRECTORY_SEPARATOR)
            ? $relative
            : $basePath.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);

        $rows = $this->collectRouteRows();

        $md = $this->buildMarkdown($rows);

        if (! is_dir(dirname($outPath))) {
            mkdir(dirname($outPath), 0755, true);
        }

        file_put_contents($outPath, $md);

        $this->info('Wrote '.$outPath.' ('.strlen($md).' bytes, '.$rows->count().' routes).');

        return self::SUCCESS;
    }

    /**
     * @return Collection<int, array{methods: string, path: string, name: string, action: string}>
     */
    protected function collectRouteRows(): Collection
    {
        $out = collect();

        /** @var RouteObject $route */
        foreach (app('router')->getRoutes() as $route) {
            $action = $route->getActionName();

            if ($action === 'Closure') {
                continue;
            }

            if (str_starts_with($action, 'Illuminate\\')) {
                continue;
            }

            if (str_contains($action, 'Livewire\\')) {
                continue;
            }

            $uri = ltrim((string) $route->uri(), '/');
            if (Str::startsWith($uri, ['sanctum/', 'telescope/', 'pulse/', '_ignition/', 'vendor/'])) {
                continue;
            }

            $methods = array_values(array_diff($route->methods(), ['HEAD']));
            sort($methods);
            if ($methods === []) {
                continue;
            }

            $path = '/'.($uri === '' ? '' : $uri);
            if ($path !== '/' && str_ends_with($path, '/')) {
                $path = rtrim($path, '/');
            }

            $name = (string) ($route->getName() ?? '');

            $out->push([
                'methods' => implode(',', $methods),
                'path' => $path,
                'name' => $name,
                'action' => $action,
            ]);
        }

        return $out->unique(fn (array $r): string => $r['methods'].'|'.$r['path'].'|'.$r['name'])
            ->sortBy('path')
            ->values();
    }

    /**
     * @param  Collection<int, array{methods: string, path: string, name: string, action: string}>  $rows
     */
    protected function buildMarkdown(Collection $rows): string
    {
        $lines = [];
        $lines[] = '# Hiring Hall — URL & route reference (for Ask Hill AI)';
        $lines[] = '';
        $lines[] = 'Auto-generated with `php artisan rag:export-routes`. Regenerate after route changes. When helping users, prefer **relative paths** from the site root (e.g. `/jobs`), not hard-coded domains.';
        $lines[] = '';
        $lines[] = '**Q:** How should I share links when assisting users on Hiring Hall?';
        $lines[] = '**A:** Give **relative URLs** starting with `/` (for example `/jobs`, `/FAQ`, `/dashboard`). Mention when a page requires sign-in or a specific role (applicant, employer, moderator). Dynamic segments like `{slug}` mean the user replaces that part with the real value from the UI.';
        $lines[] = '';
        $lines[] = '**Q:** Where is the help center and public job browsing?';
        $lines[] = '**A:** Help center: `/FAQ` (route `faq.index`). Job listings: `/jobs` (route `jobs`). Job detail: `/jobs/{slug}` where `{slug}` is the job’s slug (route `jobs.show`).';
        $lines[] = '';
        $lines[] = '**Q:** Where do users sign in, register, or use Ask Hill AI?';
        $lines[] = '**A:** Sign in: `/login` (route `login`). Register: `/register` (route `register`). Ask Hill AI opens from the signed-in navigation/drawer in the UI—users do not browse to an `/ai/...` URL for chat. Use `/FAQ` for public help articles anytime.';
        $lines[] = '';

        $grouped = $rows->groupBy(function (array $r): string {
            $p = trim($r['path'], '/');
            if ($p === '') {
                return '(root)';
            }

            return explode('/', $p)[0] ?: '(root)';
        })->sortKeys();

        foreach ($grouped as $segment => $group) {
            $lines[] = '---';
            $lines[] = '';
            $label = $segment === '(root)' ? 'site root and top-level' : '`/'.$segment.'` area';
            $lines[] = '**Q:** What Hiring Hall links exist in the '.$label.'?';
            $lines[] = '**A:**';
            foreach ($group as $r) {
                $hint = $this->hintFromRouteName($r['name']);
                $nm = $r['name'] !== '' ? ' — route `'.$r['name'].'`' : '';
                $lines[] = '- `'.$r['methods'].'` **`'.$r['path'].'`**'.$nm.$hint;
            }
            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    protected function hintFromRouteName(string $name): string
    {
        if ($name === '') {
            return '';
        }

        $n = str_replace(['.', '-', '_'], ' ', $name);

        return ' — *'.Str::limit($n, 80).'*.';
    }
}

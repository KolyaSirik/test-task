<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainRequest;
use App\Models\Domain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class DomainController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('domains/Index', [
            'domains' => $request->user()->domains()
                ->latest()
                ->get(),
            'breadcrumbs' => [
                ['title' => 'Domains', 'href' => route('domains.index')],
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('domains/Create', [
            'breadcrumbs' => [
                ['title' => 'Domains', 'href' => route('domains.index')],
                ['title' => 'Add Domain', 'href' => route('domains.create')],
            ],
        ]);
    }

    public function store(DomainRequest $request): RedirectResponse
    {
        $request->user()->domains()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Domain added successfully.')]);

        return to_route('domains.index');
    }

    public function show(Domain $domain): Response
    {
        Gate::authorize('view', $domain);

        return Inertia::render('domains/Show', [
            'domain' => $domain,
            'checks' => $domain->checks()
                ->latest()
                ->paginate(10),
            'breadcrumbs' => [
                ['title' => 'Domains', 'href' => route('domains.index')],
                ['title' => 'Logs', 'href' => route('domains.show', $domain)],
            ],
        ]);
    }

    public function edit(Domain $domain): Response
    {
        Gate::authorize('update', $domain);

        return Inertia::render('domains/Edit', [
            'domain' => $domain,
            'breadcrumbs' => [
                ['title' => 'Domains', 'href' => route('domains.index')],
                ['title' => 'Edit Domain', 'href' => route('domains.edit', $domain)],
            ],
        ]);
    }

    public function update(DomainRequest $request, Domain $domain): RedirectResponse
    {
        Gate::authorize('update', $domain);

        $domain->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Domain updated successfully.')]);

        return to_route('domains.index');
    }

    public function destroy(Domain $domain): RedirectResponse
    {
        Gate::authorize('delete', $domain);

        $domain->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Domain deleted successfully.')]);

        return to_route('domains.index');
    }
}

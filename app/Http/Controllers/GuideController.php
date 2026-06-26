<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GuideController extends Controller
{
    public function index(): View
    {
        return view('guides.index', [
            'posts' => $this->allPosts(),
        ]);
    }

    public function show(string $slug): View
    {
        $post = collect($this->allPosts())->firstWhere('slug', $slug);

        if ($post === null) {
            throw new NotFoundHttpException();
        }

        $contentPath = resource_path('content/guides/'.$slug.'.md');

        if (! File::exists($contentPath)) {
            throw new NotFoundHttpException();
        }

        $raw = File::get($contentPath);
        $html = Str::markdown($raw);

        return view('guides.show', [
            'post' => $post,
            'content' => $html,
        ]);
    }

    /**
     * @return list<array{slug: string, title: string, excerpt: string, date: string}>
     */
    private function allPosts(): array
    {
        return [
            [
                'slug' => 'when-to-refinance-home-loan-australia',
                'title' => 'When to Refinance Your Home Loan in Australia (2026)',
                'excerpt' => 'Signs it may be time to review your mortgage, what to compare, and how to avoid costly switching mistakes.',
                'date' => '2026-06-01',
            ],
            [
                'slug' => 'first-home-buyer-checklist-australia',
                'title' => 'First Home Buyer Checklist: Documents You Need',
                'excerpt' => 'A practical list of paperwork, savings evidence, and steps before you apply for your first home loan.',
                'date' => '2026-06-05',
            ],
            [
                'slug' => 'fixed-vs-variable-home-loans-australia',
                'title' => 'Fixed vs Variable: What Australian Borrowers Should Know',
                'excerpt' => 'How each rate type works, when fixed makes sense, and what flexibility really costs.',
                'date' => '2026-06-10',
            ],
            [
                'slug' => 'how-much-can-i-borrow-australia',
                'title' => 'How Much Can I Borrow? A Practical Guide',
                'excerpt' => 'Understand borrowing power, living expenses, buffers, and why online calculators only tell part of the story.',
                'date' => '2026-06-15',
            ],
            [
                'slug' => 'refinance-readiness-checklist',
                'title' => 'Refinance Readiness Checklist for Australian Homeowners',
                'excerpt' => 'Eight questions to answer before you refinance — from break costs to loan features and timing.',
                'date' => '2026-06-20',
            ],
            [
                'slug' => 'investment-property-loan-basics-australia',
                'title' => 'Investment Property Loan Basics for Australian Investors',
                'excerpt' => 'Interest-only vs principal, equity release, and structuring finance for long-term property plans.',
                'date' => '2026-06-22',
            ],
        ];
    }
}

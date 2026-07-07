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
        $posts = $this->allPosts();
        $post = collect($posts)->firstWhere('slug', $slug);

        if ($post === null) {
            throw new NotFoundHttpException();
        }

        $contentPath = resource_path('content/guides/'.$slug.'.md');

        if (! File::exists($contentPath)) {
            throw new NotFoundHttpException();
        }

        $raw = File::get($contentPath);
        $html = Str::markdown($raw);
        $html = (string) preg_replace('/^\s*<h1[^>]*>.*?<\/h1>\s*/is', '', $html, 1);
        $parsed = $this->parseGuideSections($html);

        $related = collect($posts)
            ->reject(fn (array $item): bool => $item['slug'] === $slug)
            ->take(3)
            ->values()
            ->all();

        return view('guides.show', [
            'post' => $post,
            'intro' => $parsed['intro'],
            'sections' => $parsed['sections'],
            'related' => $related,
        ]);
    }

    /**
     * @return array{intro: string, sections: list<array{title: string, body: string}>}
     */
    private function parseGuideSections(string $html): array
    {
        $parts = preg_split('/(<h2[^>]*>.*?<\/h2>)/is', $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        if ($parts === false || $parts === []) {
            return [
                'intro' => $html,
                'sections' => [],
            ];
        }

        $intro = '';
        $sections = [];
        $pendingTitle = null;

        foreach ($parts as $part) {
            if (preg_match('/^<h2[^>]*>(.*?)<\/h2>$/is', trim($part), $matches) === 1) {
                $pendingTitle = trim(html_entity_decode(strip_tags($matches[1])));

                continue;
            }

            $body = trim($part);

            if ($pendingTitle === null) {
                $intro .= $body;

                continue;
            }

            if ($body !== '') {
                $sections[] = [
                    'title' => $pendingTitle,
                    'body' => $body,
                ];
            }

            $pendingTitle = null;
        }

        return [
            'intro' => trim($intro),
            'sections' => $sections,
        ];
    }

    /**
     * @return list<array{slug: string, title: string, excerpt: string, date: string, category: string, image: string, image_alt: string}>
     */
    private function allPosts(): array
    {
        return [
            [
                'slug' => 'when-to-refinance-home-loan-australia',
                'title' => 'When to Refinance Your Home Loan in Australia (2026)',
                'excerpt' => 'Signs it may be time to review your mortgage, what to compare, and how to avoid costly switching mistakes.',
                'date' => '2026-06-01',
                'category' => 'Refinance',
                'image' => 'images/landing/refinance-advisor.jpg',
                'image_alt' => 'Homeowner reviewing refinance options',
            ],
            [
                'slug' => 'first-home-buyer-checklist-australia',
                'title' => 'First Home Buyer Checklist: Documents You Need',
                'excerpt' => 'A practical list of paperwork, savings evidence, and steps before you apply for your first home loan.',
                'date' => '2026-06-05',
                'category' => 'First home buyer',
                'image' => 'images/landing/home-loans-advisor.jpg',
                'image_alt' => 'First home buyer preparing loan documents',
            ],
            [
                'slug' => 'fixed-vs-variable-home-loans-australia',
                'title' => 'Fixed vs Variable: What Australian Borrowers Should Know',
                'excerpt' => 'How each rate type works, when fixed makes sense, and what flexibility really costs.',
                'date' => '2026-06-10',
                'category' => 'Home loans',
                'image' => 'images/landing/about-broker-team.jpg',
                'image_alt' => 'Broker explaining fixed and variable rate options',
            ],
            [
                'slug' => 'how-much-can-i-borrow-australia',
                'title' => 'How Much Can I Borrow? A Practical Guide',
                'excerpt' => 'Understand borrowing power, living expenses, buffers, and why online calculators only tell part of the story.',
                'date' => '2026-06-15',
                'category' => 'Borrowing power',
                'image' => 'images/landing/home-loans-advisor.jpg',
                'image_alt' => 'Borrower reviewing borrowing capacity',
            ],
            [
                'slug' => 'refinance-readiness-checklist',
                'title' => 'Refinance Readiness Checklist for Australian Homeowners',
                'excerpt' => 'Eight questions to answer before you refinance — from break costs to loan features and timing.',
                'date' => '2026-06-20',
                'category' => 'Refinance',
                'image' => 'images/landing/refinance-advisor.jpg',
                'image_alt' => 'Homeowner preparing to refinance',
            ],
            [
                'slug' => 'investment-property-loan-basics-australia',
                'title' => 'Investment Property Loan Basics for Australian Investors',
                'excerpt' => 'Interest-only vs principal, equity release, and structuring finance for long-term property plans.',
                'date' => '2026-06-22',
                'category' => 'Investment',
                'image' => 'images/landing/investment-property-advisor.jpg',
                'image_alt' => 'Investor reviewing property finance options',
            ],
        ];
    }
}

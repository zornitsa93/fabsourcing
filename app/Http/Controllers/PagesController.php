<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search', '');

        $pages = Page::when($search, function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            })
            ->orderBy('priority')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.index', compact('pages', 'search'));
    }

    public function create(): View
    {
        $languages = Language::active()->get();
        return view('admin.pages.create', compact('languages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'slug'           => 'required|string|max:120|unique:pages,slug',
            'title.fr'       => 'required|string|max:255',
            'title.en'       => 'nullable|string|max:255',
            'meta_title.fr'  => 'nullable|string|max:255',
            'meta_title.en'  => 'nullable|string|max:255',
            'meta_description.fr' => 'nullable|string|max:500',
            'meta_description.en' => 'nullable|string|max:500',
            'content.fr'          => 'nullable|string',
            'content.en'          => 'nullable|string',
            'hero_heading.fr'     => 'nullable|string|max:500',
            'hero_heading.en'     => 'nullable|string|max:500',
            'hero_lede.fr'        => 'nullable|string|max:1000',
            'hero_lede.en'        => 'nullable|string|max:1000',
            'services_lede.fr'    => 'nullable|string|max:1000',
            'services_lede.en'    => 'nullable|string|max:1000',
            'priority'            => 'nullable|integer|min:0|max:9999',
            'hero_image'          => 'nullable|image|max:2048',
            'why_image'           => 'nullable|image|max:4096',
            'why_eyebrow.fr'      => 'nullable|string|max:100',
            'why_eyebrow.en'      => 'nullable|string|max:100',
            'why_heading.fr'      => 'nullable|string|max:300',
            'why_heading.en'      => 'nullable|string|max:300',
            'why_caption.fr'      => 'nullable|string|max:200',
            'why_caption.en'      => 'nullable|string|max:200',
            'why_metric1_value'   => 'nullable|string|max:20',
            'why_metric1_label.fr'=> 'nullable|string|max:50',
            'why_metric1_label.en'=> 'nullable|string|max:50',
            'why_metric2_value'   => 'nullable|string|max:20',
            'why_metric2_label.fr'=> 'nullable|string|max:50',
            'why_metric2_label.en'=> 'nullable|string|max:50',
            'why_item1_title.fr'  => 'nullable|string|max:200',
            'why_item1_title.en'  => 'nullable|string|max:200',
            'why_item1_desc.fr'   => 'nullable|string|max:500',
            'why_item1_desc.en'   => 'nullable|string|max:500',
            'why_item2_title.fr'  => 'nullable|string|max:200',
            'why_item2_title.en'  => 'nullable|string|max:200',
            'why_item2_desc.fr'   => 'nullable|string|max:500',
            'why_item2_desc.en'   => 'nullable|string|max:500',
            'why_item3_title.fr'  => 'nullable|string|max:200',
            'why_item3_title.en'  => 'nullable|string|max:200',
            'why_item3_desc.fr'   => 'nullable|string|max:500',
            'why_item3_desc.en'   => 'nullable|string|max:500',
            'why_item4_title.fr'  => 'nullable|string|max:200',
            'why_item4_title.en'  => 'nullable|string|max:200',
            'why_item4_desc.fr'   => 'nullable|string|max:500',
            'why_item4_desc.en'   => 'nullable|string|max:500',
        ]);

        $data = [
            'slug'             => $request->input('slug'),
            'priority'         => $request->input('priority', 0),
            'published'        => $request->boolean('published'),
            'why_metric1_value'=> $request->input('why_metric1_value'),
            'why_metric2_value'=> $request->input('why_metric2_value'),
        ];

        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = $request->file('hero_image')
                ->store('pages', 'public');
        }

        if ($request->hasFile('why_image')) {
            $data['why_image'] = $request->file('why_image')
                ->store('pages', 'public');
        }

        $page = Page::create($data);

        foreach ([
            'title', 'meta_title', 'meta_description', 'content', 'hero_heading', 'hero_lede', 'services_lede',
            'why_eyebrow', 'why_heading', 'why_caption',
            'why_metric1_label', 'why_metric2_label',
            'why_item1_title', 'why_item1_desc',
            'why_item2_title', 'why_item2_desc',
            'why_item3_title', 'why_item3_desc',
            'why_item4_title', 'why_item4_desc',
        ] as $field) {
            foreach (['fr', 'en'] as $locale) {
                $value = $request->input("{$field}.{$locale}");
                if ($value !== null) {
                    $page->setTranslation($field, $locale, $value);
                }
            }
        }
        $page->save();

        if ($request->input('action') === 'continue') {
            return redirect()->route('pages.edit', $page)
                ->with('success', 'Страницата е създадена. Продължете редактирането.');
        }

        return redirect()->route('pages.index')
            ->with('success', 'Страница „' . $page->getTranslation('title', 'fr', false) . '" е създадена.');
    }

    public function show(Page $page): RedirectResponse
    {
        return redirect()->route('pages.edit', $page);
    }

    public function edit(Page $page): View
    {
        $languages = Language::active()->get();
        return view('admin.pages.edit', compact('page', 'languages'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $request->validate([
            'slug'           => 'required|string|max:120|unique:pages,slug,' . $page->id,
            'title.fr'       => 'required|string|max:255',
            'title.en'       => 'nullable|string|max:255',
            'meta_title.fr'  => 'nullable|string|max:255',
            'meta_title.en'  => 'nullable|string|max:255',
            'meta_description.fr' => 'nullable|string|max:500',
            'meta_description.en' => 'nullable|string|max:500',
            'content.fr'          => 'nullable|string',
            'content.en'          => 'nullable|string',
            'hero_heading.fr'     => 'nullable|string|max:500',
            'hero_heading.en'     => 'nullable|string|max:500',
            'hero_lede.fr'        => 'nullable|string|max:1000',
            'hero_lede.en'        => 'nullable|string|max:1000',
            'services_lede.fr'    => 'nullable|string|max:1000',
            'services_lede.en'    => 'nullable|string|max:1000',
            'priority'            => 'nullable|integer|min:0|max:9999',
            'hero_image'          => 'nullable|image|max:2048',
            'why_image'           => 'nullable|image|max:4096',
            'why_eyebrow.fr'      => 'nullable|string|max:100',
            'why_eyebrow.en'      => 'nullable|string|max:100',
            'why_heading.fr'      => 'nullable|string|max:300',
            'why_heading.en'      => 'nullable|string|max:300',
            'why_caption.fr'      => 'nullable|string|max:200',
            'why_caption.en'      => 'nullable|string|max:200',
            'why_metric1_value'   => 'nullable|string|max:20',
            'why_metric1_label.fr'=> 'nullable|string|max:50',
            'why_metric1_label.en'=> 'nullable|string|max:50',
            'why_metric2_value'   => 'nullable|string|max:20',
            'why_metric2_label.fr'=> 'nullable|string|max:50',
            'why_metric2_label.en'=> 'nullable|string|max:50',
            'why_item1_title.fr'  => 'nullable|string|max:200',
            'why_item1_title.en'  => 'nullable|string|max:200',
            'why_item1_desc.fr'   => 'nullable|string|max:500',
            'why_item1_desc.en'   => 'nullable|string|max:500',
            'why_item2_title.fr'  => 'nullable|string|max:200',
            'why_item2_title.en'  => 'nullable|string|max:200',
            'why_item2_desc.fr'   => 'nullable|string|max:500',
            'why_item2_desc.en'   => 'nullable|string|max:500',
            'why_item3_title.fr'  => 'nullable|string|max:200',
            'why_item3_title.en'  => 'nullable|string|max:200',
            'why_item3_desc.fr'   => 'nullable|string|max:500',
            'why_item3_desc.en'   => 'nullable|string|max:500',
            'why_item4_title.fr'  => 'nullable|string|max:200',
            'why_item4_title.en'  => 'nullable|string|max:200',
            'why_item4_desc.fr'   => 'nullable|string|max:500',
            'why_item4_desc.en'   => 'nullable|string|max:500',
        ]);

        $data = [
            'slug'             => $request->input('slug'),
            'priority'         => $request->input('priority', 0),
            'published'        => $request->boolean('published'),
            'why_metric1_value'=> $request->input('why_metric1_value'),
            'why_metric2_value'=> $request->input('why_metric2_value'),
        ];

        if ($request->boolean('remove_hero_image') && $page->hero_image) {
            Storage::disk('public')->delete($page->hero_image);
            $data['hero_image'] = null;
        } elseif ($request->hasFile('hero_image')) {
            if ($page->hero_image) {
                Storage::disk('public')->delete($page->hero_image);
            }
            $data['hero_image'] = $request->file('hero_image')
                ->store('pages', 'public');
        }

        if ($request->boolean('remove_why_image') && $page->why_image) {
            Storage::disk('public')->delete($page->why_image);
            $data['why_image'] = null;
        } elseif ($request->hasFile('why_image')) {
            if ($page->why_image) {
                Storage::disk('public')->delete($page->why_image);
            }
            $data['why_image'] = $request->file('why_image')
                ->store('pages', 'public');
        }

        $page->update($data);

        foreach ([
            'title', 'meta_title', 'meta_description', 'content', 'hero_heading', 'hero_lede', 'services_lede',
            'why_eyebrow', 'why_heading', 'why_caption',
            'why_metric1_label', 'why_metric2_label',
            'why_item1_title', 'why_item1_desc',
            'why_item2_title', 'why_item2_desc',
            'why_item3_title', 'why_item3_desc',
            'why_item4_title', 'why_item4_desc',
        ] as $field) {
            foreach (['fr', 'en'] as $locale) {
                $value = $request->input("{$field}.{$locale}");
                if ($value !== null) {
                    $page->setTranslation($field, $locale, $value);
                }
            }
        }
        $page->save();

        if ($request->input('action') === 'continue') {
            return redirect()->route('pages.edit', $page)
                ->with('success', 'Записано.');
        }

        return redirect()->route('pages.index')
            ->with('success', 'Страницата е обновена.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        if ($page->hero_image) {
            Storage::disk('public')->delete($page->hero_image);
        }
        if ($page->why_image) {
            Storage::disk('public')->delete($page->why_image);
        }

        $title = $page->getTranslation('title', 'fr', false);
        $page->delete();

        return redirect()->route('pages.index')
            ->with('success', 'Страница „' . $title . '" е изтрита.');
    }
}

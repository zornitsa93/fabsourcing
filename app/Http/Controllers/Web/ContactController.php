<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Mail\ContactSubmissionMail;
use App\Models\ContactSubmission;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends WebPagesController
{
    public function index(Request $request, string $lang = 'fr')
    {
        $categoryPrefill = null;

        $paramKey     = $lang === 'fr' ? 'categorie' : 'category';
        $categorySlug = $request->input($paramKey, '');

        if ($categorySlug) {
            $slugColumn = $lang === 'en' ? 'slug_en' : 'slug';
            $category   = ProductCategory::where($slugColumn, $categorySlug)
                ->where('published', true)
                ->first();

            if ($category) {
                $categoryPrefill = $category->getTranslation('name', $lang, false)
                                ?: $category->getTranslation('name', 'fr', false);
            }
        }

        return view('web.contact', array_merge(
            $this->commonForWebPages($lang),
            ['categoryPrefill' => $categoryPrefill]
        ));
    }

    public function send(Request $request, string $lang = 'fr')
    {
        $request->validate([
            'name'    => 'required|string|max:120',
            'company' => 'nullable|string|max:120',
            'email'   => 'required|email|max:180',
            'phone'   => 'nullable|string|max:30',
            'message' => 'required|string|max:3000',
        ]);

        $submission = ContactSubmission::create(
            $request->safe()->only(['name', 'company', 'email', 'phone', 'message'])
        );

        try {
            Mail::to(config('mail.from.address'))->send(new ContactSubmissionMail($submission));
        } catch (\Throwable) {
            // Email failure should not block the user's confirmation
        }

        session()->flash('contact_sent', true);

        return redirect()->route('contact', $lang);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SupportTopic;
use Illuminate\Http\Request;

/**
 * Class SupportController
 * @package App\Http\Controllers
 */
class SupportController extends Controller
{
    /**
     *  GET user contact page
     *
     *  @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function authContact() {
        return view('support.auth_contact', [
            'breadcrumbs' => [
                route('home', [], false) => 'Overview',
                route('auth_contact', [], false) => 'Contact Ternio & Support'
            ],
            'support_topics' => SupportTopic::all()
        ]);
    }

    /**
     * GET ajax request to return support topics based on search term.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxDoSearchSupportTopics(Request $request) {
        return response()->json([
            'support_topics' => SupportTopic::whereRaw("fts_doc @@ to_tsquery(quote_literal(?))",
                [$request->get('keyword')])->get()
        ]);
    }
}

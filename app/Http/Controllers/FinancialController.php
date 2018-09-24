<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use Illuminate\Http\Request;
use Auth;

/**
 * Class FinancialController
 * @package App\Http\Controllers
 */
class FinancialController extends Controller
{
    /**
     * GET payments home screen.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $payments_results = $this->_getPayments();

        return view('financial.index', [
            'total_count' => $payments_results['total_count'],
            'payments' => $payments_results['results'],
            'sub_title' => 'Dashboard - Payment History',
            'page_title' => 'Payments'
        ]);
    }

    /**
     * GET updated list of payments
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshList(Request $request) {
        $payments_results = $this->_getPayments($request->query('current_page'), $request->query('rows_per_page'));
        return response()->json($payments_results);
    }

    /**
     * Returns payments of the current user's company.
     *
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    private function _getPayments(int $page = 1, int $limit = 50) {
        $query = Payment::where('company_id', '=', Auth::getCurrentUser()->company_id)
            ->orderBy('created_at', 'DESC');

        $count = $query->count();

        $results = $query
            ->offset(($page - 1) * $limit)
            ->limit($limit)
            ->get();

        return ['results' => $results, 'total_count' => $count];
    }
}
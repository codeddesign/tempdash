<?php

namespace App\Http\Controllers;

use App\Company;

class CFController extends Controller
{

    /**
     * Gets the company account ID based on the passed in domain
     *
     * @param $domain
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanyAccountIdByDomain($domain)
    {
        // Locate company by domain
        if ($company = Company::where('domain', '=', trim(strtolower($domain)))->first())
        {
            return response()->json(['status' => 200, 'account_id' => $company->account_id]);
        } else {
            return response()->json(['status' => 404, 'error' => 'Company not found.']);
        }
    }
}
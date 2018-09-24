<?php
/**
 * Created by PhpStorm.
 * User: creeves
 * Date: 8/31/18
 * Time: 3:06 PM
 */

namespace App\Http\Controllers;


class PayeeController
{
    /**
     * GET payee management page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function management()
    {

        // Find other users under the same company as the logged in user
        $current_user = Auth::getCurrentUser();

        // Get permissions
        $access_control = config('access_control');
        $permissions = [];
        foreach ($access_control as $key => $value) {
            $label = str_replace('_', ' ', $value);
            $permissions[] = [
                'label' => ucfirst($label) . ' access.',
                'machine_name' => $value
            ];
        }

        $coworkers_results = $this->_getCoworkersOfUser($current_user);

        return view('user.management', [
            'company_employees' => $coworkers_results['results'],
            'total_num_of_employees' => $coworkers_results['total_count'],
            'permissions' => $permissions,
            'departments' => $current_user->getAllCoWorkerDepartments(),
            'sub_title' => 'Dashboard - Users',
            'page_title' => 'User Management'
        ]);
    }
}
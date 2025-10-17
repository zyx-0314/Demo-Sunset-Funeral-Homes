<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class Admin extends BaseController
{
    public function showDashboardPage()
    {
        try {
            $usersModel = new UsersModel();

            $activeClientsCount = $usersModel->where('type', 'client')->where('account_status', 1)->countAllResults();
        } catch (\Exception $error) {
            $activeClientsCount = "Server Issue: " . $error;
        }

        return view('admin/dashboard', [
            'activeClientsCount' => $activeClientsCount,
            'requestsCount' => "Under construction", // TODO: fetch from inquiries model
            'servicesCount' => "Under construction"  // TODO: fetch from services model
        ]);
    }
}

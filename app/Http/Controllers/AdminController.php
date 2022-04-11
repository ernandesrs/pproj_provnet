<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // middleware para autenticação de administradores
        $this->middleware("can:admin-panel-access");
    }
}

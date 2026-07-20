<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

// Filtre : verifie que l'admin est connecte
class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Ne pas filtrer la page login et logout
        $uri = $request->getUri()->getPath();
        if (strpos($uri, 'admin/login') !== false || strpos($uri, 'admin/logout') !== false) {
            return;
        }

        // Si pas connecte, redirige vers login
        if (! session()->get('admin_logged_in')) {
            return redirect()->to(site_url('admin/login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien a faire apres
    }
}

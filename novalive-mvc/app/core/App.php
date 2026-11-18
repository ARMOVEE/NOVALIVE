<?php
/**
 * Router & Application Runner
 * Memetakan URL ke Controller, Method, dan Parameter
 */

class App {
    // Properti controller sengaja tanpa tipe string karena setelah diinstansiasi akan berisi objek Controller
    protected $controller = 'HomeController';
    protected string $method = 'index';
    protected array $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Tangani Custom Clean Routes (Bagian 5)
        if (empty($url) || (isset($url[0]) && $url[0] === '')) {
            $this->controller = 'HomeController';
            $this->method = 'index';
            $this->params = [];
        } elseif ($url[0] === 'login') {
            $this->controller = 'AuthController';
            $this->method = 'login';
            $this->params = array_slice($url, 1);
        } elseif ($url[0] === 'kategori') {
            $this->controller = 'CategoriesController';
            $this->method = 'index';
            $this->params = array_slice($url, 1);
        } elseif ($url[0] === 'kota') {
            $this->controller = 'CitiesController';
            if (isset($url[1]) && $url[1] !== '') {
                $this->method = 'show';
                $this->params = [$url[1]];
            } else {
                $this->method = 'index';
                $this->params = [];
            }
        } elseif ($url[0] === 'dashboard') {
            $this->controller = 'DashboardController';
            if (isset($url[1]) && in_array($url[1], ['orders', 'events'], true)) {
                $this->method = $url[1];
                $this->params = array_slice($url, 2);
            } else {
                $this->method = 'index';
                $this->params = array_slice($url, 1);
            }
        } elseif ($url[0] === 'events') {
            $this->controller = 'EventsController';
            if (isset($url[1]) && $url[1] !== '') {
                $this->method = $url[1];
                $this->params = array_slice($url, 2);
            } else {
                $this->method = 'index';
                $this->params = [];
            }
        } else {
            // 2. Format Standar /{controller}/{method}/{param}
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = ROOT_PATH . '/app/controllers/' . $controllerName . '.php';

            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                self::notFound("Controller [{$url[0]}] tidak ditemukan.");
            }

            if (isset($url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }

            $this->params = $url ? array_values($url) : [];
        }

        // Instansiasi Controller
        $controllerFile = ROOT_PATH . '/app/controllers/' . $this->controller . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
        }

        if (!class_exists($this->controller)) {
            self::notFound("Kelas controller [{$this->controller}] tidak ditemukan.");
        }

        $this->controller = new $this->controller();

        // Validasi Method Controller
        if (!method_exists($this->controller, $this->method)) {
            self::notFound("Aksi [{$this->method}] tidak ditemukan.");
        }
    }

    /**
     * Menjalankan action controller dengan parameter
     */
    public function run(): void {
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Mem-parsing string URL dari parameter $_GET['url']
     */
    protected function parseUrl(): array {
        if (isset($_GET['url'])) {
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }

    /**
     * Menampilkan halaman 404 berlayout
     */
    public static function notFound(string $message = 'Halaman yang Anda cari tidak ada atau sudah dipindahkan.'): void {
        Security::abort(404, $message);
    }
}

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
            $seg1 = $url[1] ?? '';
            $seg2 = $url[2] ?? '';
            $seg3 = $url[3] ?? '';

            if ($seg1 === 'events') {
                if ($seg2 === 'stock') {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $seg3 !== '') {
                        $this->method = 'updateStock';
                        $this->params = [$seg3];
                    } else {
                        $this->method = 'stock';
                        $this->params = array_slice($url, 3);
                    }
                } else {
                    $this->method = 'events';
                    $this->params = array_slice($url, 2);
                }
            } elseif ($seg1 === 'categories') {
                if ($seg2 === 'store') {
                    $this->method = 'storeCategory';
                    $this->params = [];
                } elseif ($seg2 === 'update' && $seg3 !== '') {
                    $this->method = 'updateCategory';
                    $this->params = [$seg3];
                } elseif ($seg2 === 'delete' && $seg3 !== '') {
                    $this->method = 'deleteCategory';
                    $this->params = [$seg3];
                } else {
                    $this->method = 'categories';
                    $this->params = array_slice($url, 2);
                }
            } elseif ($seg1 === 'cities') {
                if ($seg2 === 'store') {
                    $this->method = 'storeCity';
                    $this->params = [];
                } elseif ($seg2 === 'update' && $seg3 !== '') {
                    $this->method = 'updateCity';
                    $this->params = [$seg3];
                } elseif ($seg2 === 'delete' && $seg3 !== '') {
                    $this->method = 'deleteCity';
                    $this->params = [$seg3];
                } else {
                    $this->method = 'cities';
                    $this->params = array_slice($url, 2);
                }
            } elseif ($seg1 === 'orders') {
                $seg4 = $url[4] ?? '';
                if ($seg2 === 'checkin') {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->method = 'processCheckin';
                    } else {
                        $this->method = 'checkin';
                    }
                    $this->params = array_slice($url, 3);
                } elseif ($seg2 === 'refunds') {
                    if ($seg3 === 'approve' && $seg4 !== '') {
                        $this->method = 'approveRefund';
                        $this->params = [$seg4];
                    } elseif ($seg3 === 'reject' && $seg4 !== '') {
                        $this->method = 'rejectRefund';
                        $this->params = [$seg4];
                    } elseif ($seg3 === 'create') {
                        $this->method = 'createRefund';
                        $this->params = array_slice($url, 4);
                    } else {
                        $this->method = 'refunds';
                        $this->params = array_slice($url, 3);
                    }
                } elseif ($seg2 === 'tickets') {
                    if ($seg3 === 'resend' && $seg4 !== '') {
                        $this->method = 'resendTicket';
                        $this->params = [$seg4];
                    } elseif ($seg3 !== '') {
                        $this->method = 'showTicket';
                        $this->params = [$seg3];
                    } else {
                        $this->method = 'tickets';
                        $this->params = array_slice($url, 3);
                    }
                } else {
                    $this->method = 'orders';
                    $this->params = array_slice($url, 2);
                }
            } else {
                $this->method = ($seg1 !== '' && method_exists('DashboardController', $seg1)) ? $seg1 : 'index';
                $this->params = array_slice($url, 2);
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

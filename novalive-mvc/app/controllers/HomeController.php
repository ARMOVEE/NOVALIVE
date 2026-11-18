<?php
/**
 * HomeController
 * Menangani halaman utama (beranda) NOVA LIVE
 */

class HomeController extends Controller {
    public function index(): void {
        $eventModel = $this->model('Event');
        $categoryModel = $this->model('Category');
        $cityModel = $this->model('City');

        $eventsSeru = $eventModel->seru(10);
        $eventsPopular = $eventModel->popular(4);
        $categories = $categoryModel->all();
        $cities = $cityModel->all();

        $this->view('home/index', [
            'pageTitle'     => 'NOVA LIVE — Platform Tiket Event Indonesia',
            'eventsSeru'    => $eventsSeru,
            'eventsPopular' => $eventsPopular,
            'categories'    => $categories,
            'cities'        => $cities
        ]);
    }
}

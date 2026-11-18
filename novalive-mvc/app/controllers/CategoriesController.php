<?php
/**
 * CategoriesController
 * Menangani daftar 8 kategori event publik
 */

class CategoriesController extends Controller {
    /**
     * Menampilkan daftar 8 kategori event beserta jumlah event aktif di tiap kategori
     */
    public function index(): void {
        $categoryModel = $this->model('Category');
        $categories = $categoryModel->all();

        $this->view('categories/index', [
            'pageTitle'  => 'Kategori Event — NOVA LIVE',
            'categories' => $categories
        ]);
    }
}

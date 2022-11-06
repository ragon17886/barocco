<?php

namespace App\Http\Controllers\Shop;

use App\Models\Filter;
use App\Models\Category;
use App\Helpers\UrlHelper;
use Illuminate\Http\Request;
use App\Services\CatalogService;
use App\Http\Requests\FilterRequest;
use App\Services\GoogleTagManagerService;

class CatalogController extends BaseController
{
    /**
     * CatalogController constructor.
     */
    public function __construct(
        Request $request,
        private GoogleTagManagerService $gtmService,
        private CatalogService $catalogService,
    ) {
        parent::__construct($request);
    }

    /**
     * Render products for next page
     */
    public function ajaxNextPage(): array
    {
        $this->request->validate(['cursor' => 'required']);

        $products = $this->catalogService->getNextProducts();

        $renderedProducts = [];
        foreach ($products as $product) {
            $renderedProducts[] = view('shop.catalog-product', compact('product'))->render();
        }

        return [
            'rendered_products' => $renderedProducts,
            'cursor' => optional($products->nextCursor())->encode(),
            'has_more' => $products->hasMorePages(),
            'data_layers' => $this->gtmService->getForCatalogArrays(
                $products, $this->request->input('category'), $this->request->input('search')
            ),
        ];
    }

    /**
     * Show catalog page
     */
    public function show(FilterRequest $filterRequest)
    {
        $sort = $filterRequest->getSorting();
        $currentFilters = $filterRequest->getFilters();
        $searchQuery = $filterRequest->input('search');
        UrlHelper::setCurrentFilters($currentFilters);
        // dump($currentFilters);

        $products = $this->catalogService->getProducts($currentFilters, $sort, $searchQuery);

        $sortingList = [
            'rating' => 'по популярности',
            'newness' => 'новинки',
            'price-up' => 'по возрастанию цены',
            'price-down' => 'по убыванию цены',
        ];

        $category = end($currentFilters[Category::class])->getFilterModel();
        $badges = $this->catalogService->getFilterBadges($currentFilters, $searchQuery);

        $this->gtmService->setForCatalog($products, $category, $searchQuery);

        return view('shop.catalog', [
            'products' => $products,
            'category' => $category,
            'currentFilters' => $currentFilters,
            'badges' => $badges,
            'filters' => Filter::all(),
            'sort' => $sort,
            'sortingList' => $sortingList,
            'searchQuery' => $searchQuery,
        ]);
    }
}

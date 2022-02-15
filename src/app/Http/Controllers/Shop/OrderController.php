<?php

namespace App\Http\Controllers\Shop;

use App\Facades\Cart;
use App\Facades\Sale;
use App\Models\Orders\Order;
use App\Models\CartData;
use App\Events\OrderCreated;
use Illuminate\Http\Request;
use Database\Seeders\ProductSeeder;
use App\Services\OldSiteSyncService;
use Illuminate\Support\Facades\Auth;
use App\Contracts\OrderServiceIntarface;
use App\Http\Requests\Order\SyncRequest;
use App\Http\Requests\Order\StoreRequest;
use App\Services\GoogleTagManagerService;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.orders', [
            'orders' => Order::with(['country', 'status:key,name_for_user'])
                ->where('user_id', Auth::id())->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request
     * @param  GoogleTagManagerService $gtmService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request, GoogleTagManagerService $gtmService)
    {
        if ($request->isOneClick()) {
            $cart = Cart::make();
            $items = [];
            foreach ($request->input('sizes') as $sizeId => $state) {
                $items[] = CartData::make([
                    'product_id' => (int)$request->input('product_id'),
                    'size_id' => $sizeId,
                    'count' => 1,
                ]);
            }
            $cart->setRelation('items', new EloquentCollection($items));
        } else {
            $cart = Cart::withData();
            abort_if(empty($cart['items']) || $cart->items->isEmpty(), 404);
        }
        Sale::applyForCart($cart);

        $order = app(OrderServiceIntarface::class)->store($request, $cart);

        $gtmService->setPurchaseFlashEvent($order->items, $order->id, $request->isOneClick());

        Cart::clear();

        event(new OrderCreated($order));

        return redirect()->route('cart-final')->with('order_id', $order->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get print view
     *
     * @param Order $order
     * @return \Illuminate\Contracts\View\View
     */
    public function print(Order $order)
    {
        return view('admin.order-print', compact('order'));
    }

    /**
     * Sync order with another DB
     *
     * @param SyncRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(SyncRequest $request)
    {
        $oldId = (int)$request->input('id');
        $oldSizes = (new ProductSeeder)->attributesList['sizes']['new_id'];
        $cart = Cart::make();
        $items = [];
        foreach ($request->input('items') as $item) {
            $item['size_id'] = $oldSizes[$item['size']] ?? 1;
            $items[] = CartData::make($item);
        }
        $cart->setRelation('items', new EloquentCollection($items));

        try {
            $order = app(OrderServiceIntarface::class)
                ->store($request, $cart);
        } catch (\Throwable $th) {
            abort(OldSiteSyncService::errorResponse($th->getMessage()));
        }

        return OldSiteSyncService::successResponse([$oldId => $order->id]);
    }
}

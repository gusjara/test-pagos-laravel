<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SantiGraviano\LaravelMercadoPago\Facades\MP;

class ComprasController extends Controller
{
    protected function generatePaymentGateway($paymentMethod, Order $order) : string{
        $method = new \App\PaymentMethods\MercadoPago;

        return $method->setupPaymentAndGetRedirectURL($order);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function comprar(){

        $datos_compra = [
            'items' => [
                [
                    'id' => 12,
                    'category_id' => 'Categoria de la compra',
                    'title' => 'Compra de xxxx cantidad de cosas',
                    'description' => 'descripcion de la compra o el item',
                    'picture_url' => 'https://www.cocinayvino.com/wp-content/uploads/2017/05/comidas-m%C3%A1s-populares-del-mundo.jpg',// imagen del producto
                    'quantity' => 1, //cantidad, 1 en este caso
                    'currency_id' => 'ARS', // moneda
                    'unit_price' => 20000 // precio unitario
                ]
            ],
        ];

        // $preference = MP::create_preference($datos_compra);

        // return dd($preference);
        
        try {
            $preference = MP::create_preference($datos_compra);
            // return dd($preference);
            return redirect()->to($preference['response']['sandbox_init_point']);
        } catch (Exception $e){
            dd($e->getMessage());
        }
    }

    public function suscripcion(){
    $preapproval_data = [
      'payer_email' => 'agaramaeste_mp@gmail.com',
      'back_url' => 'http://lalalal.com/preapproval',
      'reason' => 'Subscripción a paquete premium de $xxx pesos',
      'external_reference' => 1,//$subscription->id,
      'auto_recurring' => [
        'frequency' => 1,
        'frequency_type' => 'months',
        'transaction_amount' => 15,
        'currency_id' => 'ARS',
        'start_date' => Carbon::now()->addHour()->format('Y-m-d\TH:i:s.BP'),
        'end_date' => Carbon::now()->addMonth()->format('Y-m-d\TH:i:s.BP'),
      ],
    ];

    $preapproval = MP::create_preapproval_payment($preapproval_data);

    // return dd($preapproval);
    return redirect()->to($preapproval['response']['sandbox_init_point']);
  }

    /// con el sdk dx-php
    public function createOrder(Request $request)
    {
        $allowedPaymentMethods = config('payment-methods.enabled');

        $request->validate([
            'payment_method' => [
                'required',
                Rule::in($allowedPaymentMethods),
            ],
            'terms' => 'accepted',
        ]);

        // $order = $this->setUpOrder($preOrder, $request);
        $datos_compra = [
            'items' => [
                [
                    'id' => 12,
                    'category_id' => 'Categoria de la compra',
                    'title' => 'Compra de xxxx cantidad de cosas',
                    'description' => 'descripcion de la compra o el item',
                    'picture_url' => 'https://www.cocinayvino.com/wp-content/uploads/2017/05/comidas-m%C3%A1s-populares-del-mundo.jpg',// imagen del producto
                    'quantity' => 1, //cantidad, 1 en este caso
                    'currency_id' => 'ARS', // moneda
                    'unit_price' => 10 // precio unitario
                ]
            ],
        ];

        dd($datos_compra);
        // $this->notify($order);    
        $url = $this->generatePaymentGateway($request->get('payment_method'), $datos_compra); 
        return redirect()->to($url);
    } 
}

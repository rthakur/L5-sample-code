<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Currency;
use App\Models\PriceInterval;
use App\Models\MonthlyFeeInterval;
use Auth, Session;

class DefaultController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
   return Redirect(SITE_LANG.'/admin/agency');
  }
  
  public function accessAccount($id)
  {
      if (Auth::check() && Auth::user()->role->id == '1' || Session::has('auth_access')){
        if (Auth::user()->role->id == '1')
          Session::put('auth_access',Auth::id());
          
        Auth::loginUsingId($id);
      }
      
      if (Auth::user()->role->id == '1')
        return redirect(SITE_LANG.'/admin/manageuser');
      else
       return redirect(SITE_LANG.'/account/profile');
  }
  
  public function getCurrency()
  {
      $data['priceIntervals'] = PriceInterval::select('currencies.id', 'currencies.currency', 'price_intervals.intervals')->join('currencies', 'price_intervals.currency_id', '=', 'currencies.id')->simplePaginate(200);
      $data['monthlyFeeIntervals'] = MonthlyFeeInterval::select('currencies.id', 'currencies.currency', 'monthly_fee_intervals.intervals')->join('currencies', 'monthly_fee_intervals.currency_id', '=', 'currencies.id')->simplePaginate(200);
      return view('admin.currencies.intervals.index', $data);
  }
  
  public function addCurrencyIntervals($type)
  {
      if ($type != 1 && $type != 2)
        return view('errors.403');
        
      $data['type'] = $type;
      $joinTable = ($type == 1) ? 'price_intervals' : 'monthly_fee_intervals';
      $data['allCurrencies'] = Currency::select('currencies.id','currencies.currency')->leftJoin($joinTable, function($join) use($joinTable){
                                              $join->on('currencies.id', '=', $joinTable .'.currency_id');
                                          })->whereRaw($joinTable .'.currency_id IS NULL')->get();
      return view('admin.currencies.intervals.form', $data);
  }
  
  public function editCurrencyIntervals($currencyId, $type)
  {
      $currency = Currency::find($currencyId);
      if (!$currency)
        return view('errors.403');
      
      $intervalType = [ 1 => PriceInterval::class, 2 => MonthlyFeeInterval::class ];
      $data['intervalObj'] = $intervalType[$type]::where('currency_id', $currencyId)->first();
      $data['type'] = $type;
      $data['allCurrencies'] = Currency::all();
      $data['selectedCurrency'] = $currency;
      return view('admin.currencies.intervals.form', $data);
  }
  
  public function saveCurrencyIntervals(Request $request)
  {
      $validate = validator($request->all(), [
          'currency' => 'required|exists:currencies,id',
          'type' => 'required',
          'intervals' => 'required|regex:/^[0-9.,]+$/'
      ]);
      
      if ($validate->fails())
          return back()->withInput()->withErrors($validate);
      
      $intervalType = [ 1 => PriceInterval::class, 2 => MonthlyFeeInterval::class ];
      $intervalObj = $intervalType[$request->type]::firstOrCreate(['currency_id' => $request->currency]);
      
      $intervals = explode(',', $request->intervals);
      sort($intervals);
      $intervals = implode(',', $intervals);
      
      $intervalObj->intervals = $intervals;
      $intervalObj->save();
      
      return redirect(SITE_LANG.'/admin/intervals');
  }
  
  public function currencies()
  {
      $allCurrencies = Currency::orderBy('currency_order', 'asc')->get();
      $data['orderedCurrencies'] = $allCurrencies->where('currency_order', '!=', null);
      $data['unorderedCurrencies'] = $allCurrencies->where('currency_order', null);
      return view('admin.currencies.index', $data);
  }
  
  public function currenciesChangeOrder(Request $request)
  {
      if(!isset($request->orderData) || !count($request->orderData) || empty($request->orderData))
        return back()->with('error', trans('common.NotUpdatedAnything'));
        
      $orderData = json_decode(base64_decode($request->orderData));
      
      foreach($orderData as $orderN => $cOrder) {
          Currency::where('id', $cOrder)->update(['currency_order' => ++$orderN]);
      }
      
      return redirect(SITE_LANG.'/admin/currencies/');
  }
  
}

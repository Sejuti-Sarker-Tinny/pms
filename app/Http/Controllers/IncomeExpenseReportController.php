<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\PurchaseInfo;
use App\Models\SaleInfo;

class IncomeExpenseReportController extends Controller{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(){
        $month = Carbon::now()->format('m');
        $fullMonth = Carbon::now()->format('F');
        $year = Carbon::now()->format('Y');
        $incomes = SaleInfo::where('payment_status', 'paid')->whereMonth('sale_date','=', $month)->whereYear('sale_date','=', $year)->get();
        $incomeTotal = SaleInfo::where('payment_status', 'paid')->whereMonth('sale_date','=', $month)->whereYear('sale_date','=', $year)->sum('product_total_price_after_discount');
        $expenses = PurchaseInfo::whereMonth('purchase_date','=', $month)->whereYear('purchase_date','=', $year)->get();
        $expenseTotal = PurchaseInfo::whereMonth('purchase_date','=', $month)->whereYear('purchase_date','=', $year)->sum('product_total_price');

        return view('admin.report.income-expense-report',compact('month','fullMonth','year','incomes','incomeTotal','expenses','expenseTotal'));
    }

    public function search($from,$to){
        $income = SaleInfo::where('payment_status', 'paid')->whereBetween('sale_date', [$from, $to])->get();
        $incomeTotal = SaleInfo::where('payment_status', 'paid')->whereBetween('sale_date', [$from, $to])->sum('product_total_price_after_discount');
        $expense = PurchaseInfo::whereBetween('purchase_date', [$from, $to])->get();
        $expenseTotal = PurchaseInfo::whereBetween('purchase_date', [$from, $to])->sum('product_total_price');
        return view('admin.report.search-income-expense-report', compact('income','incomeTotal','expense','expenseTotal','from','to'));
    }

        
    public function custom_report(){
        $month = Carbon::now()->format('m');
        $fullMonth = Carbon::now()->format('F');
        $year = Carbon::now()->format('Y');
        $incomes = SaleInfo::where('payment_status', 'paid')->whereMonth('sale_date','=', $month)->whereYear('sale_date','=', $year)->get();
        $incomeTotal = SaleInfo::where('payment_status', 'paid')->whereMonth('sale_date','=', $month)->whereYear('sale_date','=', $year)->sum('product_total_price_after_discount');
        $expenses = PurchaseInfo::whereMonth('purchase_date','=', $month)->whereYear('purchase_date','=', $year)->get();
        $expenseTotal = PurchaseInfo::whereMonth('purchase_date','=', $month)->whereYear('purchase_date','=', $year)->sum('product_total_price');

        return view('admin.report.customize-income-expense-report',compact('month','fullMonth','year','incomes','incomeTotal','expenses','expenseTotal'));
    }
}

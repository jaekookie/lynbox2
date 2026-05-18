<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Box;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $totalUsers = User::where('role', 'customer')->count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $totalRevenue = Invoice::where('status', 'paid')->sum('amount');
        $pausedSubscriptions = Subscription::where('status', 'paused')->count();
        $cancelledSubscriptions = Subscription::where('status', 'cancelled')->count();

        $churnRate = $this->calculateChurnRate();
        $monthlyRevenue = $this->getMonthlyRevenue();
        $topBoxes = $this->getTopBoxes();
        $recentSubscriptions = Subscription::latest()->with('user', 'box')->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeSubscriptions',
            'totalRevenue',
            'pausedSubscriptions',
            'cancelledSubscriptions',
            'churnRate',
            'monthlyRevenue',
            'topBoxes',
            'recentSubscriptions'
        ));
    }

    public function analytics()
    {
        $this->authorizeAdmin();

        $monthlyData = $this->getMonthlyAnalytics();
        $categoryBreakdown = $this->getCategoryBreakdown();
        $userRetention = $this->getUserRetention();

        return view('admin.analytics', compact(
            'monthlyData',
            'categoryBreakdown',
            'userRetention'
        ));
    }

    private function calculateChurnRate(): float
    {
        $previousMonth = now()->subMonth();
        $cancelledInMonth = Subscription::where('status', 'cancelled')
            ->whereBetween('cancelled_at', [
                $previousMonth->startOfMonth(),
                $previousMonth->endOfMonth()
            ])
            ->count();

        $activeAtStartOfMonth = Subscription::where('status', 'active')
            ->where('created_at', '<', $previousMonth->startOfMonth())
            ->count();

        return $activeAtStartOfMonth > 0 ? ($cancelledInMonth / $activeAtStartOfMonth) * 100 : 0;
    }

    private function getMonthlyRevenue(): array
    {
        $months = [];
        $revenues = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');
            $revenues[] = Invoice::where('status', 'paid')
                ->whereBetween('paid_at', [
                    $month->startOfMonth(),
                    $month->endOfMonth()
                ])
                ->sum('amount');
        }

        return [
            'months' => $months,
            'revenues' => $revenues,
        ];
    }

    private function getTopBoxes(): array
    {
        return Box::withCount('subscriptions')
            ->orderByDesc('subscriptions_count')
            ->take(5)
            ->get()
            ->map(fn ($box) => [
                'title' => $box->title,
                'subscriptions' => $box->subscriptions_count,
                'revenue' => Invoice::whereHas('subscription', function ($query) use ($box) {
                    $query->where('box_id', $box->id);
                })->where('status', 'paid')->sum('amount'),
            ])
            ->toArray();
    }

    private function getMonthlyAnalytics(): array
    {
        $months = [];
        $newUsers = [];
        $activeUsers = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M');

            $newUsers[] = User::whereBetween('created_at', [
                $month->startOfMonth(),
                $month->endOfMonth()
            ])->count();

            $activeUsers[] = Subscription::where('status', 'active')
                ->whereBetween('created_at', [
                    $month->startOfMonth(),
                    $month->endOfMonth()
                ])
                ->count();
        }

        return [
            'months' => $months,
            'newUsers' => $newUsers,
            'activeUsers' => $activeUsers,
        ];
    }

    private function getCategoryBreakdown(): array
    {
        return \DB::table('boxes')
            ->join('categories', 'boxes.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(subscriptions.id) as count')
            ->leftJoin('subscriptions', 'boxes.id', '=', 'subscriptions.box_id')
            ->groupBy('categories.id', 'categories.name')
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name,
                'count' => $item->count,
            ])
            ->toArray();
    }

    private function getUserRetention(): array
    {
        $cohorts = [];
        for ($i = 3; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $cohortStart = $month->startOfMonth();
            $cohortEnd = $month->endOfMonth();

            $totalInCohort = User::whereBetween('created_at', [$cohortStart, $cohortEnd])->count();

            if ($totalInCohort > 0) {
                $retained = User::whereBetween('created_at', [$cohortStart, $cohortEnd])
                    ->whereHas('subscriptions', function ($query) {
                        $query->where('status', 'active');
                    })
                    ->count();

                $cohorts[$month->format('M Y')] = ($retained / $totalInCohort) * 100;
            }
        }

        return $cohorts;
    }

    private function authorizeAdmin()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
    }
}

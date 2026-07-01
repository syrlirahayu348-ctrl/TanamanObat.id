<?php

namespace App\Http\Controllers;

use App\Mail\ContactConfirmationUser;
use App\Mail\ContactNotificationAdmin;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Plant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPlants  = Plant::published()->popular(6)->with('category')->get();
        $latestPlants    = Plant::published()->latest()->limit(8)->with('category')->get();
        $categories      = Category::withCount(['publishedPlants'])->get();
        $totalPlants     = Plant::published()->count();
        $totalCategories = Category::count();

        return view('home.index', compact(
            'featuredPlants', 'latestPlants', 'categories', 'totalPlants', 'totalCategories'
        ));
    }

    public function search()
    {
        $query = request('q');
        $plants = Plant::published()
            ->search($query)
            ->with('category')
            ->paginate(12);

        $categories = Category::all();

        return view('plants.index', compact('plants', 'query', 'categories'));
    }

    public function faq()
    {
        return view('faq');
    }

    public function contact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        // 1. Simpan pesan ke database
        $contactMessage = ContactMessage::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        $http = app()->environment(['local', 'development', 'testing'])
            ? ['verify' => false]
            : [];

        // 2. Kirim notifikasi ke semua admin
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)
                    ->send(new ContactNotificationAdmin($contactMessage));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[Contact Admin Mail] ' . $e->getMessage());
        }

        // 3. Kirim konfirmasi ke pengirim
        try {
            Mail::to($contactMessage->email)
                ->send(new ContactConfirmationUser($contactMessage));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[Contact User Mail] ' . $e->getMessage());
        }

        return back()->with('success', 'Pesan Anda berhasil dikirim! Kami juga telah mengirimkan konfirmasi ke email Anda.');
    }
}

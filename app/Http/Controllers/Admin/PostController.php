<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PostController extends Controller
{
    public function index()
    {
        // AMBIL SEMUA USER (bukan admin) yang punya paket
        $users = User::where('is_admin', false)
                     ->whereNotNull('package_id')
                     ->with('posts')
                     ->get();

        // AMBIL SEMUA DATA POST untuk tabel riwayat (termasuk user-nya)
        $posts = Post::with('user')->latest()->get();

        return view('admin.posts.index', compact('users', 'posts'));
    }

    public function create($id = null)
    {
        if (!$id) {
            $users = User::where('is_admin', false)->whereNotNull('package_id')->with('package')->get();
            return view('admin.posts.create', compact('users'));
        }
        $user = User::with('package')->findOrFail($id);
        return view('admin.posts.create', compact('user'));
    }

    public function store_multi(Request $request)
    {
        // 1. Validasi Input Dasar (Dihapus validasi image agar embed bisa masuk)
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'upload_date' => 'required|date',
            'data' => 'required|array',
        ]);

        try {
            $path = null;

            // 2. CEK: Apakah pakai file image atau pakai text embed code
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                
                $destinationPath = public_path('storage/posts');
                $file->move($destinationPath, $fileName);
                $path = 'posts/' . $fileName;
            } elseif ($request->filled('cover_embed')) {
                // Jika input text embed yang diisi
                $path = $request->cover_embed;
            } else {
                return back()->with('error_platform', 'Cover Image atau Embed Code wajib diisi.');
            }

            $rowCount = 0;
            
            // 3. Looping data per platform (Instagram, TikTok, YouTube)
            foreach ($request->data as $platformName => $metrics) {
                
                // Hanya simpan jika Link Postingan diisi
                if (!empty($metrics['post_url'])) {
                    
                    Post::create([
                        'user_id'          => $request->user_id,
                        'platform'         => strtoupper($platformName),
                        'cover_image'      => $path, 
                        'upload_date'      => $request->upload_date,
                        'post_url'         => $metrics['post_url'],
                        'views'            => $metrics['views'] ?? 0,
                        'likes'            => $metrics['likes'] ?? 0,
                        'comments'         => $metrics['comments'] ?? 0,
                        'shares'           => $metrics['shares'] ?? 0,
                        'avg_watch_time'   => $metrics['avg_watch_time'] ?? null,
                        'age_demographics' => $metrics['age_demographics'] ?? null,
                    ]);
                    
                    $rowCount++;
                }
            }

            if ($rowCount === 0) {
                return back()->with('error_platform', 'Mohon isi minimal satu link postingan platform.');
            }

            return redirect()->route('admin.posts.index')->with('success', "Berhasil menyimpan $rowCount laporan performa!");

        } catch (\Exception $e) {
            return back()->with('error_platform', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate(Request $request)
    {
        $filePath = public_path('templates/template_konten.xlsx');
        if (!file_exists($filePath)) {
            return back()->with('error_platform', 'File template tidak ditemukan.');
        }
        $user = User::find($request->user_id);
        $businessName = $user ? str_replace(' ', '_', $user->business_name) : 'LokaVira';
        return response()->download($filePath, "Template_Laporan_{$businessName}.xlsx");
    }

    public function import_multi(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'excel_file' => 'required'
        ]);

        try {
            $file = $request->file('excel_file');
            $handle = fopen($file->getRealPath(), "r");
            
            $firstLine = fgets($handle);
            $separator = (str_contains($firstLine, ';')) ? ';' : ',';
            rewind($handle);

            fgetcsv($handle, 1000, $separator);

            $rowCount = 0;
            while (($row = fgetcsv($handle, 1000, $separator)) !== FALSE) {
                if (!isset($row[2]) || empty($row[2])) continue; 

                $rawDate = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $row[0]);
                $rawDate = trim($rawDate);
                
                try {
                    if (str_contains($rawDate, '/')) {
                        $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $rawDate)->format('Y-m-d');
                    } else {
                        $formattedDate = \Carbon\Carbon::parse($rawDate)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    $formattedDate = now()->format('Y-m-d');
                }

                Post::create([
                    'user_id'          => $request->user_id,
                    'platform'         => strtoupper(trim($row[1] ?? 'UNKNOWN')),
                    'cover_image'      => null, 
                    'upload_date'      => $formattedDate, 
                    'post_url'         => trim($row[2]),
                    'views'            => (int)($row[3] ?? 0),
                    'likes'            => (int)($row[4] ?? 0),
                    'comments'         => (int)($row[5] ?? 0),
                    'shares'           => (int)($row[6] ?? 0),
                    'avg_watch_time'   => trim($row[7] ?? ''),
                    'age_demographics' => [
                        '18-24' => trim($row[8] ?? '0'), 
                        '25-34' => trim($row[9] ?? '0'), 
                        '35-44' => trim($row[10] ?? '0'), 
                        '45+'   => trim($row[11] ?? '0')
                    ],
                ]);
                $rowCount++;
            }
            fclose($handle);

            return redirect()->route('admin.posts.index')->with('success', "Berhasil mengimport $rowCount data!");
            
        } catch (\Exception $e) {
            return back()->with('error_platform', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $user = User::with('package')->findOrFail($post->user_id);
        $platform = $post->platform;
        return view('admin.posts.edit', compact('post', 'user', 'platform'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $request->validate([
            'post_url' => 'required|url',
            'upload_date' => 'required|date',
            'views' => 'required|integer|min:0',
        ]);

        $data = $request->all();

        // Penanganan Update: Hapus file lama JIKA formatnya BUKAN text HTML/Embed
        if ($request->hasFile('cover_image')) {
            if ($post->cover_image && !str_contains($post->cover_image, '<blockquote') && file_exists(public_path('storage/' . $post->cover_image))) {
                unlink(public_path('storage/' . $post->cover_image));
            }

            $file = $request->file('cover_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/posts'), $fileName);
            $data['cover_image'] = 'posts/' . $fileName;
        } elseif ($request->filled('cover_embed')) {
            // Jika update-nya jadi text embed
            if ($post->cover_image && !str_contains($post->cover_image, '<blockquote') && file_exists(public_path('storage/' . $post->cover_image))) {
                unlink(public_path('storage/' . $post->cover_image));
            }
            $data['cover_image'] = $request->cover_embed;
        }

        $post->update($data);
        return redirect()->route('admin.posts.index')->with('success', 'Data performa diperbarui!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        // Hapus file fisik HANYA JIKA BUKAN format text HTML/Embed
        if ($post->cover_image && !str_contains($post->cover_image, '<blockquote') && file_exists(public_path('storage/' . $post->cover_image))) {
            unlink(public_path('storage/' . $post->cover_image));
        }

        $post->delete();
        return back()->with('success', 'Data konten berhasil dihapus!');
    }
}
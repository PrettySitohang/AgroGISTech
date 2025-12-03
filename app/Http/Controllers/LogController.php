<?php
// app/Http/Controllers/LogController.php
namespace App\Http\Controllers;

use App\Models\Log;
use App\Services\LogService;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::with('actor')->latest()->paginate(25);
        return view('superadmin.logs', compact('logs'));
    }

    public function destroy(Log $log)
    {
        $log->delete();
        LogService::record('log.delete','log',$log->id);
        return back()->with('success','Log dihapus.');
    }
}

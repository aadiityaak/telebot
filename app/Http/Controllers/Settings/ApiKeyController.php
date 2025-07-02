<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\Setting;

class ApiKeyController extends Controller
{
  public function index()
  {
    return Inertia::render('settings/ApiKey', [
      'api_key' => Setting::getValue('app_api_key'),
    ]);
  }

  public function regenerate()
  {
    $newKey = Str::random(40);
    Setting::setValue('app_api_key', $newKey);

    return redirect()
      ->route('settings.api-key')
      ->with('success', 'API Key berhasil diperbarui.')
      ->with('api_key', $newKey);
  }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RedemptionCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CodeController extends Controller
{
  public function code()
  {
    $pageTitle = 'Codes';
    $codes = RedemptionCode::paginate(getPaginate());
    return view('admin.code.index', compact('pageTitle', 'codes'));
  }

  public function codeSave(Request $request)
  {
    $request->validate([
      'count' => 'nullable|numeric|:min:1',
      'value' => 'required|numeric|min:1',
    ]);

    $count = $request->input('count', 1);
    $data = [];
    $data['value'] = $request->value;

    for ($i = 0; $i < $count; $i++)
    {
      $data['code'] = Str::random(8);
      RedemptionCode::create($data);
    }

    $notify[] = ['success', 'Code saved successfully'];
    return back()->withNotify($notify);
  }

  public function delete(string|int $id)
  {
    $code = RedemptionCode::find($id);
    if ($code)
    {
      $code->delete();
    }

    $notify[] = ['success', 'Code deleted successfully'];
    return back()->withNotify($notify);
  }
}

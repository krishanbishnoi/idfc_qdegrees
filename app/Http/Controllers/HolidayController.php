<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\HolidayImport;
use App\Holiday;
use Excel;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('date')->get();
        return view('holidays.index', compact('holidays'));
    }

    public function uploadPage()
    {
        return view('holidays.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new HolidayImport, $request->file('file'));

        return back()->with('success', 'Holidays uploaded successfully!');
    }

    public function edit($id)
    {
        $holiday = Holiday::findOrFail($id);
        return view('holidays.edit', compact('holiday'));
    }

    public function update(Request $request, $id)
{
    $holiday = Holiday::findOrFail($id);

    // CHECK: If date already exists for another record
    $exists = Holiday::where('date', $request->date)
        ->where('id', '!=', $id)
        ->exists();

    if ($exists) {
        return back()->withErrors(['date' => 'This date is already used in another record.'])->withInput();
    }

    // No duplicate → update
    $holiday->update([
        'day_name'     => $request->day_name,
        'date'         => $request->date,
        'holiday_name' => $request->holiday_name,
        'working_date' => $request->working_date,
    ]);

    return redirect()->route('holidays.index')->with('success', 'Record updated successfully!');
}

}

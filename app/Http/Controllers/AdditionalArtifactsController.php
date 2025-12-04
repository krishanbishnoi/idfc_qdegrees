<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use Crypt;
use App\AdditionalArtifact;
use Carbon\Carbon;
use ZipArchive;

// use Storage;


class AdditionalArtifactsController extends Controller
{
    public function create($type, $audit_id)
    {
// dd($type);
        $originalType = $type;
        if ($type == 'branch_repo') {
            $type = 'branch';
        }
        if ($type == 'agency_repo') {
            $type = 'agency';
        }
        if($type == 'repo_yard'){
            $type = 'yard';
        }
        $audit = DB::table('audits')->where('id', $audit_id)->first();
        // dd(Crypt::decrypt($audit_id));
        // $audit_id = Crypt::decrypt($audit_id);
        $artifacts = DB::table('additional_artifacts_points')->where('type', $type)->pluck('artifact')->toArray();
        if ($originalType == 'agency') {
            $uploaded = AdditionalArtifact::where('agency_id', $audit->agency_id)->get();
        } elseif ($originalType == 'branch') {
            $uploaded = AdditionalArtifact::where('branch_id', $audit->branch_id)->get();
        } elseif ($originalType == 'repo_yard') {
            $uploaded = AdditionalArtifact::where('yard_id', $audit->yard_id)->get();
        } elseif ($originalType == 'agency_repo') {
            $uploaded = AdditionalArtifact::where('agency_repo_id', $audit->agency_repo_id)->get();
        } elseif ($originalType == 'branch_repo') {
            $uploaded = AdditionalArtifact::where('branch_repo_id', $audit->branch_repo_id)->get();
        } elseif ($originalType == 'yard_repo') {
            $uploaded = AdditionalArtifact::where('yard_repo_id', $audit->yard_repo_id)->get();
        } else {
            $uploaded = AdditionalArtifact::where('agency_id', $audit->agency_id)->get();
        }
        // dd($uploaded);
        $uploadedGrouped = $uploaded->groupBy('artifact_name');
        return view('artifacts_additional.create', compact('artifacts', 'audit_id', 'originalType', 'uploadedGrouped'));
    }





        public function store(Request $request)
    {
        // dd($request->all());
        $user_id = auth()->user()->id;
        $audit = DB::table('audits')->where('id', $request->audit_id)->first();
        $qm_sheet = DB::table('qm_sheets')->where('id', $audit->qm_sheet_id)->first();
        $type = $qm_sheet->type;
        // dd($type);
        // if($request->type == 'branch')
        // dd($audit);
        // $agency = DB::table('agencies')->where('id', $audit->agency_id)->first();
        $cycle = DB::table('audit_cycles')->where('id', $audit->audit_cycle_id)->value('name');

        foreach ($request->file('artifacts') as $artifactName => $files) {

            if (!$files) {
                continue; // skip if no file uploaded
            }

            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $newFileName = uniqid() . '.' . $extension;

                
                if ($type == 'agency') {
                    $field = 'agency_id';
                    $data = $audit->agency_id;
                    $detail = DB::table('agencies')->where('id', $audit->agency_id)->first();
                    $name= $detail->name;
                    $branch_name = DB::table('branches')->where('id', $detail->branch_id)->value('name');
                    // dd($branch_name, $data->name, $artifactName);
                    $newFileName = $branch_name. '-' . $detail->name . '-' . $detail->agency_id . '-' . $artifactName . '-' .  uniqid() . '.' . $extension;
                    // dd($newFileName);
                    
                } elseif ($type == 'branch') {
                    $field = 'branch_id';
                    $data = $audit->branch_id;
                    $cm = $audit->collection_manager_id;
                    $cmname  = DB::table('users')->where('id', $cm)->value('name');
                    $name = DB::table('branches')->where('id', $audit->branch_id)->value('name');
                    $newFileName = $name . '_' . $cmname . '_' . $cm . '_' . $artifactName . '_' . uniqid() . '.' . $extension;
                } elseif ($type == 'repo_yard') {
                    $field = 'yard_id';
                    $data = $audit->yard_id;
                    $detail = DB::table('yards')->where('id', $audit->yard_id)->first();
                    $name = $detail->name;
                    $branch_name = DB::table('branches')->where('id', $detail->branch_id)->value('name');
                    $newFileName = $branch_name. '-' . $detail->name . '-' . $detail->yard_id . '-' . $artifactName . '-' .  uniqid() . '.' . $extension;
                } elseif ($type == 'agency_repo') {
                    $field = 'agency_repo_id';
                    $data = $audit->agency_repo_id;
                    $detail = DB::table('agency_repos')->where('id', $audit->agency_repo_id)->first();
                    $name = $detail->name;
                    $branch_name = DB::table('branches')->where('id', $detail->branch_id)->value('name');
                    $newFileName = $branch_name. '-' . $detail->name . '-' . $detail->agency_repo_id . '-' . $artifactName . '-' . uniqid() . '.' . $extension;
                } elseif ($type == 'branch_repo') {
                    $field = 'branch_repo_id';
                    $data = $audit->branch_repo_id;
                    $cm = $audit->collection_manager_id;
                    $cmname  = DB::table('users')->where('id', $cm)->value('name');
                    $name = DB::table('branch_repos')->where('id', $audit->branch_repo_id)->value('name');
                    $newFileName = $name . '_' . $cmname . '_' . $cm . '_' . $artifactName . '_' . uniqid() . '.' . $extension;
                } elseif ($type == 'yard_repo') {
                    $field = 'yard_repo_id';
                    $data = $audit->yard_repo_id;
                    $name = DB::table('yard_repo')->where('id', $audit->yard_repo_id)->value('name');
                    $newFileName = uniqid() . '.' . $extension;
                } else {
                    $field = 'agency_id';
                    $data = $audit->agency_id;
                    $name = DB::table('agencies')->where('id', $audit->agency_id)->value('name');
                    $newFileName = uniqid() . '.' . $extension;
                }

                $filePath = $file->storeAs('uploads/additional-artifacts', $newFileName, 'public');

                // Save record into additional_artifacts table
                AdditionalArtifact::create([
                    $field               => $data,
                    'agency_name_cycle'  => $name . '_' . $cycle,
                    'audit_id'           => $request->audit_id,
                    'artifact'           => $filePath,
                    'validate_at'        => $request->validate_at,
                    'type'               => $type,
                    'artifact_name'      => $artifactName,
                    'user_id'            => $user_id,
                    'audit_cycle'        => $cycle,
                ]);
            }
        }

        return back()->with('success', 'Artifacts uploaded & saved successfully!');
    }
    //     public function delete($id)
    // {
    //     $file = Artifact::find($id);

    //     if (!$file) {
    //         return response()->json(['success' => false, 'message' => 'File not found'], 404);
    //     }

    //     // Delete file from storage
    //     Storage::delete('public/' . $file->artifact);

    //     // Delete record from database
    //     $file->delete();

    //     return response()->json(['success' => true]);
    // }


    public function destroy($id)
    {
        // dd($id);
        // Find the artifact
        $artifact = AdditionalArtifact::find($id);
        // dd($artifact);
        if (!$artifact) {
            return response()->json(['success' => false, 'message' => 'Artifact not found.']);
        }

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($artifact->artifact)) {
                Storage::disk('public')->delete($artifact->artifact);
            }

            // Delete from DB
            $artifact->delete();

            return response()->json(['success' => true, 'message' => 'Artifact deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete artifact.']);
        }
    }

    public function downloadArtifacts($audit_id)
    {
        // Fetch all artifacts for the audit
        $artifacts = AdditionalArtifact::where('audit_id', $audit_id)->get();

        if ($artifacts->isEmpty()) {
            return back()->with('error', 'No artifacts found for this audit.');
        }

        $zipFileName = "audit_{$audit_id}_artifacts.zip";
        $zipPath = storage_path('app/public/' . $zipFileName);

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {

            foreach ($artifacts as $artifact) {

                $filePath = storage_path('app/public/' . $artifact->artifact);
                $artifactFolder = $artifact->artifact_name; // Subfolder name
                $fileName = basename($artifact->artifact);

                if (file_exists($filePath)) {
                    // Add file under subfolder in ZIP
                    $zip->addFile($filePath, $artifactFolder . '/' . $fileName);
                }
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    // public function store(Request $request)
    // {
    //     dd($request->all());
    //     // Validate the request
    //     $request->validate([
    //         'artifacts.*' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',  // Adjust the validation rules as needed
    //     ]);

    //     // Process each file uploaded
    //     $uploadedFiles = [];
    //     foreach ($request->file('artifacts') as $artifact) {
    //         // Store the file and keep the path
    //         $uploadedFiles[] = $artifact->store('artifacts');
    //     }

    //     // You can store the paths in the database if needed
    //     // For example: Artifact::create(['file_paths' => json_encode($uploadedFiles)]);

    //     return redirect()->route('artifacts.create')->with('success', 'Artifacts uploaded successfully!');
    // }

    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     foreach ($request->file('artifacts') as $artifactName => $files) {

    //         // Ensure $files is an array (because multiple uploads)
    //         if (!is_array($files)) {
    //             $files = [$files];
    //         }

    //         $counter = 0;

    //         foreach ($files as $file) {
    //             $extension = $file->getClientOriginalExtension();

    //             // First file uses the exact artifact name
    //             if ($counter == 0) {
    //                 $newFileName = $artifactName . '.' . $extension;
    //             } else {
    //                 $newFileName = $artifactName . ' ' . $counter . '.' . $extension;
    //             }

    //             // Store file
    //             $file->storeAs('artifacts', $newFileName, 'public');

    //             $counter++;
    //         }
    //     }

    //     return back()->with('success', 'Artifacts uploaded successfully!');
    // }


    public function download()
    {
        $audit_cycle = DB::table('audit_cycles')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();
        return view('artifacts_additional.download', compact('audit_cycle'));
    }
    public function getItems(Request $request)
    {
        $type = $request->type;
        if ($type == 'branch_repo') {
            $column = 'branch_repo_id';
            $table = 'branch_repos';
        } elseif ($type == 'agency_repo') {
            $column = 'agency_repo_id';
            $table = 'agency_repos';
            // $table = 'branch_repo';
        } elseif ($type == 'yard_repo') {
            $column = 'yard_repo_id';
            $table = 'yard_repos';
        } elseif ($type == 'agency') {
            $table = 'agencies';
            $column = 'agency_id';
        } elseif ($type == 'branch') {
            $column = 'branch_id';
            $table = 'branches';
        } elseif ($type == 'repo_yard') {
            $column = 'yard_id';
            $table = 'yards';
        }

        // Get distinct IDs from additional_artifacts table
        $ids = DB::table('additional_artifacts')
            ->where('type', $type)
            ->distinct()
            ->pluck($column); // assuming 'type_id' stores the related id

        // $table = $type; // table name matches type, e.g., branch_repo
        $items = DB::table($table)
            ->whereIn('id', $ids)
            ->select('id', 'name')
            ->get();
        // dd($items);
        return response()->json($items);
    }

    // Fetch artifacts for third select box
    public function getArtifacts(Request $request)
    {
        $item_id = $request->item_id;

        $type = $request->type;
        if ($type == 'branch_repo') {
            $column = 'branch_repo_id';
        } elseif ($type == 'agency_repo') {
            $column = 'agency_repo_id';
        } elseif ($type == 'yard_repo') {
            $column = 'yard_repo_id';
        } elseif ($type == 'agency') {

            $column = 'agency_id';
        } elseif ($type == 'branch') {
            $column = 'branch_id';
        } elseif ($type == 'repo_yard') {
            $column = 'yard_id';
        }

        $artifacts = DB::table('additional_artifacts')
            ->where($column, $item_id)
            ->select(DB::raw('MIN(id) as id'), 'artifact_name')
            ->groupBy('artifact_name')
            ->get();
        return response()->json($artifacts);
    }

    public function download_artifcats(Request $request)
    {
        $type = $request->type;

        if ($type == 'branch_repo') {
            $column = 'branch_repo_id';
        } elseif ($type == 'agency_repo') {
            $column = 'agency_repo_id';
        } elseif ($type == 'yard_repo') {
            $column = 'yard_repo_id';
        } elseif ($type == 'agency') {
            $column = 'agency_id';
        } elseif ($type == 'branch') {
            $column = 'branch_id';
        } elseif ($type == 'repo_yard') {
            $column = 'yard_id';
        } else {
            abort(400, 'Invalid type');
        }

        $itemId = $request->item_id;
        if ($request->artifact_name == null) {
            $artifacts = AdditionalArtifact::where($column, $itemId)->get();

            if ($artifacts->isEmpty()) {
                return back()->with('error', 'No artifacts found for this audit.');
            }

            $zipFileName = "audit_artifacts.zip";
            $zipPath = storage_path('app/public/' . $zipFileName);

            $zip = new ZipArchive;

            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {

                foreach ($artifacts as $artifact) {

                    $filePath = storage_path('app/public/' . $artifact->artifact);
                    $artifactFolder = $artifact->artifact_name; // Subfolder name
                    $fileName = basename($artifact->artifact);

                    if (file_exists($filePath)) {
                        // Add file under subfolder in ZIP
                        $zip->addFile($filePath, $artifactFolder . '/' . $fileName);
                    }
                }

                $zip->close();
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } else {
            $artifact_name = AdditionalArtifact::where('id', $request->artifact_name)->value('artifact_name');

            // dd($artifact_name);
            $artifacts = AdditionalArtifact::where('artifact_name', $artifact_name)
                ->where($column, $itemId)
                ->get();

            if ($artifacts->isEmpty()) {
                abort(404, 'No artifacts found.');
            }

            // Create a temporary file with .zip extension
            $tempFile = tempnam(sys_get_temp_dir(), 'zip');
            $zipPath = $tempFile . '.zip';
            rename($tempFile, $zipPath);

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
                abort(500, 'Could not create zip file.');
            }

            foreach ($artifacts as $artifact) {
                $filePath = storage_path('/app/public/' . $artifact->artifact);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $artifact_name . '/' . basename($filePath));
                }
            }

            $zip->close();

            return response()->download($zipPath, $artifact_name . '.zip')->deleteFileAfterSend(true);
        }
    }
}

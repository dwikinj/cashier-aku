<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Exceptions\DecoderException;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemberController extends Controller
{


    /**
     * Display member page.
     */
    public function displayMember()
    {
        return view('backend.dashboard.members');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Member::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($member) {
                return '
                    <button data-id="' . $member->id . '" class="btn btn-warning btn-sm member-edit-btn">Edit</button>
                    <button data-id="' . $member->id . '"  class="btn btn-danger btn-sm member-delete-btn">Delete</button>
                ';
            })
            ->addColumn('checkbox', '<input type="checkbox" name="members_checkbox[]" class="members_checkbox" value="{{$id}}" />')
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $searchValue = $request->get('search')['value'];
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('members.code', 'like', "%{$searchValue}%")
                            ->orWhere('members.name', 'like', "%{$searchValue}%")
                            ->orWhere('members.address', 'like', "%{$searchValue}%")
                            ->orWhere('members.phone', 'like', "%{$searchValue}%");
                    });
                }
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMemberRequest $request)
    {
        $validatedData = $request->validated();

        // Generate random string if code is null or empty
        if (empty($validatedData['code'])) {
            $validatedData['code'] = Str::random(10);
        }
        Member::create([
            'code' => $validatedData['code'],
            'name' => $validatedData['name'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Member created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = Member::findOrFail($id);
        return response()->json([
            'id' => $member->id,
            'code' => $member->code,
            'name' => $member->name,
            'address' => $member->address,
            'phone' => $member->phone,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, $id)
    {
        try {
            $member = Member::findOrFail($id);
            $validatedData = $request->validated();
            $member->update($validatedData);
            return response()->json(['message' => 'Member updated successfully', 'member' => $member]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Member not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the member: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Member deleted successfully'
        ], 200);
    }

    /**
     * Print members barcode.
     */

    public function printProductsBarcode(Request $request)
    {
        $member_id_array = $request->query('id');

        if (!is_array($member_id_array)) {
            $member_id_array = explode(',', $member_id_array);
        }

        $members = Member::whereIn('id', $member_id_array)->get();
        $setting = Setting::first();

        $cards = [];

        // Create an instance of ImageManager
        $manager = new ImageManager(new Driver());

        // Define default paths
        $defaultCardPath = Storage::disk('public')->path('default/card_member.png');
        $defaultLogoPath = Storage::disk('public')->path('default/company_logo.png');

        foreach ($members as $member) {
            try {
                // Use default paths or settings paths if they exist
                $memberCardPath = $defaultCardPath;
                $logoPath = $defaultLogoPath;

                // Check if files exist
                if (!file_exists($memberCardPath) || !file_exists($logoPath)) {
                    throw new \Exception("Image file not found");
                }

                // Load background image
                $img = $manager->read($memberCardPath);

                // Load and insert logo
                $logo = $manager->read($logoPath);
                $logo->resize(100, 100);
                $img->place($logo, 'top-center', 10, 10);

                // Add member name
                $img->text($member->name, $img->width() / 2, $logo->height() + 10, function ($font) {
                    $font->file(public_path('backend/assets/fonts/Roboto-Medium.ttf'));
                    $font->size(24);
                    $font->color('#000000');
                    $font->align('center');
                    $font->valign('top');
                });

                // Generate QR code using Endroid/QR-Code
                $qrCode = QrCode::create($member->code)
                    ->setSize(100)
                    ->setMargin(0);

                $writer = new PngWriter();
                $result = $writer->write($qrCode);

                // Save QR code to file
                $qrCodePath = 'temp/' . $member->id . '_qrcode.png';
                Storage::disk('public')->put($qrCodePath, $result->getString());

                // Load QR code image
                $qrCodeImg = $manager->read(Storage::disk('public')->path($qrCodePath));

                // Place QR code at bottom-left
                $img->place($qrCodeImg, 'center', 10, 10);

                // Save the image
                $outputPath = 'temp/' . $member->id . '_card.png';
                $img->save(Storage::disk('public')->path($outputPath));

                // Encode image to base64
                $base64Image = base64_encode(file_get_contents(Storage::disk('public')->path($outputPath)));
                $cards[] = 'data:image/png;base64,' . $base64Image;
            } catch (\Exception $e) {
                // Handle errors
                return response()->json(['error' => 'Error processing image: ' . $e->getMessage()], 500);
            }
        }

        try {
            $pdf = Pdf::loadView('backend.components.print-member-card', ['cards' => $cards])->setPaper('A4', 'potrait');

            return $pdf->stream('membership-card.pdf');
        } catch (\Exception $e) {
            // Handle view or PDF generation error
            return response()->json(['error' => 'Error generating view or PDF: ' . $e->getMessage()], 500);
        }
    }
}

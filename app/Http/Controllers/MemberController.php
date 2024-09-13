<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

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
        //
    }
}

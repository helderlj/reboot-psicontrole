<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;

class ServiceUsersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Service $service)
    {
        $this->authorize('view', $service);

        $search = $request->get('search', '');

        $users = $service
            ->users()
            ->search($search)
            ->latest()
            ->paginate();

        return new UserCollection($users);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Service $service
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Service $service, User $user)
    {
        $this->authorize('update', $service);

        $service->users()->syncWithoutDetaching([$user->id]);

        return response()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Service $service
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Service $service, User $user)
    {
        $this->authorize('update', $service);

        $service->users()->detach($user);

        return response()->noContent();
    }
}

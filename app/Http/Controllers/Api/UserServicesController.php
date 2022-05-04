<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCollection;

class UserServicesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $services = $user
            ->services()
            ->search($search)
            ->latest()
            ->paginate();

        return new ServiceCollection($services);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user, Service $service)
    {
        $this->authorize('update', $user);

        $user->services()->syncWithoutDetaching([$service->id]);

        return response()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user, Service $service)
    {
        $this->authorize('update', $user);

        $user->services()->detach($service);

        return response()->noContent();
    }
}

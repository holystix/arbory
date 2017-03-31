<?php

namespace CubeSystems\Leaf\Http\Middleware;

use Cartalyst\Sentinel\Sentinel;
use Closure;
use CubeSystems\Leaf\Services\Module;
use CubeSystems\Leaf\Services\ModuleRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class AdminMiddleware
 * @package CubeSystems\Leaf\Http\Middleware
 */
class LeafAdminAuthMiddleware
{
    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * LeafAdminAuthMiddleware constructor.
     * @param Sentinel $sentinel
     */
    public function __construct( Sentinel $sentinel )
    {
        $this->sentinel = $sentinel;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
        if( !$this->sentinel->check() )
        {
            return $this->denied( $request );
        }

        $targetModule = $this->resolveTargetModule( $request );

        if( !$targetModule )
        {
            throw new \RuntimeException( 'Could not find target module for route controller' );
        }

        if( !$targetModule->isAuthorized( $this->sentinel ) )
        {
            return $this->denied( $request );
        }

        return $next( $request );
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    private function denied( Request $request )
    {
        $message = 'Unauthorized';

        if( $request->ajax() )
        {
            return response()->json( [ 'error' => $message ], 401 );
        }

        return redirect()
            ->guest( route( 'admin.login.form' ) )
            ->with( 'error', $message );
    }

    /**
     * @param Request $request
     * @return Module|null
     */
    private function resolveTargetModule( Request $request )
    {
        $controller = $request->route()->getController();

        /* @var $modules ModuleRegistry */
        $modules = app( 'leaf.modules' );

        return $modules->findModuleByController( $controller );
    }
}

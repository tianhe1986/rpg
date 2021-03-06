<?php

namespace App\Http\Middleware;

use App\Character;
use App\Contracts\Models\UserInterface;
use Closure;
use Illuminate\Http\Request;

class CanAttack
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var Character $targetCharacter */
        $targetCharacter = $request->route('character');

        /** @var UserInterface $user */
        $user = $request->user();
        $currentUserCharacter = $user->getCharacter();

        if (!$currentUserCharacter->isAlive()) {
            return redirect()->back()->withErrors([
                'message' => 'You cannot attack when your character is knocked out',
            ]);
        }

        if (!$targetCharacter->isAlive()) {
            return redirect()->back()->withErrors([
                'message' => 'You cannot attack a knocked out character',
            ]);
        }

        if ($targetCharacter->getId() === $currentUserCharacter->getId()) {
            return redirect()->back()->withErrors([
                'message' => 'You cannot attack yourself',
            ]);
        }

        return $next($request);
    }
}

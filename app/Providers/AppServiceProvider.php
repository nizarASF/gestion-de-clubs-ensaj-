<?php

namespace App\Providers;

use App\Models\Club;
use App\Models\Conversation;
use App\Models\Media;
use App\Models\Post;
use App\Models\TeamMember;
use App\Policies\ConversationPolicy;
use App\Policies\MediaPolicy;
use App\Policies\PostPolicy;
use App\Policies\TeamMemberPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Policies explicites : un membre n'agit que sur sa propre ressource.
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(TeamMember::class, TeamMemberPolicy::class);
        Gate::policy(Media::class, MediaPolicy::class);
        Gate::policy(Conversation::class, ConversationPolicy::class);
        // L'ability « contact » porte sur un club mais vit dans ConversationPolicy.
        Gate::policy(Club::class, ConversationPolicy::class);

        // Pagination rendue par notre propre vue CSS, sans framework externe.
        Paginator::defaultView('pagination');

        // Compteurs du header, calculés hors des vues.
        View::composer('layouts.app', function ($view): void {
            $user = Auth::user();
            $view->with([
                'unreadMessagesCount' => $user?->unreadMessagesCount() ?? 0,
                'unreadNotificationsCount' => $user?->unreadNotifications()->count() ?? 0,
            ]);
        });
    }
}

<?php

namespace App\Providers;

use App\Models\Invitation;
use App\Policies\InvitationPolicy;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Models\Reply;
use App\Policies\ReplyPolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Invitation::class => InvitationPolicy::class,
        Comment::class => CommentPolicy::class,
        Reply::class => ReplyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}

<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {

        $this->registerPolicies();
        Gate::define('employee_setting',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(1,$decode_permission) || $user->id==1){
                return true;
            }
        });

        Gate::define('allowance',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(21,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('deduction',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(22,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('salary_structure',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(23,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('allowance_template',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(24,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('deduction_template',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(25,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('salary_template',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(26,$decode_permission) || $user->id==1){
                return true;
            }
        });

        Gate::define('monthly_update',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(31,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('group_update',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(32,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('annual_increment',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(33,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('loan_deduction',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(34,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('salary_posting',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(35,$decode_permission) || $user->id==1){
                return true;
            }
        });

        Gate::define('group_report',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(41,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('individual_report',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(42,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('nominal_roll',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(43,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('annual_inc_history',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(44,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('loan_dedc_history',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(45,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('retired_staff',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(46,$decode_permission) || $user->id==1){
                return true;
            }
        });

        Gate::define('backup_history',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(51,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('restore_history',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(52,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('audit_log',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(53,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('analytic',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(54,$decode_permission)  || $user->id==1){
                return true;
            }
        });

        Gate::define('backup',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(61,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('restore',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(62,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('app_setting',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(63,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('admin_user',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(64,$decode_permission)  || $user->id==1){
                return true;
            }
        });


        Gate::define('can_save',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(71,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('can_edit',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(72,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('can_export',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(73,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('can_mail',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(74,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('can_report',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(75,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('can_promote',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(76,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('repository',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(77,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('terminated_list',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(78,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('restore_point',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(79,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('help',function (User $user){
            $decode_permission=json_decode($user->permission);
            if (in_array(80,$decode_permission) || $user->id==1){
                return true;
            }
        });
        Gate::define('can_admin',function (User $user){
          if ($user->role !=1){
              return true;
          }
        });




        //
    }
}

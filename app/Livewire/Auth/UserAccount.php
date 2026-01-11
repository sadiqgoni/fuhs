<?php

namespace App\Livewire\Auth;

use App\Models\ActivityLog;
use App\Models\EmployeePasskey;
use App\Models\Passkey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use function TallStackUi\Foundation\Support\Blade\id;

class UserAccount extends Component
{
    public $name,$email,$username,$password;
    use LivewireAlert;
    protected $listeners=['generate'];
    public $view=false,$passkeys,$view_passkey=false;
    protected function rules(){
        return [
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.auth()->id(),
            'username'=>'required|unique:users,username,'.auth()->id(),
        ];
    }

    public function update()
    {
        $this->validate();
        auth()->user()->name=$this->name;
        auth()->user()->email=$this->email;
        auth()->user()->username=$this->username;
        auth()->user()->save();
        $this->alert('success','Your account have been updated');
        $user=Auth::user();
        $log=new ActivityLog();
        $log->user_id=$user->id;
        $log->action="Updated their account";
        $log->save();
    }
    public function mount()
    {
        $keysArray=
            [
                "flar", "bint", "clow", "neft", "drap", "malk", "jore", "spug", "fard", "lerm",
                "wost", "quib", "dren", "hult", "grak", "vond", "tret", "bemp", "skav", "jult",
                "zarn", "frib", "plod", "mekz", "durn", "vota", "trub", "klef", "mong", "durk",
                "grov", "quen", "wekt", "blim", "gurt", "shav", "norm", "kelp", "vick", "trol",
                "zemp", "scru", "mylk", "brak", "chuv", "plar", "werd", "flen", "zirk", "ponz",
                "grop", "lunt", "sked", "fler", "clid", "bruz", "kemp", "snuv", "yark", "zolm",
                "prag", "wend", "jask", "hork", "blem", "zuch", "wral", "glen", "murk", "jiff",
                "trab", "snom", "plez", "vorn", "keft", "ralk", "grun", "jorb", "smik", "telf",
                "drik", "phez", "zumb", "klan", "brog", "lerm", "vink", "suft", "clab", "neev",
                "wark", "zond", "thaz", "glim", "kupt", "shaz", "brot", "nump", "ulch", "vang",
                "plin", "kosh", "meef", "jalk", "lurf", "truz", "quap", "zegg", "ronk", "knel",
                "dunt", "groz", "shup", "womp", "curz", "jert", "neeb", "murt", "krad", "volk",
                "skon", "pruv", "jelm", "honz", "vlip", "zook", "merg", "doft", "bark", "klaz",
                "snob", "vorg", "trez", "gluv", "flid", "prot", "shaz", "mung", "chiv", "brap",
                "stog", "kler", "thub", "juzz", "prex", "dilt", "werm", "zoop", "fluv", "schn",
                "crad", "wupt", "norb", "jenk", "skam", "leff", "zorv", "chot", "bren", "hump",
                "turf", "slib", "girn", "jalk", "nezz", "pruz", "flob", "klan", "quif", "zugk",
                "brem", "smod", "jibb", "lunt", "cruv", "kleb", "gorp", "shaz", "brom", "velt",
                "woft", "blan", "snug", "dorn", "kern", "yult", "trab", "krin", "zaff", "chet",
                "blor", "jumb", "teld", "vusk", "merg", "grib", "shum", "wrop", "druv", "flan",
                "jerv", "corb", "munt", "sheb", "blik", "norz", "tepz", "splu", "jank", "zemn",
                "klub", "harn", "grel", "volg", "thon", "juld", "prig", "snet", "glar", "druv",
                "flim", "shok", "turn", "berk", "kluv", "meff", "lurt", "zwig", "chub", "fral",
                "gruz", "nelf", "brod", "votz", "zolk", "werm", "trap", "shan", "klit", "murn",
                "trof", "blen", "jusk", "krab", "durg", "peld", "gref", "chaz", "lorp", "spid",
                "crup", "jank", "bozz", "wrot", "meng", "flaz", "doft", "grib", "trol", "kenz",
                "prat", "skuv", "blim", "jomp", "kurz", "claf", "gnek", "murf", "shek", "druz",
                "fleg", "ronk", "blag", "twuz", "heff", "knop", "gral", "shoz", "bult", "plem",
                "joff", "merg", "blag", "nurp", "zant", "volt", "drim", "welf", "snoz", "chub",
                "trep", "blaz", "vorn", "klim", "shub", "pert", "gorf", "krim", "dulz", "zank",
                "flim", "drup", "jenk", "nard", "womb", "grak", "fluv", "triz", "chuz", "blon",
                "krub", "shig", "nulp", "vokz", "skre", "wond", "flin", "zumb", "trez", "zenk",
                "marn", "bluv", "jern", "cron", "flob", "drez", "klir", "chul", "porg", "zonf",
                "delt", "pruz", "zirk", "jamp", "snud", "grub", "glaf", "trul", "mept", "vurk",
                "skez", "narl", "jern", "trud", "hulf", "drem", "kren", "zogt", "pezz", "brin",
                "klob", "flam", "nenk", "fluv", "jelk", "dorf", "snul", "pled", "wrib", "gorp",
                "zonk", "blap", "kren", "trow", "jisp", "vank", "durz", "klon", "clet", "smor",
                "flad", "shon", "glir", "japt", "merk", "klib", "doft", "barg", "trul", "pleb",
                "chov", "jerd", "blaf", "snom", "vurk", "wald", "grep", "snig", "krel", "brap",
                "drul", "glup", "vort", "plim", "jalk", "nezz", "krad", "merg", "zelp", "thur",
                "bluz", "shom", "plor", "kred", "duff", "wend", "trup", "flab", "merg", "klok",
                "snet", "zorn", "jert", "dung", "blid", "klam", "sheg", "flog", "glen", "jupz",
                "twag", "burn", "prif", "knor", "glaz", "chon", "tulp", "flad", "snog", "bert",
                "clor", "meff", "klaz", "jurb", "droz", "glub", "zemf", "prut", "welf", "vorp",
                "shon", "krip", "flok", "drum", "pleb", "bult", "wrez", "snur", "jund", "bruk",
                "glot", "thim", "plog", "crid", "merk", "flup", "quon", "slar", "trug", "bloz",
                "nard", "klin", "snog", "yerm", "jall", "brup", "merg", "keft", "truv", "plog",
                "slen", "gruz", "tork", "droz", "flink", "wurp", "jenz", "knop", "vlur", "shaf",
                "blet", "pruv", "klud", "jarn", "mont", "gert", "durz", "knop", "glar", "kruz",
                "tent", "blor", "zink", "shud", "jurf", "vorm", "pled", "groz", "znar", "klib",
                "flop", "trup", "mezt", "snur", "klim", "vorg", "shok", "weft", "jomp", "bluv",
                "norg", "triz", "crub", "merk", "klan", "gret", "jank", "bult", "snop", "dred"
            ];
        $keysArray=collect($keysArray);
        if (Passkey::get()->count() < 1){
            foreach ($keysArray as $item){
                $pass=new Passkey();
                $pass->name=$item;
                $pass->save();
            }
        }

        $this->username= auth()->user()->username;
        $this->name= auth()->user()->name;
        $this->email= auth()->user()->email;

    }

    public function enter_password()
    {
        $this->view_passkey=true;
    }
    public function generate()
    {

        $this->validate(['password'=>'required']);
        if (Hash::check($this->password,Auth::user()->password)){
            $keys=Passkey::inRandomOrder()->limit(5)->get();
            foreach ($keys as $index=>$key){
                $emp_key=new EmployeePasskey();
                $emp_key->employee_id=auth()->id();
                $emp_key->key_id=$key->id;
                $emp_key->passkey=strtolower($key->name);
                $emp_key->rand_int=$index+1;

                $emp_key->save();
            }
            auth()->user()->passkey=1;
            auth()->user()->is_2fa_enabled = 0;
            auth()->user()->otp = null;
            auth()->user()->otp_expires_at = null;
            auth()->user()->save();

            $this->view=true;
            $this->passkeys=EmployeePasskey::where('employee_id',Auth::id())->get();
            $this->alert('success','Two factor authentication have been activated');
            $user=Auth::user();
            $log=new ActivityLog();
            $log->user_id=$user->id;
            $log->action="Activated two factor authentication";
            $log->save();
            $this->view_passkey=false;
            $this->password='';
        }else{
            $this->alert('warning','Invalid Password',[
                'icon' => 'warning',
                'toast' => true,
                'position'=>'center'
            ]);
        }

    }
    public function turn_off()
    {
        if (Hash::check($this->password,Auth::user()->password)) {
            $passkeys = EmployeePasskey::where('employee_id', Auth::id())->get();
            foreach ($passkeys as $pass) {
                $pass->delete();
            }
            auth()->user()->passkey = null;
            auth()->user()->verify = null;
            auth()->user()->save();
            $this->view = false;
            $this->alert('success', 'Two factor authentication have been de-activated');
            $user = Auth::user();
            $log = new ActivityLog();
            $log->user_id = $user->id;
            $log->action = "Deactivated two factor authentication";
            $log->save();
            $this->view_passkey=false;
            $this->password='';
        }else{
            $this->alert('warning','Invalid Password',[
                'icon' => 'warning',
                'toast' => true,
                'position'=>'center'
            ]);
        }
    }


    public function render()
    {
        return view('livewire.auth.user-account')->extends('components.layouts.app');
    }
}

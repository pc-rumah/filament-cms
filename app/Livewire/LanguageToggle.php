<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageToggle extends Component
{
    public $currentLanguage;
    public $availableLanguage;

    public function mount()
    {
        $this->currentLanguage = App::getLocale();
        $this->availableLanguage = config('languages');
    }

    public function render()
    {
        return view('livewire.language-toggle');
    }

    public function switchLanguage($lang)
    {
        if (array_key_exists($lang, $this->availableLanguage)) {
            Session::put('locale', $lang);
            App::setLocale($lang);
            $this->currentLanguage = $lang;

            return redirect(request()->header('Referer'));
        }
    }
}

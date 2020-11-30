<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Profile extends Component
{
    public $username = '';
    public $about = '';
    public $birthday = null;
//    public $saved = false;            how to show a flash message with just livewire

    public function mount()
    {
        $this->username = auth()->user()->username;
        $this->about = auth()->user()->about;
        $this->birthday = optional(auth()->user()->birthday)->format('d-m-Y');
    }

//    public function updated($field)     //this hides the toast message when typing into input field.
//    {
//        if($field !== 'saved') {
//            $this->saved = false;
//        }
//    }


    public function save()
    {
        $profileData = $this->validate([
            'username' => 'max:24',
            'about' => 'max:140',
            'birthday' => 'sometimes',
        ]);

        auth()->user()->update($profileData);

        $this->emit('notify-saved');            //listens for saved event

//        $this->dispatchBrowserEvent('notify');
//        $this->saved = true;

//        auth()->user()->update([
//           'username' => $this->username,
//           'about' => $this->about,
//        ]);
    }


    public function render()
    {
        return view('livewire.profile');
    }
}

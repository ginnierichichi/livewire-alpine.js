<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $username = '';
    public $about = '';
    public $birthday = null;
    public $upload;
    public $files = [];

    protected $rules = [
        'username' => 'max:24',
        'about' => 'max:140',
        'birthday' => 'sometimes',
        'upload' => 'nullable|image|max:1000',
    ];
//    public $newAvatars = [];
//    public $saved = false;            how to show a flash message with just livewire

    public function mount()
    {
        $this->username = auth()->user()->username;
        $this->about = auth()->user()->about;
        $this->birthday = optional(auth()->user()->birthday)->format('d-m-Y');
    }

    public function updatedNewAvatar()
    {
        $this->validate(['upload' => 'image|max:1000']);
    }

//    public function updated($field)     //this hides the toast message when typing into input field.
//    {
//        if($field !== 'saved') {
//            $this->saved = false;
//        }
//    }


    public function save()
    {
        $this->validate([
            'username' => 'max:24',
            'about' => 'max:140',
            'birthday' => 'sometimes',
            'upload' => 'image|max:1000',
        ]);

       $filename = $this->upload->store('avatars', 'public');
        auth()->user()->update([
            'username' => $this->username,
            'about' => $this->about,
            'birthday' => $this->birthday,
            'avatars' => $filename,
        ]);

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

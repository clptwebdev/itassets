<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromArray, withHeadings {

    public function headings(): array
    {
        return [
            "name",
            "email",
            "email_verified_at",
            "created_at",
            "telephone",
        ];
    }

    public function array(): array
    {
        $users = \App\Models\User::all();
        $object = [];
        foreach($users as $user)
        {
            $array = [];
            $array["name"] = $user->name;
            $array["email"] = $user->email;
            $array["email_verified_at"] = $user->email_verified_at ?? null;
            $array["created_at"] = $user->created_at;
            $array["telephone"] = $user->telephone;
            $object[] = $array;


        }

        return $object;
    }

}
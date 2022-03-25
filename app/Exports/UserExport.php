<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class UserExport implements FromArray, withHeadings, ShouldAutoSize, WithEvents {

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

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:E1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
            },
        ];
    }

}
